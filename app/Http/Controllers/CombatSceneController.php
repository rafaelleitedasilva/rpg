<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CombatScene;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CombatSceneController extends Controller
{
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'initiative_order' => ['nullable', 'string'],
            'round' => ['required', 'integer', 'min:1'],
            'turn' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['campaign_id'] = $campaign->id;
        $validated['status'] = $validated['status'] ?? 'ativo';

        $campaign->combatScenes()->create($validated);

        return redirect()->route('campaigns.show', $campaign);
    }

    public function advanceTurn(Request $request, Campaign $campaign, CombatScene $combatScene): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);
        abort_unless($combatScene->campaign_id === $campaign->id, 404);

        $combatScene->advanceTurn();

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Turno da cena avançado.');
    }
}
