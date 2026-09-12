<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Character;
use App\Models\Spell;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::where('master_id', auth()->id())
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            [
                'label' => 'Fichas criadas',
                'value' => Character::where('user_id', auth()->id())->count(),
                'description' => 'personagens em andamento',
            ],
            [
                'label' => 'Magias disponíveis',
                'value' => Spell::count(),
                'description' => 'no compêndio D&D 5e',
            ],
            [
                'label' => 'Campanhas',
                'value' => $campaigns->count(),
                'description' => 'em preparação',
            ],
            [
                'label' => 'Sessões',
                'value' => 2,
                'description' => 'atualizadas esta semana',
            ],
        ];

        $recentCharacters = Character::where('user_id', auth()->id())
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('stats', 'recentCharacters', 'campaigns'));
    }
}
