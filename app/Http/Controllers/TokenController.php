<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CombatScene;
use App\Models\Token;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function store(Request $request, Campaign $campaign, CombatScene $combatScene): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);
        abort_unless($combatScene->campaign_id === $campaign->id, 404);

        $validated = $request->validate([
            'monster_template_id' => ['nullable', 'exists:monster_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:monster,pc,npc,environment'],
            'x' => ['required', 'integer', 'min:0'],
            'y' => ['required', 'integer', 'min:0'],
            'initiative' => ['nullable', 'integer', 'min:-20', 'max:30'],
            'hp' => ['nullable', 'integer', 'min:0'],
            'max_hp' => ['nullable', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        if (! empty($validated['monster_template_id'])) {
            $monster = $campaign->monsterTemplates()->findOrFail($validated['monster_template_id']);
            $validated['monster_template_id'] = $monster->id;
            $validated['initiative'] = $validated['initiative'] ?? $monster->initiative;
            $validated['max_hp'] = $validated['max_hp'] ?? $monster->max_hp;
            $validated['hp'] = $validated['hp'] ?? $monster->max_hp;
        }

        $validated['campaign_id'] = $campaign->id;
        $validated['map_id'] = $validated['map_id'] ?? $combatScene->map_id;
        $validated['combat_scene_id'] = $combatScene->id;
        $validated['type'] = $validated['type'] ?? 'monster';

        $combatScene->tokens()->create($validated);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Token adicionado à cena de combate.');
    }

    public function move(Request $request, Campaign $campaign, CombatScene $combatScene, Token $token): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);
        abort_unless($combatScene->campaign_id === $campaign->id, 404);
        abort_unless($token->combat_scene_id === $combatScene->id, 404);

        $validated = $request->validate([
            'x' => ['required', 'integer', 'min:0'],
            'y' => ['required', 'integer', 'min:0'],
        ]);

        $token->moveTo($validated['x'], $validated['y']);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Token movido com sucesso.');
    }

    public function damage(Request $request, Campaign $campaign, CombatScene $combatScene, Token $token): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);
        abort_unless($combatScene->campaign_id === $campaign->id, 404);
        abort_unless($token->combat_scene_id === $combatScene->id, 404);

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:0', 'max:200'],
        ]);

        $token->applyDamage($validated['amount']);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Dano aplicado ao token.');
    }

    public function heal(Request $request, Campaign $campaign, CombatScene $combatScene, Token $token): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);
        abort_unless($combatScene->campaign_id === $campaign->id, 404);
        abort_unless($token->combat_scene_id === $combatScene->id, 404);

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:0', 'max:200'],
        ]);

        $token->applyHealing($validated['amount']);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Cura aplicada ao token.');
    }
}
