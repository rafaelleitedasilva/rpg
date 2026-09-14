<?php

namespace App\Http\Controllers;

use App\Models\Monster;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonsterController extends Controller
{
    public function index(Request $request): View
    {
        $query = Monster::query();
        $search = trim((string) $request->input('search', ''));

        if ($search !== '' && mb_strlen($search) >= 2) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('name_pt', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->value());
        }

        if ($request->filled('cr')) {
            $query->where('challenge_rating', (float) $request->input('cr'));
        }

        $monsters = $query->orderBy('challenge_rating')->orderBy('name')->paginate(9)->withQueryString();

        $types = Monster::query()
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        $challengeRatings = Monster::query()
            ->select('challenge_rating')
            ->distinct()
            ->orderBy('challenge_rating')
            ->pluck('challenge_rating');

        return view('monsters.index', [
            'monsters' => $monsters,
            'search' => $search,
            'type' => $request->input('type'),
            'cr' => $request->input('cr'),
            'types' => $types,
            'challengeRatings' => $challengeRatings,
        ]);
    }

    public function show(Monster $monster): View
    {
        return view('monsters.show', [
            'monster' => $monster,
        ]);
    }
}
