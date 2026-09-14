<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use App\Support\SpellTranslator;
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

        if ($request->filled('school')) {
            $query->where('school', $request->string('school')->value());
        }

        if ($request->filled('class')) {
            $query->whereJsonContains('classes', $request->class);
        }

        if ($request->filled('race')) {
            $race = $request->string('race')->value();
            $query->where(function ($query) use ($race) {
                $query->whereJsonContains('races', $race)
                    ->orWhereNull('races')
                    // An empty `races` array means "available to every race" —
                    // comparing a json/jsonb column with `= '[]'` has no valid
                    // operator on Postgres, so check its length instead.
                    ->orWhereJsonLength('races', 0);
            });
        }

        match ($request->input('sort')) {
            'name' => $query->orderBy('name'),
            'level_desc' => $query->orderByDesc('level')->orderBy('name'),
            default => $query->orderBy('level')->orderBy('name'),
        };

        $spells = $query->paginate(12)->withQueryString();

        return view('spells.index', [
            'spells' => $spells,
            'search' => $search,
            'level' => $request->input('level'),
            'school' => $request->input('school'),
            'class' => $request->input('class'),
            'race' => $request->input('race'),
            'sort' => $request->input('sort', 'level'),
            'schools' => SpellTranslator::schools(),
        ]);
    }
}
