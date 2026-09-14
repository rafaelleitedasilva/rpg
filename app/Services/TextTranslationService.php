<?php

namespace App\Services;

use App\Models\TranslationCache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

/**
 * Traduz textos livres (descrições, habilidades, ações de magias e monstros)
 * vindos de APIs em inglês para português, usando o pacote gratuito
 * stichoza/google-translate-php.
 *
 * Cada texto só é enviado para tradução uma vez: o resultado fica guardado
 * na tabela "translation_cache" e reaproveitado nas próximas sincronizações,
 * o que também evita bater no limite de requisições do Google Translate.
 * Se a tradução falhar (indisponibilidade, bloqueio por excesso de uso etc.),
 * o texto original é devolvido para que a sincronização nunca quebre.
 */
class TextTranslationService
{
    private const LOCALE = 'pt';

    private const RETRY_ATTEMPTS = 3;

    /**
     * Abaixo desse tamanho não dá pra confiar que um texto idêntico ao
     * original seja sinal de falha (pode ser uma sigla, um nome próprio etc).
     */
    private const ECHO_GUARD_MIN_LENGTH = 12;

    /**
     * O endpoint gratuito do Google Translate bloqueia (silenciosamente,
     * devolvendo o texto original) quando detecta muitas requisições
     * seguidas da mesma origem em pouco tempo — na prática, algo como
     * "requisições por segundo", não um limite de volume total. Chamadas
     * isoladas e espaçadas sempre funcionam; um laço de sincronização
     * disparando uma atrás da outra é o que aciona o bloqueio. Por isso
     * esperamos alguns segundos antes de cada chamada, imitando um ritmo
     * humano em vez de um script em rajada.
     */
    private const REQUEST_PACE_MICROSECONDS = 2_500_000;

    public function __construct(private ?GoogleTranslate $client = null)
    {
        $this->client ??= new GoogleTranslate(self::LOCALE, 'en');
    }

    public function translate(?string $text): ?string
    {
        $text = is_string($text) ? trim($text) : $text;

        if ($text === null || $text === '') {
            return $text;
        }

        $hash = md5(self::LOCALE.':'.$text);

        $cached = TranslationCache::query()->where('hash', $hash)->first();

        if ($cached) {
            return $cached->translated_text;
        }

        $translated = $this->translateWithRetry($text);

        if ($translated === null) {
            return $text;
        }

        TranslationCache::query()->create([
            'hash' => $hash,
            'locale' => self::LOCALE,
            'source_text' => $text,
            'translated_text' => $translated,
        ]);

        return $translated;
    }

    /**
     * @param  array<int, string|null>  $texts
     * @return array<int, string|null>
     */
    public function translateMany(array $texts): array
    {
        return array_map(fn ($text) => $this->translate($text), $texts);
    }

    private function translateWithRetry(string $text): ?string
    {
        for ($attempt = 1; $attempt <= self::RETRY_ATTEMPTS; $attempt++) {
            usleep(self::REQUEST_PACE_MICROSECONDS);

            try {
                $result = $this->client->translate($text);

                if (is_string($result) && $result !== '' && ! $this->looksUntranslated($text, $result)) {
                    return $result;
                }

                // O Google Translate raramente devolve um erro quando está
                // limitando requisições em excesso: em vez disso, ele
                // silenciosamente "ecoa" o texto original sem traduzir.
                // Tratamos isso como falha para não guardar lixo no cache,
                // e esperamos mais antes de tentar de novo.
                Log::warning('Tradução retornou o texto original (possível bloqueio por excesso de uso).', [
                    'attempt' => $attempt,
                ]);

                usleep(1_500_000 * $attempt);
            } catch (Throwable $e) {
                Log::warning('Falha ao traduzir texto via Google Translate.', [
                    'attempt' => $attempt,
                    'message' => $e->getMessage(),
                ]);

                usleep(1_500_000 * $attempt);
            }
        }

        return null;
    }

    private function looksUntranslated(string $original, string $result): bool
    {
        if (mb_strlen($original) < self::ECHO_GUARD_MIN_LENGTH) {
            return false;
        }

        return $this->normalize($original) === $this->normalize($result);
    }

    private function normalize(string $value): string
    {
        return mb_strtolower(preg_replace('/\s+/', ' ', trim($value)) ?? trim($value));
    }
}
