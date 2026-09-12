<?php

namespace Tests\Unit;

use App\Modules\Rpg\Services\Dnd5eCharacterService;
use PHPUnit\Framework\TestCase;

class Dnd5eCharacterServiceTest extends TestCase
{
    private Dnd5eCharacterService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new Dnd5eCharacterService();
    }

    public function test_saving_throw_bonus_only_adds_proficiency_when_proficient(): void
    {
        // Wisdom 16 -> modifier +3, proficiency bonus +3 at level 9-12.
        $this->assertSame(3, $this->service->savingThrowBonus(16, 3, false));
        $this->assertSame(6, $this->service->savingThrowBonus(16, 3, true));
    }

    public function test_skill_bonus_adds_proficiency_and_doubles_it_with_expertise(): void
    {
        // Dexterity 14 -> modifier +2, proficiency bonus +2.
        $this->assertSame(2, $this->service->skillBonus(14, 2, false));
        $this->assertSame(4, $this->service->skillBonus(14, 2, true));
        $this->assertSame(6, $this->service->skillBonus(14, 2, true, true));
        // Expertise without proficiency does not double the bonus.
        $this->assertSame(2, $this->service->skillBonus(14, 2, false, true));
    }

    public function test_passive_perception_adds_ten_to_the_perception_skill_bonus(): void
    {
        $this->assertSame(15, $this->service->passivePerception(5));
    }

    public function test_spell_save_dc_and_attack_bonus_use_the_chosen_ability_score(): void
    {
        // Intelligence 18 -> modifier +4, proficiency bonus +3.
        $this->assertSame(15, $this->service->spellSaveDc(18, 3));
        $this->assertSame(7, $this->service->spellAttackBonus(18, 3));
    }

    public function test_default_spellcasting_ability_is_inferred_from_class(): void
    {
        $this->assertSame('intelligence', $this->service->defaultSpellcastingAbility('Mago'));
        $this->assertSame('wisdom', $this->service->defaultSpellcastingAbility('Clérigo'));
        $this->assertSame('charisma', $this->service->defaultSpellcastingAbility('Bruxo'));
        $this->assertNull($this->service->defaultSpellcastingAbility('Guerreiro'));
        $this->assertNull($this->service->defaultSpellcastingAbility(null));
    }

    public function test_supports_spellcasting_recognizes_portuguese_and_english_class_names(): void
    {
        $this->assertTrue($this->service->supportsSpellcasting('Bruxo'));
        $this->assertTrue($this->service->supportsSpellcasting('Warlock'));
        $this->assertFalse($this->service->supportsSpellcasting('Guerreiro'));
        $this->assertFalse($this->service->supportsSpellcasting(null));
    }

    public function test_xp_progress_measures_advance_within_the_current_level(): void
    {
        // Level 4 spans 2700-6500 XP; sitting at 4600 is halfway through that span.
        $progress = $this->service->xpProgress(4, 4600);

        $this->assertSame(1900, $progress['current']);
        $this->assertSame(3800, $progress['needed']);
        $this->assertSame(50, $progress['percent']);
        $this->assertFalse($progress['maxed']);
    }

    public function test_xp_progress_is_maxed_at_level_twenty(): void
    {
        $progress = $this->service->xpProgress(20, 400000);

        $this->assertSame(100, $progress['percent']);
        $this->assertTrue($progress['maxed']);
    }
}
