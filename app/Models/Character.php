<?php

namespace App\Models;

use App\Modules\Rpg\Services\Dnd5eCharacterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'player_name',
        'master_name',
        'campaign_name',
        'race',
        'class',
        'secondary_class',
        'secondary_class_level',
        'level',
        'background',
        'alignment',
        'deity',
        'size',
        'age',
        'height',
        'weight',
        'eyes',
        'skin',
        'hair',
        'portrait_url',
        'experience',
        'strength',
        'dexterity',
        'constitution',
        'intelligence',
        'wisdom',
        'charisma',
        'proficiency_bonus',
        'initiative',
        'armor_class',
        'speed',
        'current_hp',
        'max_hp',
        'temp_hp',
        'hit_dice',
        'death_save_successes',
        'death_save_failures',
        'inspiration',
        'passive_perception',
        'saving_throw_proficiencies',
        'skill_proficiencies',
        'skill_expertise',
        'armor_proficiencies',
        'weapon_proficiencies',
        'tool_proficiencies',
        'languages',
        'equipment',
        'coins_cp',
        'coins_sp',
        'coins_ep',
        'coins_gp',
        'coins_pp',
        'treasure',
        'attacks',
        'spellcasting_ability',
        'spell_save_dc',
        'spell_attack_bonus',
        'cantrips',
        'spells',
        'personality_traits',
        'ideals',
        'bonds',
        'flaws',
        'features',
        'proficiencies',
        'allies_organizations',
        'backstory',
        'notes',
    ];

    protected $casts = [
        'spells' => 'array',
        'cantrips' => 'array',
        'personality_traits' => 'array',
        'ideals' => 'array',
        'bonds' => 'array',
        'flaws' => 'array',
        'features' => 'array',
        'proficiencies' => 'array',
        'saving_throw_proficiencies' => 'array',
        'skill_proficiencies' => 'array',
        'skill_expertise' => 'array',
        'armor_proficiencies' => 'array',
        'weapon_proficiencies' => 'array',
        'tool_proficiencies' => 'array',
        'languages' => 'array',
        'equipment' => 'array',
        'attacks' => 'array',
        'experience' => 'integer',
        'level' => 'integer',
        'secondary_class_level' => 'integer',
        'strength' => 'integer',
        'dexterity' => 'integer',
        'constitution' => 'integer',
        'intelligence' => 'integer',
        'wisdom' => 'integer',
        'charisma' => 'integer',
        'proficiency_bonus' => 'integer',
        'initiative' => 'integer',
        'armor_class' => 'integer',
        'speed' => 'integer',
        'current_hp' => 'integer',
        'max_hp' => 'integer',
        'temp_hp' => 'integer',
        'hit_dice' => 'integer',
        'death_save_successes' => 'integer',
        'death_save_failures' => 'integer',
        'inspiration' => 'boolean',
        'passive_perception' => 'integer',
        'coins_cp' => 'integer',
        'coins_sp' => 'integer',
        'coins_ep' => 'integer',
        'coins_gp' => 'integer',
        'coins_pp' => 'integer',
        'spell_save_dc' => 'integer',
        'spell_attack_bonus' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function spellSlots(): array
    {
        return app(Dnd5eCharacterService::class)->spellSlots((int) $this->level, (string) $this->class);
    }

    public function hasSpellcasting(): bool
    {
        return app(Dnd5eCharacterService::class)->supportsSpellcasting((string) $this->class);
    }

    /**
     * Progress from the current level's XP floor toward the next level.
     *
     * @return array{current: int, needed: int, percent: int, maxed: bool}
     */
    public function xpProgress(): array
    {
        return app(Dnd5eCharacterService::class)->xpProgress((int) $this->level, (int) ($this->experience ?? 0));
    }

    /**
     * Modifier for one of the six abilities (e.g. "strength").
     */
    public function abilityModifier(string $ability): int
    {
        return app(Dnd5eCharacterService::class)->modifier((int) ($this->$ability ?? 10));
    }

    public function isProficientInSavingThrow(string $ability): bool
    {
        return in_array($ability, $this->saving_throw_proficiencies ?? [], true);
    }

    public function savingThrowBonus(string $ability): int
    {
        return app(Dnd5eCharacterService::class)->savingThrowBonus(
            (int) ($this->$ability ?? 10),
            (int) $this->proficiency_bonus,
            $this->isProficientInSavingThrow($ability)
        );
    }

    public function isProficientInSkill(string $skill): bool
    {
        return in_array($skill, $this->skill_proficiencies ?? [], true);
    }

    public function hasExpertiseInSkill(string $skill): bool
    {
        return in_array($skill, $this->skill_expertise ?? [], true);
    }

    public function skillBonus(string $skill): int
    {
        $ability = Dnd5eCharacterService::SKILLS[$skill]['ability'] ?? 'strength';

        return app(Dnd5eCharacterService::class)->skillBonus(
            (int) ($this->$ability ?? 10),
            (int) $this->proficiency_bonus,
            $this->isProficientInSkill($skill),
            $this->hasExpertiseInSkill($skill)
        );
    }

    /**
     * All eighteen skills with their computed bonus, ready for display.
     *
     * @return array<string, array{label: string, ability: string, proficient: bool, expertise: bool, bonus: int}>
     */
    public function skillsBreakdown(): array
    {
        $breakdown = [];

        foreach (Dnd5eCharacterService::SKILLS as $key => $skill) {
            $breakdown[$key] = [
                'label' => $skill['label'],
                'ability' => $skill['ability'],
                'proficient' => $this->isProficientInSkill($key),
                'expertise' => $this->hasExpertiseInSkill($key),
                'bonus' => $this->skillBonus($key),
            ];
        }

        return $breakdown;
    }
}
