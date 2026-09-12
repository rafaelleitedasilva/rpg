<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_campaign(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/campaigns', [
            'name' => 'A Sombra do Círculo',
            'setting' => 'Faerûn',
            'description' => 'Uma campanha de exploração e mistério.',
            'max_players' => 5,
            'status' => 'Em preparação',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaigns', [
            'name' => 'A Sombra do Círculo',
            'master_id' => $user->id,
        ]);
    }

    public function test_dashboard_shows_master_campaigns(): void
    {
        $user = User::factory()->create();
        $campaign = $user->campaigns()->create([
            'name' => 'A Sombra do Círculo',
            'setting' => 'Faerûn',
            'description' => 'Uma campanha de exploração e mistério.',
            'status' => 'Em preparação',
            'max_players' => 5,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Minhas campanhas');
        $response->assertSee($campaign->name);
    }
}
