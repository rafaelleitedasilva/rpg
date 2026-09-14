<?php

namespace App\Console\Commands;

use App\Models\Monster;
use App\Services\TextTranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncDndMonsters extends Command
{
    protected $signature = 'monsters:sync
        {--fresh : Remove os monstros já cadastrados antes de importar}
        {--no-translate : Importa apenas em inglês, sem consultar o serviço de tradução}';

    protected $description = 'Sincroniza o catálogo de monstros do sistema D&D 5e com a API oficial, traduzindo os textos para português.';

    private const BASE_URL = 'https://www.dnd5eapi.co';

    public function handle(TextTranslationService $translator): int
    {
        if ($this->option('fresh')) {
            Monster::query()->delete();
        }

        $indexResponse = Http::timeout(30)->get(self::BASE_URL.'/api/2014/monsters');

        if ($indexResponse->failed()) {
            $this->error('Não foi possível sincronizar os monstros: a API não respondeu com sucesso.');

            return self::FAILURE;
        }

        $results = $indexResponse->json('results', []);

        if ($results === []) {
            $this->warn('Nenhum monstro foi retornado pela API do D&D 5e.');

            return self::SUCCESS;
        }

        $translate = ! $this->option('no-translate');

        $bar = $this->output->createProgressBar(count($results));
        $bar->start();

        $imported = 0;

        foreach ($results as $result) {
            $monsterUrl = $result['url'] ?? null;

            if (! is_string($monsterUrl) || $monsterUrl === '') {
                $bar->advance();

                continue;
            }

            $detailResponse = Http::timeout(30)->get(self::BASE_URL.$monsterUrl);

            if ($detailResponse->failed()) {
                $bar->advance();

                continue;
            }

            $payload = $detailResponse->json();
            $index = $payload['index'] ?? null;
            $name = $payload['name'] ?? null;

            if (! is_string($index) || $index === '' || ! is_string($name) || $name === '') {
                $bar->advance();

                continue;
            }

            $armorClass = $payload['armor_class'][0] ?? [];
            $imagePath = $payload['image'] ?? null;

            Monster::query()->updateOrCreate(
                ['index' => $index],
                [
                    'name' => $name,
                    'name_pt' => $translate ? $translator->translate($name) : null,
                    'size' => $payload['size'] ?? 'Medium',
                    'type' => $payload['type'] ?? 'humanoid',
                    'subtype' => $payload['subtype'] ?? null,
                    'alignment' => $payload['alignment'] ?? null,
                    'armor_class' => (int) ($armorClass['value'] ?? 10),
                    'armor_class_note' => $this->armorClassNote($armorClass),
                    'hit_points' => (int) ($payload['hit_points'] ?? 1),
                    'hit_dice' => $payload['hit_points_roll'] ?? $payload['hit_dice'] ?? null,
                    'speed' => $payload['speed'] ?? [],
                    'strength' => (int) ($payload['strength'] ?? 10),
                    'dexterity' => (int) ($payload['dexterity'] ?? 10),
                    'constitution' => (int) ($payload['constitution'] ?? 10),
                    'intelligence' => (int) ($payload['intelligence'] ?? 10),
                    'wisdom' => (int) ($payload['wisdom'] ?? 10),
                    'charisma' => (int) ($payload['charisma'] ?? 10),
                    'proficiencies' => $this->mapProficiencies($payload['proficiencies'] ?? []),
                    'damage_vulnerabilities' => $payload['damage_vulnerabilities'] ?? [],
                    'damage_resistances' => $payload['damage_resistances'] ?? [],
                    'damage_immunities' => $payload['damage_immunities'] ?? [],
                    'condition_immunities' => array_map(
                        static fn ($item) => $item['name'] ?? $item['index'] ?? '',
                        $payload['condition_immunities'] ?? []
                    ),
                    'senses' => $payload['senses'] ?? [],
                    'languages' => $translate
                        ? $translator->translate($payload['languages'] ?? null)
                        : ($payload['languages'] ?? null),
                    'challenge_rating' => (float) ($payload['challenge_rating'] ?? 0),
                    'xp' => (int) ($payload['xp'] ?? 0),
                    'special_abilities' => $this->translateEntries($payload['special_abilities'] ?? [], $translator, $translate),
                    'actions' => $this->translateEntries($payload['actions'] ?? [], $translator, $translate),
                    'legendary_actions' => $this->translateEntries($payload['legendary_actions'] ?? [], $translator, $translate),
                    'reactions' => $this->translateEntries($payload['reactions'] ?? [], $translator, $translate),
                    'image_url' => is_string($imagePath) && $imagePath !== '' ? self::BASE_URL.$imagePath : null,
                    'source' => 'dnd5eapi',
                    'synced_at' => now(),
                    'translated' => $translate,
                ]
            );

            $imported++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info(sprintf('Sincronização concluída: %d monstros disponíveis no grimório.', $imported));

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $armorClass
     */
    private function armorClassNote(array $armorClass): ?string
    {
        $items = array_map(
            static fn ($item) => $item['name'] ?? null,
            $armorClass['armor'] ?? []
        );

        $items = array_values(array_filter($items));

        return $items === [] ? ($armorClass['type'] ?? null) : implode(', ', $items);
    }

    /**
     * @param  array<int, array<string, mixed>>  $proficiencies
     * @return array<int, array{label: string, value: int}>
     */
    private function mapProficiencies(array $proficiencies): array
    {
        return array_values(array_filter(array_map(static function ($item) {
            $name = $item['proficiency']['name'] ?? null;

            if (! is_string($name)) {
                return null;
            }

            return [
                'label' => $name,
                'value' => (int) ($item['value'] ?? 0),
            ];
        }, $proficiencies)));
    }

    /**
     * Traduz nome e descrição de cada habilidade/ação, mantendo o texto
     * original disponível como referência (e como rede de segurança caso
     * a tradução falhe).
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function translateEntries(array $entries, TextTranslationService $translator, bool $translate): array
    {
        return array_map(function (array $entry) use ($translator, $translate) {
            $name = $entry['name'] ?? '';
            $desc = $entry['desc'] ?? '';

            return [
                'name' => $name,
                'name_pt' => $translate ? $translator->translate($name) : null,
                'desc' => $desc,
                'desc_pt' => $translate ? $translator->translate($desc) : null,
                'attack_bonus' => $entry['attack_bonus'] ?? null,
                'damage' => array_map(static function ($damage) {
                    return [
                        'type' => $damage['damage_type']['name'] ?? null,
                        'dice' => $damage['damage_dice'] ?? null,
                    ];
                }, $entry['damage'] ?? []),
            ];
        }, $entries);
    }
}
