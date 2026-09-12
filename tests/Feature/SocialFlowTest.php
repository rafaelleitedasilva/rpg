<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_a_friend_request(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $response = $this->actingAs($sender)->post('/friends/requests', [
            'user_id' => $recipient->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('friend_requests', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);
    }

    public function test_master_can_invite_a_friend_to_campaign(): void
    {
        $master = User::factory()->create();
        $friend = User::factory()->create();
        $campaign = $master->campaigns()->create([
            'name' => 'A Sombra do Círculo',
            'setting' => 'Faerûn',
            'description' => 'Uma campanha de exploração e mistério.',
            'status' => 'Em preparação',
            'max_players' => 5,
        ]);

        $response = $this->actingAs($master)->post("/campaigns/{$campaign->id}/invites", [
            'user_id' => $friend->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaign_invites', [
            'campaign_id' => $campaign->id,
            'user_id' => $friend->id,
            'status' => 'pending',
        ]);
    }

    public function test_user_can_accept_a_campaign_invite(): void
    {
        $master = User::factory()->create();
        $friend = User::factory()->create();
        $campaign = $master->campaigns()->create([
            'name' => 'Trilha da Lua Negra',
            'setting' => 'Eberron',
            'description' => 'Uma campanha de aventura e mistério.',
            'status' => 'Em preparação',
            'max_players' => 4,
        ]);

        $invite = $campaign->invites()->create([
            'user_id' => $friend->id,
            'inviter_id' => $master->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($friend)->post("/campaigns/invites/{$invite->id}/accept");

        $response->assertRedirect();
        $this->assertDatabaseHas('campaign_invites', [
            'id' => $invite->id,
            'status' => 'accepted',
        ]);
    }
}
