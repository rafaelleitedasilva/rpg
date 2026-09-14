<?php

namespace App\Models;

use App\Support\SpellTranslator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'school',
        'casting_time',
        'range',
        'components',
        'duration',
        'concentration',
        'ritual',
        'description',
        'description_pt',
        'classes',
        'races',
        'translated',
    ];

    protected $casts = [
        'classes' => 'array',
        'races' => 'array',
        'concentration' => 'boolean',
        'ritual' => 'boolean',
        'level' => 'integer',
        'translated' => 'boolean',
    ];

    /**
     * Quando a magia foi importada com `--no-translate`, nada nela deve
     * passar pelos dicionários de tradução: nome, escola, alcance etc.
     * continuam exatamente como a API devolveu, em inglês.
     */
    public function nameLabel(): string
    {
        return $this->translated ? SpellTranslator::name($this->name) : $this->name;
    }

    public function schoolLabel(): string
    {
        return $this->translated ? SpellTranslator::school($this->school) : ($this->school ?: '—');
    }

    public function durationLabel(): string
    {
        return $this->translated ? SpellTranslator::duration($this->duration) : ($this->duration ?: '—');
    }

    public function rangeLabel(): string
    {
        return $this->translated ? SpellTranslator::range($this->range) : ($this->range ?: '—');
    }

    public function componentsLabel(): string
    {
        return $this->translated ? SpellTranslator::components($this->components) : ($this->components ?: '—');
    }

    public function castingTimeLabel(): string
    {
        return $this->translated ? SpellTranslator::castingTime($this->casting_time) : ($this->casting_time ?: '—');
    }

    public function classListLabel(): string
    {
        $classes = $this->classes ?? [];

        if ($this->translated) {
            return SpellTranslator::classList($classes);
        }

        return $classes === [] ? '—' : implode(', ', $classes);
    }

    public function raceListLabel(): string
    {
        $races = $this->races ?? [];

        if ($this->translated) {
            return SpellTranslator::raceList($races);
        }

        return $races === [] ? '—' : implode(', ', $races);
    }

    public function descriptionLabel(): string
    {
        if (! $this->translated) {
            return $this->description ?: '—';
        }

        return $this->description_pt ?: SpellTranslator::description($this->description);
    }
}
