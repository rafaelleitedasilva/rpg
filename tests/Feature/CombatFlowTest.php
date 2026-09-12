<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CombatFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_can_create_a_combat_scene_for_a_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'A Sombra do Círculo',
            'setting' => 'Faerûn',
            'description' => 'Uma campanha de exploração e mistério.',
            'status' => 'Em preparação',
            'max_players' => 5,
        ]);

        $response = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes", [
            'name' => 'Batalha na clareira',
            'initiative_order' => 'Goblin, Herói, Orc',
            'round' => 1,
            'turn' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('combat_scenes', [
            'campaign_id' => $campaign->id,
            'name' => 'Batalha na clareira',
        ]);
    }

    public function test_master_can_create_monster_templates_and_tokens_for_a_scene(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'Campanha do Dragão',
            'setting' => 'Eberron',
            'description' => 'Uma campanha de aventura.',
            'status' => 'Em andamento',
            'max_players' => 4,
        ]);

        $map = $campaign->maps()->create([
            'name' => 'Sala de tesouro',
            'terrain' => 'Pedra',
            'width' => 8,
            'height' => 6,
            'grid_size' => 5,
        ]);

        $scene = $campaign->combatScenes()->create([
            'map_id' => $map->id,
            'name' => 'Encontro final',
            'status' => 'ativo',
            'round' => 1,
            'turn' => 1,
            'initiative_order' => 'Herói, Goblin, Dragão',
        ]);

        $response = $this->actingAs($user)->post("/campaigns/{$campaign->id}/monster-templates", [
            'name' => 'Goblin',
            'description' => 'Ladrão pequeno e astuto.',
            'armor_class' => 13,
            'max_hp' => 7,
            'initiative' => 12,
            'speed' => 30,
            'challenge_rating' => 0.25,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('monster_templates', [
            'campaign_id' => $campaign->id,
            'name' => 'Goblin',
        ]);

        $monster = $campaign->monsterTemplates()->first();

        $tokenResponse = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes/{$scene->id}/tokens", [
            'monster_template_id' => $monster->id,
            'name' => 'Goblin #1',
            'type' => 'monster',
            'x' => 3,
            'y' => 4,
            'initiative' => 12,
            'hp' => 7,
            'max_hp' => 7,
            'color' => '#ef4444',
        ]);

        $tokenResponse->assertRedirect();
        $this->assertDatabaseHas('tokens', [
            'combat_scene_id' => $scene->id,
            'name' => 'Goblin #1',
        ]);
    }

    public function test_master_can_move_a_token_in_the_combat_scene(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'Campanha de teste',
            'setting' => 'Forgotten Realms',
            'description' => 'Mapa em batalha.',
            'status' => 'Em andamento',
            'max_players' => 4,
        ]);

        $map = $campaign->maps()->create([
            'name' => 'Sala da torre',
            'terrain' => 'Rocha',
            'width' => 8,
            'height' => 6,
            'grid_size' => 5,
        ]);

        $scene = $campaign->combatScenes()->create([
            'map_id' => $map->id,
            'name' => 'Batalha do corredor',
            'status' => 'ativo',
            'round' => 1,
            'turn' => 1,
            'initiative_order' => 'Herói, Inimigo',
        ]);

        $token = $scene->tokens()->create([
            'campaign_id' => $campaign->id,
            'map_id' => $map->id,
            'name' => 'Goblin #2',
            'type' => 'monster',
            'x' => 1,
            'y' => 1,
            'initiative' => 14,
            'hp' => 6,
            'max_hp' => 6,
            'color' => '#f59e0b',
        ]);

        $response = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes/{$scene->id}/tokens/{$token->id}/move", [
            'x' => 4,
            'y' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tokens', [
            'id' => $token->id,
            'x' => 4,
            'y' => 5,
        ]);
    }

    public function test_master_can_manage_damage_healing_and_turn_progression(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'Campanha de combate',
            'setting' => 'Planescape',
            'description' => 'Ação táctica.',
            'status' => 'Em andamento',
            'max_players' => 5,
        ]);

        $map = $campaign->maps()->create([
            'name' => 'Sala do trono',
            'terrain' => 'Pedra',
            'width' => 10,
            'height' => 8,
            'grid_size' => 5,
        ]);

        $scene = $campaign->combatScenes()->create([
            'map_id' => $map->id,
            'name' => 'Confronto final',
            'status' => 'ativo',
            'round' => 1,
            'turn' => 1,
            'initiative_order' => 'Paladino, Dragão, Goblin',
        ]);

        $token = $scene->tokens()->create([
            'campaign_id' => $campaign->id,
            'map_id' => $map->id,
            'name' => 'Paladino',
            'type' => 'pc',
            'x' => 2,
            'y' => 2,
            'initiative' => 16,
            'hp' => 12,
            'max_hp' => 12,
            'color' => '#60a5fa',
        ]);

        $damageResponse = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes/{$scene->id}/tokens/{$token->id}/damage", [
            'amount' => 4,
        ]);

        $damageResponse->assertRedirect();
        $this->assertDatabaseHas('tokens', [
            'id' => $token->id,
            'hp' => 8,
        ]);

        $healResponse = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes/{$scene->id}/tokens/{$token->id}/heal", [
            'amount' => 3,
        ]);

        $healResponse->assertRedirect();
        $this->assertDatabaseHas('tokens', [
            'id' => $token->id,
            'hp' => 11,
        ]);

        $advanceResponse = $this->actingAs($user)->post("/campaigns/{$campaign->id}/combat-scenes/{$scene->id}/advance");

        $advanceResponse->assertRedirect();
        $this->assertDatabaseHas('combat_scenes', [
            'id' => $scene->id,
            'turn' => 2,
        ]);
    }
}
