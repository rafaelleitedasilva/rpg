<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Map;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'terrain' => ['nullable', 'string', 'max:255'],
            'width' => ['required', 'integer', 'min:1', 'max:50'],
            'height' => ['required', 'integer', 'min:1', 'max:50'],
            'grid_size' => ['required', 'integer', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
        ]);

        $campaign->maps()->create($validated);

        return redirect()->route('campaigns.show', $campaign);
    }
}
