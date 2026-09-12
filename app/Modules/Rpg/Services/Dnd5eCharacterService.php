<?php

namespace App\Modules\Rpg\Services;

class Dnd5eCharacterService
{
    /**
     * The six core abilities, in sheet order.
     *
     * @var array<string, string>
     */
    public const ABILITIES = [
        'strength' => 'Força',
        'dexterity' => 'Destreza',
        'constitution' => 'Constituição',
        'intelligence' => 'Inteligência',
        'wisdom' => 'Sabedoria',
        'charisma' => 'Carisma',
    ];

    /**
     * The eighteen skills, each tied to its governing ability.
     *
     * @var array<string, array{label: string, ability: string}>
     */
    public const SKILLS = [
        'athletics' => ['label' => 'Atletismo', 'ability' => 'strength'],
        'acrobatics' => ['label' => 'Acrobacia', 'ability' => 'dexterity'],
        'sleight_of_hand' => ['label' => 'Prestidigitação', 'ability' => 'dexterity'],
        'stealth' => ['label' => 'Furtividade', 'ability' => 'dexterity'],
        'arcana' => ['label' => 'Arcanismo', 'ability' => 'intelligence'],
        'history' => ['label' => 'História', 'ability' => 'intelligence'],
        'investigation' => ['label' => 'Investigação', 'ability' => 'intelligence'],
        'nature' => ['label' => 'Natureza', 'ability' => 'intelligence'],
        'religion' => ['label' => 'Religião', 'ability' => 'intelligence'],
        'animal_handling' => ['label' => 'Adestrar Animais', 'ability' => 'wisdom'],
        'insight' => ['label' => 'Intuição', 'ability' => 'wisdom'],
        'medicine' => ['label' => 'Medicina', 'ability' => 'wisdom'],
        'perception' => ['label' => 'Percepção', 'ability' => 'wisdom'],
        'survival' => ['label' => 'Sobrevivência', 'ability' => 'wisdom'],
        'deception' => ['label' => 'Enganação', 'ability' => 'charisma'],
        'intimidation' => ['label' => 'Intimidação', 'ability' => 'charisma'],
        'performance' => ['label' => 'Atuação', 'ability' => 'charisma'],
        'persuasion' => ['label' => 'Persuasão', 'ability' => 'charisma'],
    ];

    /**
     * Sizes available on the sheet.
     *
     * @var list<string>
     */
    public const SIZES = ['Miúdo', 'Pequeno', 'Médio', 'Grande', 'Enorme', 'Descomunal'];

    /**
     * The nine classic alignments.
     *
     * @var list<string>
     */
    public const ALIGNMENTS = [
        'Leal e Bom', 'Neutro e Bom', 'Caótico e Bom',
        'Leal e Neutro', 'Neutro', 'Caótico e Neutro',
        'Leal e Mau', 'Neutro e Mau', 'Caótico e Mau',
    ];

    /**
     * Abilities that can fuel spellcasting, keyed by value stored on the character.
     *
     * @var array<string, string>
     */
    public const SPELLCASTING_ABILITIES = [
        'intelligence' => 'Inteligência',
        'wisdom' => 'Sabedoria',
        'charisma' => 'Carisma',
    ];

    /**
     * Total experience required to reach each character level (SRD table).
     *
     * @var array<int, int>
     */
    public const XP_THRESHOLDS = [
        1 => 0, 2 => 300, 3 => 900, 4 => 2700, 5 => 6500,
        6 => 14000, 7 => 23000, 8 => 34000, 9 => 48000, 10 => 64000,
        11 => 85000, 12 => 100000, 13 => 120000, 14 => 140000, 15 => 165000,
        16 => 195000, 17 => 225000, 18 => 265000, 19 => 305000, 20 => 355000,
    ];

    public function modifier(int $score): int
    {
        return (int) floor(($score - 10) / 2);
    }

    public function proficiencyBonus(int $level): int
    {
        return (int) max(2, floor(($level - 1) / 4) + 2);
    }

    /**
     * How far a character has progressed from their current level toward the next one.
     *
     * @return array{current: int, needed: int, percent: int, maxed: bool}
     */
    public function xpProgress(int $level, int $experience): array
    {
        $level = max(1, min(20, $level));
        $floor = self::XP_THRESHOLDS[$level];

        if ($level >= 20) {
            return ['current' => $experience, 'needed' => $experience, 'percent' => 100, 'maxed' => true];
        }

        $ceiling = self::XP_THRESHOLDS[$level + 1];
        $current = max(0, $experience - $floor);
        $needed = $ceiling - $floor;
        $percent = $needed > 0 ? (int) round(min(100, max(0, $current / $needed * 100))) : 100;

        return ['current' => $current, 'needed' => $needed, 'percent' => $percent, 'maxed' => false];
    }

