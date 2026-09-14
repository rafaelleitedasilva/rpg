<?php

namespace App\Support;

class SpellTranslator
{
    private const NAME_MAP = [
        'acid splash' => 'Respingo Ácido',
        'chill touch' => 'Toque Frio',
        'fire bolt' => 'Raio de Fogo',
        'magic missile' => 'Míssil Mágico',
        'shield' => 'Escudo',
        'mage armor' => 'Armadura Arcana',
        'light' => 'Luz',
        'detect magic' => 'Detectar Magia',
        'minor illusion' => 'Ilusão Menor',
        'sleep' => 'Sono',
        'burning hands' => 'Mãos Ardentes',
        'magic weapon' => 'Arma Mágica',
        'faerie fire' => 'Fogo Feérico',
        'fog cloud' => 'Nuvem de Nevoeiro',
        'scorching ray' => 'Raio Incandescente',
        'cure wounds' => 'Curar Ferimentos',
        'healing word' => 'Palavra de Cura',
        'guiding bolt' => 'Raio Guia',
        'hold person' => 'Imobilizar Pessoa',
        'silence' => 'Silêncio',
        'counterspell' => 'Contramágica',
        'fireball' => 'Bola de Fogo',
        'fly' => 'Voo',
        'gust of wind' => 'Rajada de Vento',
        'misty step' => 'Passo Nebuloso',
        'haste' => 'Aceleração',
        'dispel magic' => 'Dissipar Magia',
        'dimension door' => 'Porta Dimensional',
        'stoneskin' => 'Pele de Pedra',
        'animate objects' => 'Animar Objetos',
        'cloudkill' => 'Nuvem Mortal',
        'cone of cold' => 'Cone de Frio',
        'wall of force' => 'Parede de Força',
        'mass suggestion' => 'Sugestão em Massa',
        'greater invisibility' => 'Invisibilidade Maior',
        'wish' => 'Desejo',
        'dancing lights' => 'Luzes Dançantes',
        'druidcraft' => 'Ofício de Druida',
        'eldritch blast' => 'Rajada Bruxa',
        'color spray' => 'Spray de Cores',
        'thaumaturgy' => 'Taumaturgia',
        'prestidigitation' => 'Prestidigitação',
        'message' => 'Mensagem',
        'friends' => 'Amigos',
        'create or destroy water' => 'Criar ou Destruir Água',
        'detect evil and good' => 'Detectar Mal e Bem',
        'sanctuary' => 'Santuário',
        'invisibility' => 'Invisibilidade',
        'lesser restoration' => 'Restauração Menor',
        'darkness' => 'Escuridão',
        'shatter' => 'Estilhaçar',
        'resurrection' => 'Ressurreição',
        'raise dead' => 'Reanimar Mortos',
        'barkskin' => 'Pele de Árvore',
        'spirit guardians' => 'Guardiões Espirituais',
        'etherealness' => 'Etéreo',
        'hallow' => 'Consagração',
        'sleet storm' => 'Tempestade de Granizo',
        'wall of stone' => 'Parede de Pedra',
        'shocking grasp' => 'Abraço Elétrico',
    ];

