<?php

namespace App\Support;

/**
 * Vocabulário fixo de D&D (tamanhos, tipos, alinhamentos, sentidos, danos,
 * condições, perícias...) traduzido por dicionário — rápido e sem depender
 * de uma API externa, já que esses termos técnicos se repetem em todos os
 * monstros. Textos livres (descrições e falas de habilidades) usam o
 * App\Services\TextTranslationService, que faz a tradução de verdade.
 */
class MonsterTranslator
{
    private const SIZE_MAP = [
        'tiny' => 'Minúsculo',
        'small' => 'Pequeno',
        'medium' => 'Médio',
        'large' => 'Grande',
        'huge' => 'Enorme',
        'gargantuan' => 'Gigantesco',
    ];

    private const TYPE_MAP = [
        'aberration' => 'Aberração',
        'beast' => 'Fera',
        'celestial' => 'Celestial',
        'construct' => 'Construto',
        'dragon' => 'Dragão',
        'elemental' => 'Elemental',
        'fey' => 'Feérico',
        'fiend' => 'Corruptor',
        'giant' => 'Gigante',
        'humanoid' => 'Humanoide',
        'monstrosity' => 'Monstruosidade',
        'ooze' => 'Limo',
        'plant' => 'Planta',
        'undead' => 'Morto-vivo',
        'swarm' => 'Enxame',
    ];

    private const ALIGNMENT_MAP = [
        'lawful good' => 'Leal e Bom',
        'neutral good' => 'Neutro e Bom',
        'chaotic good' => 'Caótico e Bom',
        'lawful neutral' => 'Leal e Neutro',
        'neutral' => 'Neutro',
        'true neutral' => 'Neutro Puro',
        'chaotic neutral' => 'Caótico e Neutro',
        'lawful evil' => 'Leal e Mau',
        'neutral evil' => 'Neutro e Mau',
        'chaotic evil' => 'Caótico e Mau',
        'unaligned' => 'Sem Alinhamento',
        'any alignment' => 'Qualquer Alinhamento',
        'any non-good alignment' => 'Qualquer Alinhamento Não Bom',
        'any non-lawful alignment' => 'Qualquer Alinhamento Não Leal',
        'any chaotic alignment' => 'Qualquer Alinhamento Caótico',
        'any evil alignment' => 'Qualquer Alinhamento Mau',
    ];

    private const DAMAGE_TYPE_MAP = [
        'acid' => 'Ácido',
        'bludgeoning' => 'Concussão',
        'cold' => 'Frio',
        'fire' => 'Fogo',
        'force' => 'Energia',
        'lightning' => 'Elétrico',
        'necrotic' => 'Necrótico',
        'piercing' => 'Perfuração',
        'poison' => 'Veneno',
        'psychic' => 'Psíquico',
        'radiant' => 'Radiante',
        'slashing' => 'Cortante',
        'thunder' => 'Trovão',
    ];

    private const CONDITION_MAP = [
        'blinded' => 'Cego',
        'charmed' => 'Enfeitiçado',
        'deafened' => 'Surdo',
        'exhaustion' => 'Exaustão',
        'frightened' => 'Amedrontado',
        'grappled' => 'Agarrado',
        'incapacitated' => 'Incapacitado',
        'invisible' => 'Invisível',
        'paralyzed' => 'Paralisado',
        'petrified' => 'Petrificado',
        'poisoned' => 'Envenenado',
        'prone' => 'Caído',
        'restrained' => 'Contido',
        'stunned' => 'Atordoado',
        'unconscious' => 'Inconsciente',
    ];

    private const SENSE_MAP = [
        'darkvision' => 'Visão no Escuro',
        'blindsight' => 'Visão Cega',
        'tremorsense' => 'Sentido Sísmico',
        'truesight' => 'Visão Verdadeira',
        'passive_perception' => 'Percepção Passiva',
    ];

    private const SPEED_MAP = [
        'walk' => 'Deslocamento',
        'fly' => 'Voo',
        'swim' => 'Natação',
        'climb' => 'Escalada',
        'burrow' => 'Escavação',
        'hover' => 'Flutuação',
    ];