    /**
     * Bonus for a given saving throw.
     */
    public function savingThrowBonus(int $abilityScore, int $proficiencyBonus, bool $proficient): int
    {
        return $this->modifier($abilityScore) + ($proficient ? $proficiencyBonus : 0);
    }

    /**
     * Bonus for a given skill check.
     */
    public function skillBonus(int $abilityScore, int $proficiencyBonus, bool $proficient, bool $expertise = false): int
    {
        $bonus = $this->modifier($abilityScore);

        if ($proficient) {
            $bonus += $proficiencyBonus;
        }

        if ($expertise && $proficient) {
            $bonus += $proficiencyBonus;
        }

        return $bonus;
    }

    public function passivePerception(int $perceptionSkillBonus): int
    {
        return 10 + $perceptionSkillBonus;
    }

    public function spellSaveDc(int $spellcastingAbilityScore, int $proficiencyBonus): int
    {
        return 8 + $this->modifier($spellcastingAbilityScore) + $proficiencyBonus;
    }

    public function spellAttackBonus(int $spellcastingAbilityScore, int $proficiencyBonus): int
    {
        return $this->modifier($spellcastingAbilityScore) + $proficiencyBonus;
    }

    /**
     * Suggests a default spellcasting ability based on the character's class.
     */
    public function defaultSpellcastingAbility(?string $class): ?string
    {
        if ($class === null || $class === '') {
            return null;
        }

        $normalized = $this->normalizeClassName($class);

        $intelligence = ['wizard', 'mago', 'artificer', 'artifice', 'artifice'];
        $wisdom = ['cleric', 'clerigo', 'druid', 'druida', 'ranger', 'patrulheiro'];
        $charisma = ['bard', 'bardo', 'sorcerer', 'feiticeiro', 'warlock', 'bruxo', 'paladin', 'paladino'];

        if (in_array($normalized, $intelligence, true)) {
            return 'intelligence';
        }

        if (in_array($normalized, $wisdom, true)) {
            return 'wisdom';
        }

        if (in_array($normalized, $charisma, true)) {
            return 'charisma';
        }

        return null;
    }

    public function spellSlots(int $level, ?string $class = null): array
    {
        $table = [
            1 => [1 => 2],
            2 => [1 => 3],
            3 => [1 => 4, 2 => 2],
            4 => [1 => 4, 2 => 3],
            5 => [1 => 4, 2 => 3, 3 => 2],
            6 => [1 => 4, 2 => 3, 3 => 3],
            7 => [1 => 4, 2 => 3, 3 => 3, 4 => 1],
            8 => [1 => 4, 2 => 3, 3 => 3, 4 => 2],
            9 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 1],
            10 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2],
            11 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1],
            12 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1],
            13 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1, 7 => 1],
            14 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1, 7 => 1],
            15 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1, 7 => 1, 8 => 1],
            16 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1, 7 => 1, 8 => 1],
            17 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 1, 7 => 1, 8 => 1, 9 => 1],
            18 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 6 => 1, 7 => 1, 8 => 1, 9 => 1],
            19 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 6 => 2, 7 => 1, 8 => 1, 9 => 1],
            20 => [1 => 4, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 6 => 2, 7 => 2, 8 => 1, 9 => 1],
        ];

        if ($class === null || $class === '') {
            return $table[$level] ?? [];
        }

        if (! $this->supportsSpellcasting($class)) {
            return [];
        }

        return $table[$level] ?? [];
    }

    public function supportsSpellcasting(?string $class): bool
    {
        if ($class === null || $class === '') {
            return false;
        }

        $normalized = $this->normalizeClassName($class);
        $values = [
            'wizard',
            'mago',
            'sorcerer',
            'feiticeiro',
            'cleric',
            'clerigo',
            'clérigo',
            'druid',
            'druida',
            'bard',
            'bardo',
            'ranger',
            'patrulheiro',
            'paladin',
            'paladino',
            'warlock',
            'bruxo',
            'artificer',
            'artifice',
            'artífice',
        ];

        return in_array($normalized, $values, true);
    }

    private function normalizeClassName(string $class): string
    {
        $normalized = strtolower(trim($class));

        return strtolower(str_replace(['é', 'í', 'á', 'à', 'ã', 'ç'], ['e', 'i', 'a', 'a', 'a', 'c'], $normalized));
    }
}
