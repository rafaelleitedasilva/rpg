<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CharacterImageTest extends TestCase
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

    public function test_user_can_upload_multiple_images_and_first_becomes_cover(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $character = $this->makeCharacter($user);

        $response = $this->actingAs($user)->post(route('characters.images.store', $character), [
            'images' => [
                UploadedFile::fake()->create('foto1.jpg', 10, 'image/jpeg'),
                UploadedFile::fake()->create('foto2.jpg', 10, 'image/jpeg'),
            ],
        ]);

        $response->assertRedirect();
        $character->refresh();

        $this->assertCount(2, $character->images);
        $this->assertSame(1, $character->images->where('is_cover', true)->count());
        Storage::disk('public')->assertExists($character->images->first()->path);
    }

    public function test_user_can_change_cover_and_delete_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $character = $this->makeCharacter($user);

        $this->actingAs($user)->post(route('characters.images.store', $character), [
            'images' => [UploadedFile::fake()->create('a.jpg', 10, 'image/jpeg'), UploadedFile::fake()->create('b.jpg', 10, 'image/jpeg')],
        ]);

        $character->refresh();
        $first = $character->images->first();
        $second = $character->images->last();

        $this->actingAs($user)->patch(route('characters.images.cover', [$character, $second]));
        $this->assertTrue($second->fresh()->is_cover);
        $this->assertFalse($first->fresh()->is_cover);

        $path = $second->path;
        $this->actingAs($user)->delete(route('characters.images.destroy', [$character, $second]));

        Storage::disk('public')->assertMissing($path);
        $this->assertSame(1, $character->images()->count());
        // Deleting the cover promotes the remaining image automatically.
        $this->assertTrue($first->fresh()->is_cover);
    }

    public function test_user_cannot_manage_images_of_another_users_character(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $character = $this->makeCharacter($owner);

        $this->actingAs($intruder)->post(route('characters.images.store', $character), [
            'images' => [UploadedFile::fake()->create('a.jpg', 10, 'image/jpeg')],
        ])->assertForbidden();
    }

    public function test_deleting_a_character_removes_its_stored_images(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $character = $this->makeCharacter($user);

        $this->actingAs($user)->post(route('characters.images.store', $character), [
            'images' => [UploadedFile::fake()->create('a.jpg', 10, 'image/jpeg')],
        ]);

        $character->refresh();
        $path = $character->images->first()->path;

        $this->actingAs($user)->delete(route('characters.destroy', $character));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('character_images', ['path' => $path]);
    }
}
