<?php

namespace App\Console\Commands;

use App\Models\Spell;
use App\Services\TextTranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncDndSpells extends Command
{
    protected $signature = 'spells:sync
        {--fresh : Remove existing spell records before import}
        {--no-translate : Importa apenas em inglês, sem consultar o serviço de tradução}';

    protected $aliases = ['rpg:sync-spells'];

    protected $description = 'Sincroniza a base de magias do sistema D&D 5e com a API oficial.';

    public function handle(TextTranslationService $translator): int
    {
        $translate = ! $this->option('no-translate');
        if ($this->option('fresh')) {
            Spell::query()->delete();
        }

        $indexResponse = Http::timeout(30)->get('https://www.dnd5eapi.co/api/spells');

        if ($indexResponse->failed()) {
            $this->error('Não foi possível sincronizar as magias: a API não respondeu com sucesso.');

            return self::FAILURE;
        }

        $results = $indexResponse->json('results', []);

        if ($results === []) {
            $this->warn('Nenhuma magia foi retornada pela API do D&D 5e.');

            return self::SUCCESS;
        }

        $imported = 0;

        foreach ($results as $result) {
            $spellUrl = $result['url'] ?? null;

            if (! is_string($spellUrl) || $spellUrl === '') {
                continue;
            }

            $detailResponse = Http::timeout(30)->get('https://www.dnd5eapi.co'.$spellUrl);

            if ($detailResponse->failed()) {
                continue;
            }

            $payload = $detailResponse->json();
            $spellName = $payload['name'] ?? null;

            if (! is_string($spellName) || $spellName === '') {
                continue;
            }

            $classes = array_values(array_map(static fn ($item) => $item['name'] ?? '', $payload['classes'] ?? []));
            $races = array_values(array_map(static fn ($item) => $item['name'] ?? '', $payload['races'] ?? []));
            $description = implode(' ', $payload['desc'] ?? []);

            Spell::updateOrCreate(
                ['name' => $spellName],
                [
                    'level' => (int) ($payload['level'] ?? 0),
                    'school' => $payload['school']['name'] ?? 'Desconhecida',
                    'casting_time' => $payload['casting_time'] ?? '1 ação',
                    'range' => $payload['range'] ?? 'Pessoal',
                    'components' => implode(', ', $payload['components'] ?? []),
                    'duration' => $payload['duration'] ?? 'Instantânea',
                    'concentration' => (bool) ($payload['concentration'] ?? false),
                    'ritual' => (bool) ($payload['ritual'] ?? false),
                    'description' => $description,
                    'description_pt' => $translate ? $translator->translate($description) : null,
                    'classes' => array_values(array_filter($classes, static fn ($value) => $value !== '')),
                    'races' => array_values(array_filter($races, static fn ($value) => $value !== '')),
                    'translated' => $translate,
                ]
            );

            $imported++;
        }

        $this->info(sprintf('Sincronização concluída: %d magias disponíveis.', $imported));

        return self::SUCCESS;
    }
}
