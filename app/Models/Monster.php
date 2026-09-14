<?php

namespace App\Models;

use App\Support\MonsterTranslator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'index',
        'name',
        'name_pt',
        'size',
        'type',
        'subtype',
        'alignment',
        'armor_class',
        'armor_class_note',
        'hit_points',
        'hit_dice',
        'speed',
        'strength',
        'dexterity',
        'constitution',
        'intelligence',
        'wisdom',
        'charisma',
        'proficiencies',
        'damage_vulnerabilities',
        'damage_resistances',
        'damage_immunities',
        'condition_immunities',
        'senses',
        'languages',
        'challenge_rating',
        'xp',
        'special_abilities',
        'actions',
        'legendary_actions',
        'reactions',
        'image_url',
        'source',
        'synced_at',
        'translated',
    ];

    protected $casts = [
        'speed' => 'array',
        'proficiencies' => 'array',
        'damage_vulnerabilities' => 'array',
        'damage_resistances' => 'array',
        'damage_immunities' => 'array',
        'condition_immunities' => 'array',
        'senses' => 'array',
        'special_abilities' => 'array',
        'actions' => 'array',
        'legendary_actions' => 'array',
        'reactions' => 'array',
        'armor_class' => 'integer',
        'hit_points' => 'integer',
        'strength' => 'integer',
        'dexterity' => 'integer',
        'constitution' => 'integer',
        'intelligence' => 'integer',
        'wisdom' => 'integer',
        'charisma' => 'integer',
        'challenge_rating' => 'decimal:3',
        'xp' => 'integer',
        'synced_at' => 'datetime',
        'translated' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'index';
    }

    /**
     * Quando o monstro foi importado com `--no-translate`, nenhum campo dele
     * deve passar pelos dicionários de tradução: tamanho, tipo, alinhamento,
     * sentidos etc. continuam exatamente como a API devolveu, em inglês.
     */
    public function displayName(): string
    {
        return $this->translated ? ($this->name_pt ?: $this->name) : $this->name;
    }

    public function sizeLabel(): string
    {
        return $this->translated ? MonsterTranslator::size($this->size) : ucfirst((string) $this->size);
    }

    public function typeLabel(): string
    {
        return $this->translated ? MonsterTranslator::type($this->type) : ucfirst((string) $this->type);
    }

    public function alignmentLabel(): string
    {
        return $this->translated ? MonsterTranslator::alignment($this->alignment) : ucfirst((string) $this->alignment);
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function speedEntries(): array
    {
        $entries = [];

        foreach ((array) $this->speed as $key => $value) {
            $entries[] = [
                'label' => $this->translated ? MonsterTranslator::speedLabel($key) : $this->humanizeKey($key),
                'value' => $this->translated ? MonsterTranslator::distance($value) : $value,
            ];
        }

        return $entries;
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function sensesEntries(): array
    {
        $entries = [];

        foreach ((array) $this->senses as $key => $value) {
            $isPassivePerception = $key === 'passive_perception';

            $entries[] = [
                'label' => $this->translated ? MonsterTranslator::sense($key) : $this->humanizeKey($key),
                'value' => $isPassivePerception
                    ? (string) $value
                    : ($this->translated ? MonsterTranslator::distance((string) $value) : (string) $value),
            ];
        }

        return $entries;
    }

    /**
     * @return array<int, string>
     */
    public function proficiencyLabels(): array
    {
        return array_map(
            fn (array $item) => ($this->translated
                ? MonsterTranslator::proficiencyLabel($item['label'])
                : $item['label']).' +'.$item['value'],
            (array) $this->proficiencies
        );
    }

    public function abilityLabel(string $key): string
    {
        return $this->translated ? MonsterTranslator::ability($key) : strtoupper($key);
    }

    public function abilityModifier(int $score): string
    {
        $modifier = (int) floor(($score - 10) / 2);

        return $modifier >= 0 ? "+{$modifier}" : (string) $modifier;
    }

    public function challengeRatingLabel(): string
    {
        $value = (float) $this->challenge_rating;

        $fractions = [
            0.125 => '1/8',
            0.25 => '1/4',
            0.5 => '1/2',
        ];

        return $fractions[$value] ?? rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    protected function damageVulnerabilitiesLabels(): Attribute
    {
        return $this->translatedListAttribute('damage_vulnerabilities');
    }

    protected function damageResistancesLabels(): Attribute
    {
        return $this->translatedListAttribute('damage_resistances');
    }

    protected function damageImmunitiesLabels(): Attribute
    {
        return $this->translatedListAttribute('damage_immunities');
    }

    protected function conditionImmunitiesLabels(): Attribute
    {
        return Attribute::get(fn () => array_map(
            fn ($value) => $this->translated ? MonsterTranslator::condition($value) : ucfirst((string) $value),
            (array) $this->condition_immunities
        ));
    }

    private function translatedListAttribute(string $field): Attribute
    {
        return Attribute::get(fn () => array_map(
            fn ($value) => $this->translated ? MonsterTranslator::damageType($value) : ucfirst((string) $value),
            (array) $this->{$field}
        ));
    }

    private function humanizeKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }
}