    private const WORD_MAP = [
        'acid' => 'ácido',
        'splash' => 'respingo',
        'chill' => 'frio',
        'touch' => 'toque',
        'fire' => 'fogo',
        'bolt' => 'raio',
        'magic' => 'mágica',
        'missile' => 'míssil',
        'shield' => 'escudo',
        'mage' => 'arcano',
        'armor' => 'armadura',
        'light' => 'luz',
        'lights' => 'luzes',
        'dancing' => 'dançantes',
        'detect' => 'detectar',
        'minor' => 'menor',
        'illusion' => 'ilusão',
        'sleep' => 'sono',
        'burning' => 'ardente',
        'hands' => 'mãos',
        'weapon' => 'arma',
        'faerie' => 'feérico',
        'fog' => 'nevoeiro',
        'cloud' => 'nuvem',
        'scorching' => 'incandescente',
        'ray' => 'raio',
        'cure' => 'curar',
        'wounds' => 'ferimentos',
        'healing' => 'cura',
        'word' => 'palavra',
        'guiding' => 'guia',
        'hold' => 'imobilizar',
        'person' => 'pessoa',
        'silence' => 'silêncio',
        'counterspell' => 'contramágica',
        'ball' => 'bola',
        'fly' => 'voo',
        'gust' => 'rajada',
        'of' => 'de',
        'wind' => 'vento',
        'misty' => 'nebuloso',
        'step' => 'passo',
        'haste' => 'aceleração',
        'dispel' => 'dissipar',
        'dimension' => 'dimensional',
        'door' => 'porta',
        'stone' => 'pedra',
        'skin' => 'pele',
        'animate' => 'animar',
        'objects' => 'objetos',
        'kill' => 'mortal',
        'cone' => 'cone',
        'cold' => 'frio',
        'wall' => 'parede',
        'force' => 'força',
        'mass' => 'massa',
        'suggestion' => 'sugestão',
        'greater' => 'maior',
        'invisibility' => 'invisibilidade',
        'wish' => 'desejo',
        'portal' => 'portal',
        'summon' => 'conjurar',
        'monster' => 'monstro',
        'bless' => 'bênção',
        'command' => 'comando',
        'study' => 'estudo',
        'feather' => 'pena',
        'fall' => 'queda',
        'water' => 'água',
        'breathing' => 'respiração',
        'speak' => 'fala',
        'with' => 'com',
        'dead' => 'morto',
        'deadly' => 'letal',
        'dragon' => 'dragão',
        'breeze' => 'brisa',
        'storm' => 'tempestade',
        'druidcraft' => 'ofício de druida',
        'craft' => 'artesanato',
        'eldritch' => 'bruxa',
        'blast' => 'rajada',
        'dancing' => 'dançantes',
        'lights' => 'luzes',
        'color' => 'cor',
        'spray' => 'spray',
        'thaumaturgy' => 'taumaturgia',
        'prestidigitation' => 'prestidigitação',
        'message' => 'mensagem',
        'friends' => 'amigos',
        'create' => 'criar',
        'destroy' => 'destruir',
        'evil' => 'mal',
        'good' => 'bem',
        'sanctuary' => 'santuário',
        'darkness' => 'escuridão',
        'shatter' => 'estilhaçar',
        'resurrection' => 'ressurreição',
        'raise' => 'reanimar',
        'barkskin' => 'pele de árvore',
        'spirit' => 'espírito',
        'guardians' => 'guardiões',
        'etherealness' => 'etéreo',
        'hallow' => 'consagração',
        'sleet' => 'granizo',
        'wall' => 'parede',
        'shocking' => 'elétrico',
        'grasp' => 'abraço',
    ];

    private const SCHOOL_MAP = [
        'abjuration' => 'Abjuração',
        'conjuration' => 'Conjuração',
        'divination' => 'Adivinhação',
        'enchantment' => 'Encantamento',
        'evocation' => 'Evocação',
        'illusion' => 'Ilusão',
        'necromancy' => 'Necromancia',
        'transmutation' => 'Transmutação',
        'unknown' => 'Desconhecida',
    ];

    /**
     * The eight schools of magic, keyed by the English value stored on the
     * spell (used for the "Escola" filter dropdown).
     *
     * @return array<string, string>
     */
    public static function schools(): array
    {
        return array_diff_key(self::SCHOOL_MAP, ['unknown' => true]);
    }

    /**
     * One gh-icon name per school of magic, so each school reads as its own
     * symbol in the catalog instead of sharing a generic sparkle.
     */
    private const SCHOOL_ICON_MAP = [
        'abjuration' => 'shield',
        'conjuration' => 'portal',
        'divination' => 'eye',
        'enchantment' => 'spiral',
        'evocation' => 'flame',
        'illusion' => 'mask',
        'necromancy' => 'skull',
        'transmutation' => 'cycle',
    ];

