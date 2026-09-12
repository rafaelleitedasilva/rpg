<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MonsterTemplateController extends Controller
{
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'armor_class' => ['nullable', 'integer', 'min:1', 'max:50'],
            'max_hp' => ['nullable', 'integer', 'min:1'],
            'initiative' => ['nullable', 'integer', 'min:-20', 'max:30'],
            'speed' => ['nullable', 'integer', 'min:0', 'max:120'],
            'challenge_rating' => ['nullable', 'numeric', 'min:0', 'max:30'],
        ]);

        $validated['campaign_id'] = $campaign->id;
        $validated['master_id'] = $request->user()->id;

        $campaign->monsterTemplates()->create($validated);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Monstro adicionado ao catálogo.');
    }
}
