<?php

namespace Tests\Feature;

use App\Models\Spell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RpgAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_visit_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('AGENTE');
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_authenticated_user_can_view_spell_catalog(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/spells');

        $response->assertOk();
        $response->assertSee('Catálogo de Magias');
    }

    public function test_spell_catalog_can_filter_by_class_and_race(): void
    {
        $user = User::factory()->create();

        Spell::create([
            'name' => 'Detect Magic',
            'level' => 1,
            'school' => 'Adivinhação',
            'casting_time' => '1 ação',
            'range' => 'Pessoal',
            'components' => 'V, S',
            'duration' => 'Concentração, até 10 minutos',
            'concentration' => true,
            'ritual' => true,
            'description' => 'Revela magia nas proximidades.',
            'classes' => ['Wizard', 'Cleric'],
            'races' => ['Elf'],
        ]);

        Spell::create([
            'name' => 'Magic Missile',
            'level' => 1,
            'school' => 'Evocação',
            'casting_time' => '1 ação',
            'range' => '120 pés',
            'components' => 'V, S',
            'duration' => 'Instantânea',
            'concentration' => false,
            'ritual' => false,
            'description' => 'Causa dano por três dardos.',
            'classes' => ['Wizard'],
            'races' => ['Human'],
        ]);

        $response = $this->actingAs($user)->get('/spells?class=Wizard&race=Elf');

        $response->assertOk();
        $response->assertSee('Detect Magic');
        $response->assertDontSee('Magic Missile');
    }

    public function test_user_can_edit_character_sheet(): void
    {
        $user = User::factory()->create();
        $character = $user->characters()->create([
            'name' => 'Arath',
            'race' => 'Humano',
            'class' => 'Mago',
            'level' => 1,
            'background' => 'Aventureiro',
            'alignment' => 'Neutro',
            'strength' => 10,
            'dexterity' => 12,
            'constitution' => 14,
            'intelligence' => 16,
            'wisdom' => 13,
            'charisma' => 11,
            'armor_class' => 12,
            'speed' => 30,
            'current_hp' => 8,
            'max_hp' => 8,
            'temp_hp' => 0,
            'hit_dice' => 8,
            'inspiration' => false,
            'notes' => 'Antiga ficha',
            'proficiency_bonus' => 2,
            'initiative' => 1,
            'passive_perception' => 11,
            'spell_save_dc' => 13,
            'spell_attack_bonus' => 3,
            'experience' => 0,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('characters.update', $character), [
                'name' => 'Arath Voss',
                'race' => 'Humano',
                'class' => 'Bruxo',
                'level' => 2,
                'background' => 'Aventureiro',
                'alignment' => 'Leal',
                'strength' => 10,
                'dexterity' => 12,
                'constitution' => 14,
                'intelligence' => 16,
                'wisdom' => 13,
                'charisma' => 12,
                'armor_class' => 13,
                'speed' => 30,
                'current_hp' => 12,
                'max_hp' => 12,
                'temp_hp' => 0,
                'hit_dice' => 8,
                'inspiration' => true,
                'notes' => 'Nova versão da ficha',
            ]);

        $response->assertRedirect(route('characters.show', $character));
        $this->assertDatabaseHas('characters', [
            'id' => $character->id,
            'name' => 'Arath Voss',
            'class' => 'Bruxo',
            'level' => 2,
            'alignment' => 'Leal',
            'charisma' => 12,
            'inspiration' => true,
        ]);
    }

    public function test_character_can_store_multiclass_details_and_known_spells(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('characters.store'), [
                'name' => 'Nyra',
                'race' => 'Elfa',
                'class' => 'Mago',
                'secondary_class' => 'Bardo',
                'secondary_class_level' => 2,
                'level' => 8,
                'background' => 'Acolito',
                'alignment' => 'Neutro Bom',
                'strength' => 8,
                'dexterity' => 16,
                'constitution' => 14,
                'intelligence' => 18,
                'wisdom' => 12,
                'charisma' => 15,
                'armor_class' => 14,
                'speed' => 30,
                'current_hp' => 32,
                'max_hp' => 32,
                'temp_hp' => 0,
                'hit_dice' => 8,
                'inspiration' => false,
                'personality_traits' => 'Curiosa e falante.',
                'ideals' => 'Proteger seus aliados.',
                'bonds' => 'Ama a biblioteca de sua cidade.',
                'flaws' => 'Sofre com orgulho.',
                'features' => 'Magia Arcana, Conhecimentos Arcanos',
                'proficiencies' => 'Percepção, Furtividade',
                'spells' => 'Míssil Mágico, Escudo Arcano, Identificação',
                'notes' => 'Mestre dos segredos antigos',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('characters', [
            'name' => 'Nyra',
            'secondary_class' => 'Bardo',
            'secondary_class_level' => 2,
            'personality_traits' => json_encode(['Curiosa e falante.']),
            'features' => json_encode(['Magia Arcana', 'Conhecimentos Arcanos']),
        ]);

        $character = $user->characters()->first();
        $this->assertSame(['Míssil Mágico', 'Escudo Arcano', 'Identificação'], $character->spells);
    }

    public function test_character_level_has_spell_slots_calculated_for_magic_users(): void
    {
        $service = app(\App\Modules\Rpg\Services\Dnd5eCharacterService::class);

        $this->assertSame([
            1 => 4,
            2 => 3,
            3 => 2,
        ], $service->spellSlots(5));
    }

    public function test_character_sheet_stores_saving_throws_skills_attacks_and_currency(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('characters.store'), [
            'name' => 'Thalindra',
            'race' => 'Elfa',
            'class' => 'Clérigo',
            'level' => 5,
            'background' => 'Acólito',
            'alignment' => 'Leal e Bom',
            'strength' => 12,
            'dexterity' => 14,
            'constitution' => 13,
            'intelligence' => 10,
            'wisdom' => 17,
            'charisma' => 11,
            'saving_throw_proficiencies' => ['wisdom', 'charisma'],
            'skill_proficiencies' => ['perception', 'insight'],
            'skill_expertise' => ['perception'],
            'armor_class' => 16,
            'current_hp' => 0,
            'max_hp' => 38,
            'death_save_successes' => 1,
            'death_save_failures' => 2,
            'attacks' => "Maça | +5 | 1d6+2 contundente\nBesta leve | +4 | 1d8 perfurante",
            'languages' => "Comum\nCeleste",
            'coins_gp' => 50,
            'spellcasting_ability' => 'wisdom',
        ]);

        $response->assertRedirect();

        $character = $user->characters()->first();

        $this->assertSame(['wisdom', 'charisma'], $character->saving_throw_proficiencies);
        $this->assertSame(['perception', 'insight'], $character->skill_proficiencies);
        $this->assertSame(['perception'], $character->skill_expertise);
        $this->assertSame(1, $character->death_save_successes);
        $this->assertSame(2, $character->death_save_failures);
        $this->assertSame(50, $character->coins_gp);
        $this->assertSame(['Comum', 'Celeste'], $character->languages);
        $this->assertCount(2, $character->attacks);
        $this->assertSame('Maça', $character->attacks[0]['name']);
        $this->assertSame('+5', $character->attacks[0]['bonus']);

        // Wisdom 17 -> modifier +3, proficiency bonus +3 at level 5, expertise doubles it.
        $this->assertSame(9, $character->skillBonus('perception'));
        $this->assertSame(19, $character->passive_perception);
        $this->assertSame('wisdom', $character->spellcasting_ability);
        $this->assertSame(14, $character->spell_save_dc);

        $this->actingAs($user)->get(route('characters.show', $character))->assertOk()->assertSee('Maça');
        $this->actingAs($user)->get(route('characters.edit', $character))->assertOk();
    }

    public function test_spell_sync_command_imports_spells_from_external_api(): void
    {
        Http::fake([
            'https://www.dnd5eapi.co/api/spells' => Http::response([
                'results' => [
                    ['index' => 'detect-magic', 'name' => 'Detect Magic', 'url' => '/api/spells/detect-magic'],
                ],
            ], 200),
            'https://www.dnd5eapi.co/api/spells/detect-magic' => Http::response([
                'name' => 'Detect Magic',
                'level' => 1,
                'school' => ['name' => 'Divination'],
                'casting_time' => '1 action',
                'range' => 'Self',
                'components' => ['V', 'S'],
                'duration' => 'Concentration, up to 10 minutes',
                'concentration' => true,
                'ritual' => false,
                'desc' => ['You sense the presence of magic around you.'],
                'classes' => [['name' => 'Wizard'], ['name' => 'Cleric']],
                'races' => [['name' => 'Elf']],
            ], 200),
        ]);

        $this->artisan('spells:sync')->assertSuccessful();

        $this->assertDatabaseHas('spells', [
            'name' => 'Detect Magic',
            'school' => 'Divination',
            'level' => 1,
        ]);

        $this->assertSame(['Wizard', 'Cleric'], Spell::first()->classes);
        $this->assertSame(['Elf'], Spell::first()->races);
    }
}
