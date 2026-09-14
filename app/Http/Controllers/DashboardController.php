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
        $characterCount = Character::where('user_id', auth()->id())->count();

        $campaigns = Campaign::where('master_id', auth()->id())
            ->with('acceptedInvites.user')
            ->latest()
            ->take(3)
            ->get();

        $campaignCount = Campaign::where('master_id', auth()->id())->count();

        $stats = [
            [
                'icon' => 'user',
                'label' => 'Fichas criadas',
                'value' => $characterCount,
                'description' => 'personagens em sua conta',
                'route' => route('characters.index'),
            ],
            [
                'icon' => 'sparkles',
                'label' => 'Magias disponíveis',
                'value' => Spell::count(),
                'description' => 'no seu acervo',
                'route' => route('spells.index'),
            ],
            [
                'icon' => 'flag',
                'label' => 'Campanhas',
                'value' => $campaignCount,
                'description' => 'em andamento',
                'route' => route('campaigns.index'),
            ],
            [
                'icon' => 'calendar',
                // No scheduling feature exists yet — this is a static placeholder,
                // not a real count, until sessions become a real resource.
                'value' => null,
                'label' => 'Sessões',
                'description' => 'em breve',
                'route' => null,
            ],
        ];

        $recentCharacters = Character::where('user_id', auth()->id())
            ->with('images')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('stats', 'recentCharacters', 'campaigns'));
    }
}
