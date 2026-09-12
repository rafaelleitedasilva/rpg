<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_can_create_a_map_for_a_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'A Sombra do Círculo',
            'setting' => 'Faerûn',
            'description' => 'Uma campanha de exploração e mistério.',
            'status' => 'Em preparação',
            'max_players' => 5,
        ]);

        $response = $this->actingAs($user)->post("/campaigns/{$campaign->id}/maps", [
            'name' => 'Floresta Sombria',
            'width' => 10,
            'height' => 8,
            'grid_size' => 5,
            'terrain' => 'Floresta',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('maps', [
            'campaign_id' => $campaign->id,
            'name' => 'Floresta Sombria',
        ]);
    }
}
