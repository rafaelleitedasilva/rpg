<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpellController extends Controller
{
    public function index(Request $request): View
    {
        $query = Spell::query();
        $search = trim((string) $request->input('search', ''));

        if ($search !== '' && mb_strlen($search) >= 3) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', (int) $request->level);
        }

        if ($request->filled('class')) {
            $query->whereJsonContains('classes', $request->class);
        }

        if ($request->filled('race')) {
            $race = $request->string('race')->value();
            $query->where(function ($query) use ($race) {
                $query->whereJsonContains('races', $race)
                    ->orWhereNull('races')
                    ->orWhere('races', '[]');
            });
        }

        $spells = $query->orderBy('level')->orderBy('name')->paginate(12)->withQueryString();

        return view('spells.index', [
            'spells' => $spells,
            'search' => $search,
            'level' => $request->input('level'),
            'class' => $request->input('class'),
            'race' => $request->input('race'),
        ]);
    }
}
