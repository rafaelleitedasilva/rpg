<?php

namespace Tests\Feature;

use App\Models\Spell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpellRaceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtering_spells_by_race_does_not_error_and_includes_universal_spells(): void
    {
        $user = User::factory()->create();

        Spell::create([
            'name' => 'Detect Magic', 'level' => 1, 'school' => 'Divination',
            'casting_time' => '1 action', 'range' => 'Self', 'components' => 'V, S',
            'duration' => 'Concentration', 'concentration' => true, 'ritual' => true,
            'description' => 'x', 'classes' => ['Wizard'], 'races' => ['Elf'],
        ]);

        Spell::create([
            'name' => 'Fireball', 'level' => 3, 'school' => 'Evocation',
            'casting_time' => '1 action', 'range' => '150 feet', 'components' => 'V, S, M',
            'duration' => 'Instantaneous', 'concentration' => false, 'ritual' => false,
            'description' => 'x', 'classes' => ['Wizard'], 'races' => ['Human'],
        ]);

        Spell::create([
            'name' => 'Universal Cantrip', 'level' => 0, 'school' => 'Transmutation',
            'casting_time' => '1 action', 'range' => 'Touch', 'components' => 'V',
            'duration' => 'Instantaneous', 'concentration' => false, 'ritual' => false,
            'description' => 'x', 'classes' => ['Wizard'], 'races' => [],
        ]);

        $response = $this->actingAs($user)->get('/spells?race=Human');

        $response->assertOk();
        $response->assertSee('Fireball');
        $response->assertSee('Universal Cantrip');
        $response->assertDontSee('Detect Magic');
    }
}
