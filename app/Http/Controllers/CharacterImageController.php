<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterImageController extends Controller
{
    private const MAX_IMAGES_PER_CHARACTER = 12;

    public function store(Request $request, Character $character): RedirectResponse
    {
        abort_unless($character->user_id === auth()->id(), 403);

        $remainingSlots = max(0, self::MAX_IMAGES_PER_CHARACTER - $character->images()->count());

        if ($remainingSlots === 0) {
            return back()->withErrors(['images' => 'Este personagem já atingiu o limite de '.self::MAX_IMAGES_PER_CHARACTER.' imagens.']);
        }

        $validated = $request->validate([
            'images' => ['required', 'array', 'max:'.$remainingSlots],
            'images.*' => ['image', 'max:5120'],
        ], [
            'images.max' => 'Você pode enviar no máximo mais '.$remainingSlots.' imagem(ns) para este personagem.',
        ]);

        $disk = config('filesystems.default');
        $hasCover = $character->images()->where('is_cover', true)->exists();
        $nextPosition = (int) $character->images()->max('position');

        foreach ($validated['images'] as $file) {
            $path = $file->store('characters/'.$character->id, $disk);
            $nextPosition++;

            $character->images()->create([
                'path' => $path,
                'is_cover' => ! $hasCover,
                'position' => $nextPosition,
            ]);

            // Only the very first image ever uploaded becomes the automatic cover.
            $hasCover = true;
        }

        return back()->with('status', 'Imagens enviadas com sucesso.');
    }

    public function setCover(Character $character, CharacterImage $image): RedirectResponse
    {
        abort_unless($character->user_id === auth()->id(), 403);
        abort_unless($image->character_id === $character->id, 404);

        $character->images()->update(['is_cover' => false]);
        $image->update(['is_cover' => true]);

        return back()->with('status', 'Capa atualizada.');
    }

    public function destroy(Character $character, CharacterImage $image): RedirectResponse
    {
        abort_unless($character->user_id === auth()->id(), 403);
        abort_unless($image->character_id === $character->id, 404);

        Storage::disk(config('filesystems.default'))->delete($image->path);

        $wasCover = $image->is_cover;
        $image->delete();

        if ($wasCover) {
            $character->images()->orderBy('position')->first()?->update(['is_cover' => true]);
        }

        return back()->with('status', 'Imagem removida.');
    }
}
