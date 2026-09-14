<?php

namespace Tests\Feature;

use App\Models\Monster;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonsterCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function makeMonster(array $overrides = []): Monster
    {
        return Monster::create(array_merge([
            'index' => 'goblin',
            'name' => 'Goblin',
            'name_pt' => 'Goblin',
            'size' => 'Small',
            'type' => 'humanoid',
            'subtype' => 'goblinoid',
            'alignment' => 'neutral evil',
            'armor_class' => 15,
            'armor_class_note' => 'leather armor, shield',
            'hit_points' => 7,
            'hit_dice' => '2d6',
            'speed' => ['walk' => '30 ft.'],
            'strength' => 8,
            'dexterity' => 14,
            'constitution' => 10,
            'intelligence' => 10,
            'wisdom' => 8,
            'charisma' => 8,
            'proficiencies' => [['label' => 'Skill: Stealth', 'value' => 6]],
            'damage_vulnerabilities' => [],
            'damage_resistances' => [],
            'damage_immunities' => [],
            'condition_immunities' => [],
            'senses' => ['darkvision' => '60 ft.', 'passive_perception' => 9],
            'languages' => 'Comum, Goblin',
            'challenge_rating' => 0.25,
            'xp' => 50,
            'special_abilities' => [[
                'name' => 'Nimble Escape',
                'name_pt' => 'Fuga Ágil',
                'desc' => 'The goblin can take the Disengage or Hide action as a bonus action.',
                'desc_pt' => 'O goblin pode usar Desengajar ou Esconder-se como ação bônus.',
                'attack_bonus' => null,
                'damage' => [],
            ]],
            'actions' => [[
                'name' => 'Scimitar',
                'name_pt' => 'Cimitarra',
                'desc' => 'Melee Weapon Attack: +4 to hit.',
                'desc_pt' => 'Ataque corpo a corpo: +4 para acertar.',
                'attack_bonus' => 4,
                'damage' => [['type' => 'Slashing', 'dice' => '1d6+2']],
            ]],
            'legendary_actions' => [],
            'reactions' => [],
            'image_url' => null,
            'source' => 'dnd5eapi',
            'synced_at' => now(),
        ], $overrides));
    }

    public function test_authenticated_user_can_view_monster_catalog(): void
    {
        $user = User::factory()->create();
        $this->makeMonster();

        $response = $this->actingAs($user)->get('/monsters');

        $response->assertOk();
        $response->assertSee('Grimório de Monstros');
        $response->assertSee('Goblin');
    }

    public function test_monster_catalog_can_filter_by_type(): void
    {
        $user = User::factory()->create();
        $this->makeMonster();
        $this->makeMonster([
            'index' => 'brown-bear',
            'name' => 'Brown Bear',
            'name_pt' => 'Urso-pardo',
            'type' => 'beast',
        ]);

        $response = $this->actingAs($user)->get('/monsters?type=beast');

        $response->assertOk();
        $response->assertSee('Urso-pardo');
        $response->assertDontSee('Goblin');
    }

    public function test_authenticated_user_can_view_monster_grimoire_page(): void
    {
        $user = User::factory()->create();
        $this->makeMonster();

        $response = $this->actingAs($user)->get('/monsters/goblin');

        $response->assertOk();
        $response->assertSee('Goblin');
        $response->assertSee('Fuga Ágil');
        $response->assertSee('O goblin pode usar Desengajar ou Esconder-se como ação bônus.');
    }

    public function test_guest_is_redirected_from_monster_catalog(): void
    {
        $response = $this->get('/monsters');

        $response->assertRedirect(route('login'));
    }

    public function test_monster_imported_with_no_translate_is_shown_fully_in_english(): void
    {
        $user = User::factory()->create();
        $this->makeMonster([
            'name_pt' => null,
            'size' => 'Large',
            'type' => 'beast',
            'alignment' => 'unaligned',
            'special_abilities' => [[
                'name' => 'Nimble Escape',
                'name_pt' => null,
                'desc' => 'The goblin can take the Disengage or Hide action as a bonus action.',
                'desc_pt' => null,
                'attack_bonus' => null,
                'damage' => [],
            ]],
            'translated' => false,
        ]);

        $response = $this->actingAs($user)->get('/monsters/goblin');

        $response->assertOk();
        // Ficha 100% em inglês: nem os termos de jogo (dicionário) nem o
        // texto livre (traduzido pela API) devem aparecer em português.
        $response->assertSee('Large');
        $response->assertSee('Beast');
        $response->assertSee('The goblin can take the Disengage or Hide action as a bonus action.');
        $response->assertDontSee('Grande');
        $response->assertDontSee('Fera');
        $response->assertDontSee('Desengajar');
    }
}
