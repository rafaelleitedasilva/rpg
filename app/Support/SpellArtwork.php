<?php

namespace App\Support;

/**
 * Deterministic, minimalist visual theme for a spell's card thumbnail — an
 * accent color plus one of gh-icon's symbols.
 *
 * There is no reliable, freely licensable source of per-spell artwork (D&D
 * spell illustrations are Wizards of the Coast IP), so every spell gets a
 * small generated icon instead of a photo. The theme is picked from
 * keywords in the spell's own (English) name/description first — so it
 * reads as "fire", "cold", "acid"... the way the spell's own name evokes —
 * falling back to its school of magic when nothing matches.
 */
class SpellArtwork
{
    /**
     * Checked in order; the first keyword found in the spell's name or
     * description wins. Order matters where words could overlap.
     *
     * @var array<string, array{icon: string, color: string}>
     */
    private const KEYWORDS = [
        'fire' => ['icon' => 'flame', 'color' => '#f0793c'],
        'flame' => ['icon' => 'flame', 'color' => '#f0793c'],
        'burn' => ['icon' => 'flame', 'color' => '#f0793c'],
        'scorch' => ['icon' => 'flame', 'color' => '#f0793c'],
        'ember' => ['icon' => 'flame', 'color' => '#f0793c'],
        'cold' => ['icon' => 'snowflake', 'color' => '#5ec8e0'],
        'ice' => ['icon' => 'snowflake', 'color' => '#5ec8e0'],
        'frost' => ['icon' => 'snowflake', 'color' => '#5ec8e0'],
        'chill' => ['icon' => 'snowflake', 'color' => '#5ec8e0'],
        'sleet' => ['icon' => 'snowflake', 'color' => '#5ec8e0'],
        'lightning' => ['icon' => 'zap', 'color' => '#e8c94e'],
        'thunder' => ['icon' => 'zap', 'color' => '#e8c94e'],
        'shock' => ['icon' => 'zap', 'color' => '#e8c94e'],
        'spark' => ['icon' => 'zap', 'color' => '#e8c94e'],
        'acid' => ['icon' => 'droplet', 'color' => '#7bc96f'],
        'splash' => ['icon' => 'droplet', 'color' => '#7bc96f'],
        'poison' => ['icon' => 'droplet', 'color' => '#84a862'],
        'venom' => ['icon' => 'droplet', 'color' => '#84a862'],
        'death' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'dead' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'necro' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'wither' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'raise' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'radiant' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'holy' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'guiding' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'sun' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'light' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'heal' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'cure' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'restoration' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'bless' => ['icon' => 'sun', 'color' => '#f2c14e'],
        'psychic' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'mind' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'charm' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'suggestion' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'friends' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'invis' => ['icon' => 'eye', 'color' => '#a78bfa'],
        'illusion' => ['icon' => 'eye', 'color' => '#a78bfa'],
        'detect' => ['icon' => 'eye', 'color' => '#4fd1c5'],
        'force' => ['icon' => 'diamond', 'color' => '#94a3b8'],
        'stone' => ['icon' => 'diamond', 'color' => '#a89a8c'],
        'wall' => ['icon' => 'shield', 'color' => '#5e9bd6'],
        'shield' => ['icon' => 'shield', 'color' => '#5e9bd6'],
        'armor' => ['icon' => 'shield', 'color' => '#5e9bd6'],
        'sanctuary' => ['icon' => 'shield', 'color' => '#5e9bd6'],
        'wind' => ['icon' => 'wind', 'color' => '#7fd6c2'],
        'gust' => ['icon' => 'wind', 'color' => '#7fd6c2'],
        'fly' => ['icon' => 'wind', 'color' => '#7fd6c2'],
        'bark' => ['icon' => 'leaf', 'color' => '#7bb661'],
        'plant' => ['icon' => 'leaf', 'color' => '#7bb661'],
    ];

    /**
     * @var array<string, array{icon: string, color: string}>
     */
    private const SCHOOL_FALLBACK = [
        'abjuration' => ['icon' => 'shield', 'color' => '#5e9bd6'],
        'conjuration' => ['icon' => 'sparkles', 'color' => '#9b6bd6'],
        'divination' => ['icon' => 'eye', 'color' => '#4fd1c5'],
        'enchantment' => ['icon' => 'sparkles', 'color' => '#e879b9'],
        'evocation' => ['icon' => 'flame', 'color' => '#f0793c'],
        'illusion' => ['icon' => 'eye', 'color' => '#a78bfa'],
        'necromancy' => ['icon' => 'skull', 'color' => '#8b5f9e'],
        'transmutation' => ['icon' => 'diamond', 'color' => '#d4a24c'],
    ];

    /**
     * @return array{icon: string, color: string}
     */
    public static function for(string $name, ?string $description, ?string $school): array
    {
        $haystack = strtolower($name.' '.($description ?? ''));

        foreach (self::KEYWORDS as $keyword => $theme) {
            if (str_contains($haystack, $keyword)) {
                return $theme;
            }
        }

        return self::SCHOOL_FALLBACK[strtolower((string) $school)] ?? ['icon' => 'sparkles', 'color' => '#d4a24c'];
    }
}