    private const ABILITY_MAP = [
        'str' => 'FOR',
        'dex' => 'DES',
        'con' => 'CON',
        'int' => 'INT',
        'wis' => 'SAB',
        'cha' => 'CAR',
    ];

    public static function size(?string $value): string
    {
        return self::lookup(self::SIZE_MAP, $value);
    }

    public static function type(?string $value): string
    {
        return self::lookup(self::TYPE_MAP, $value);
    }

    public static function alignment(?string $value): string
    {
        if (! is_string($value) || trim($value) === '') {
            return 'Sem Alinhamento';
        }

        $normalized = strtolower(trim($value));

        if (isset(self::ALIGNMENT_MAP[$normalized])) {
            return self::ALIGNMENT_MAP[$normalized];
        }

        // Ex.: "neutral evil (50%) or neutral (50%)" — traduz cada trecho conhecido.
        $translated = preg_replace_callback('/[a-z ]+(?=,|\(|$)/', function ($matches) {
            $piece = trim($matches[0]);

            return self::ALIGNMENT_MAP[strtolower($piece)] ?? $piece;
        }, $normalized);

        return is_string($translated) ? ucfirst(trim($translated)) : ucfirst($value);
    }

    public static function damageType(?string $value): string
    {
        return self::lookup(self::DAMAGE_TYPE_MAP, $value);
    }

    public static function condition(?string $value): string
    {
        return self::lookup(self::CONDITION_MAP, $value);
    }

    public static function sense(string $key): string
    {
        return self::SENSE_MAP[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    public static function distance(?string $value): string
    {
        if (! is_string($value) || trim($value) === '') {
            return '—';
        }

        $replacements = [
            'ft.' => 'pés',
            'feet' => 'pés',
            'miles' => 'milhas',
            'mile' => 'milha',
        ];

        return trim(str_ireplace(array_keys($replacements), array_values($replacements), $value));
    }

    public static function speedLabel(string $key): string
    {
        return self::SPEED_MAP[$key] ?? ucfirst($key);
    }

    public static function ability(?string $value): string
    {
        if (! is_string($value)) {
            return '—';
        }

        return self::ABILITY_MAP[strtolower($value)] ?? strtoupper($value);
    }

    /**
     * Traduz o rótulo de uma proficiência da API ("Skill: Stealth",
     * "Saving Throw: DEX") sem precisar de tradução automática.
     */
    public static function proficiencyLabel(string $value): string
    {
        if (str_starts_with($value, 'Skill: ')) {
            $skill = substr($value, 7);

            return 'Perícia: '.(self::SKILL_MAP[strtolower($skill)] ?? $skill);
        }

        if (str_starts_with($value, 'Saving Throw: ')) {
            $ability = substr($value, 14);

            return 'Resistência: '.self::ability($ability);
        }

        return $value;
    }

    private const SKILL_MAP = [
        'acrobatics' => 'Acrobacia',
        'animal handling' => 'Lidar com Animais',
        'arcana' => 'Arcanismo',
        'athletics' => 'Atletismo',
        'deception' => 'Enganação',
        'history' => 'História',
        'insight' => 'Intuição',
        'intimidation' => 'Intimidação',
        'investigation' => 'Investigação',
        'medicine' => 'Medicina',
        'nature' => 'Natureza',
        'perception' => 'Percepção',
        'performance' => 'Atuação',
        'persuasion' => 'Persuasão',
        'religion' => 'Religião',
        'sleight of hand' => 'Prestidigitação',
        'stealth' => 'Furtividade',
        'survival' => 'Sobrevivência',
    ];

    /**
     * @param  array<string, string>  $map
     */
    private static function lookup(array $map, ?string $value): string
    {
        if (! is_string($value) || trim($value) === '') {
            return '—';
        }

        $normalized = strtolower(trim($value));

        return $map[$normalized] ?? ucfirst($value);
    }
}