    public static function schoolIcon(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return 'sparkles';
        }

        return self::SCHOOL_ICON_MAP[strtolower(trim($value))] ?? 'sparkles';
    }

    private const CLASS_MAP = [
        'wizard' => 'Mago',
        'cleric' => 'Clérigo',
        'druid' => 'Druida',
        'bard' => 'Bardo',
        'paladin' => 'Paladino',
        'sorcerer' => 'Feiticeiro',
        'ranger' => 'Patrulheiro',
        'warlock' => 'Bruxo',
        'artificer' => 'Artífice',
        'fighter' => 'Guerreiro',
        'rogue' => 'Ladino',
    ];

    private const RACE_MAP = [
        'human' => 'Humano',
        'elf' => 'Elfo',
        'dwarf' => 'Anão',
        'tiefling' => 'Tiefling',
        'dragonborn' => 'Draconato',
        'half-elf' => 'Meio-Elfo',
        'gnome' => 'Gnomo',
        'half-orc' => 'Meio-Orc',
        'hobbit' => 'Halfling',
        'halfling' => 'Halfling',
    ];

    public static function name(string $value): string
    {
        $normalized = strtolower(trim($value));

        if (isset(self::NAME_MAP[$normalized])) {
            return self::NAME_MAP[$normalized];
        }

        $words = preg_split('/\s+|[-_]+/', $normalized) ?: [$normalized];
        $translated = array_map(static function (string $word): string {
            if (isset(self::WORD_MAP[$word])) {
                return self::WORD_MAP[$word];
            }

            return $word;
        }, $words);

        $phrase = implode(' ', $translated);

        return self::titleCase($phrase);
    }

    public static function school(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return 'Desconhecida';
        }

        $normalized = strtolower(trim($value));

        return self::SCHOOL_MAP[$normalized] ?? ucfirst($value);
    }

    public static function classList(array $values): string
    {
        if ($values === []) {
            return '—';
        }

        $translated = array_map(static function ($value) {
            $normalized = strtolower((string) $value);

            return self::CLASS_MAP[$normalized] ?? ucfirst((string) $value);
        }, $values);

        return implode(', ', $translated);
    }

    public static function raceList(array $values): string
    {
        if ($values === []) {
            return '—';
        }

        $translated = array_map(static function ($value) {
            $normalized = strtolower((string) $value);

            return self::RACE_MAP[$normalized] ?? ucfirst((string) $value);
        }, $values);

        return implode(', ', $translated);
    }

    public static function duration(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return '—';
        }

        $lower = strtolower($value);

        $replacements = [
            'instantaneous' => 'Instantânea',
            'concentration' => 'Concentração',
            'up to' => 'até',
            'minutes' => 'minutos',
            'minute' => 'minuto',
            'hours' => 'horas',
            'hour' => 'hora',
            'rounds' => 'rodadas',
            'round' => 'rodada',
            'days' => 'dias',
            'day' => 'dia',
            'action' => 'ação',
            'bonus action' => 'ação bônus',
            'reaction' => 'reação',
            'special' => 'especial',
            'until dispelled' => 'até ser dissipada',
            'until the spell ends' => 'até a magia terminar',
            'until the end of your next turn' => 'até o fim do seu próximo turno',
            '1 minute' => '1 minuto',
            '10 minutes' => '10 minutos',
            '1 hour' => '1 hora',
            '8 hours' => '8 horas',
        ];

        foreach ($replacements as $needle => $replacement) {
            $lower = str_replace($needle, $replacement, $lower);
        }

        return ucwords($lower);
    }

    public static function range(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return '—';
        }

        $lower = self::feetToMeters(strtolower($value));

        $replacements = [
            'self' => 'Pessoal',
            'touch' => 'Toque',
            'sight' => 'Visão',
            'miles' => 'milhas',
            'mile' => 'milha',
            'unlimited' => 'ilimitada',
            'special' => 'especial',
            'radius' => 'raio',
        ];

        foreach ($replacements as $needle => $replacement) {
            $lower = str_replace($needle, $replacement, $lower);
        }

        return ucwords($lower);
    }

    /**
     * D&D distances convert to metric at a flat 1 foot = 0.3 meters — the
     * same factor the official metric conversion charts use, which happens
     * to land every common 5e distance (5, 10, 15, 30, 60, 90, 120, 150,
     * 300, 500 ft...) on a clean number of meters.
     */
    private static function feetToMeters(string $text): string
    {
        $converted = preg_replace_callback(
            '/(\d+(?:\.\d+)?)-?\s?(?:feet|foot|ft\.?)\b/i',
            static function (array $matches): string {
                $meters = ((float) $matches[1]) * 0.3;
                $decimals = $meters == floor($meters) ? 0 : 1;

                return number_format($meters, $decimals, ',', '.').' metros';
            },
            $text
        );

        return $converted ?? $text;
    }

    public static function components(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return '—';
        }

        $replacements = [
            'V' => 'V',
            'S' => 'S',
            'M' => 'M',
            'v' => 'V',
            's' => 'S',
            'm' => 'M',
            'verbal' => 'Verbal',
            'somatic' => 'Somático',
            'material' => 'Material',
        ];

        $normalized = preg_replace('/\s*,\s*/', ', ', trim($value));

        if (! is_string($normalized)) {
            return $value;
        }

        foreach ($replacements as $needle => $replacement) {
            $normalized = preg_replace('/\b' . preg_quote($needle, '/') . '\b/i', $replacement, $normalized);
        }

        return $normalized;
    }

    public static function castingTime(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return '—';
        }

        $lower = strtolower($value);

        $replacements = [
            'action' => 'ação',
            'bonus action' => 'ação bônus',
            'reaction' => 'reação',
            'minute' => 'minuto',
            'minutes' => 'minutos',
            'hour' => 'hora',
            'hours' => 'horas',
            'round' => 'rodada',
            'rounds' => 'rodadas',
        ];

        foreach ($replacements as $needle => $replacement) {
            $lower = str_replace($needle, $replacement, $lower);
        }

        return ucwords($lower);
    }

    public static function description(?string $value): string
    {
        if (! is_string($value) || $value === '') {
            return '—';
        }

        $text = self::feetToMeters($value);
        $replacements = [
            'You hurl' => 'Você arremessa',
            'a bubble of acid.' => 'uma bolha de ácido.',
            'Choose one creature' => 'Escolha uma criatura',
            'within range' => 'dentro do alcance',
            'or choose two creatures' => 'ou escolha duas criaturas',
            'that are within 1,5 metros of each other.' => 'que estejam a 1,5 metros de distância uma da outra.',
            'must succeed on a dexterity saving throw' => 'deve fazer um teste de resistência de destreza',
            'damage.' => 'de dano.',
            'You create' => 'Você cria',
            'The target' => 'A criatura-alvo',
            'until the end of its next turn' => 'até o fim do seu próximo turno',
            'A creature that touches the target' => 'Uma criatura que tocar a alvo',
            'for the duration' => 'pela duração',
            'up to' => 'até',
            'of' => 'de',
            'can' => 'pode',
            'and' => 'e',
            'the' => 'o',
        ];

        foreach ($replacements as $needle => $replacement) {
            $text = str_replace($needle, $replacement, $text);
        }

        return trim($text);
    }

    private static function titleCase(string $value): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value));

        if (! is_string($value) || $value === '') {
            return '—';
        }

        $parts = explode(' ', strtolower($value));
        $parts = array_map(static fn (string $part) => ucfirst($part), $parts);

        return implode(' ', $parts);
    }
}
