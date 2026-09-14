<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CharacterPdfExportTest extends TestCase
{
    use RefreshDatabase;

    private function makeCharacter(User $user)
    {
        return $user->characters()->create([
            'name' => 'Riruky',
            'race' => 'Humano',
            'class' => 'Mago',
            'level' => 4,
            'background' => 'Aventureiro',
            'alignment' => 'Neutro',
            'strength' => 10, 'dexterity' => 12, 'constitution' => 14,
            'intelligence' => 16, 'wisdom' => 13, 'charisma' => 11,
        ]);
    }

    public function test_user_can_export_their_character_sheet_as_pdf(): void
    {
        $user = User::factory()->create();
        $character = $this->makeCharacter($user);

        $response = $this->actingAs($user)->get(route('characters.pdf', $character));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_cannot_export_another_users_character(): void
    {
        $owner = User::factory()->create();
        $character = $this->makeCharacter($owner);

        $intruder = User::factory()->create();

        $this->actingAs($intruder)->get(route('characters.pdf', $character))->assertForbidden();
    }
}
