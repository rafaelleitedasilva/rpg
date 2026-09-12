<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::with('master')->latest()->get();

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign): View
    {
        abort_unless($campaign->master_id === auth()->id(), 403);

        $campaign->load(['maps', 'combatScenes']);

        return view('campaigns.show', compact('campaign'));
    }

    public function create(): View
    {
        return view('campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'setting' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_players' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        $validated['master_id'] = $request->user()->id;

        $campaign = Campaign::create($validated);

        return redirect()->route('campaigns.show', $campaign);
    }
}
