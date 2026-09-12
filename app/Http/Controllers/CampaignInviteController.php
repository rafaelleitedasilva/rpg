<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignInviteController extends Controller
{
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless($campaign->master_id === $request->user()->id, 403);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'different:'.$request->user()->id],
        ]);

        $exists = CampaignInvite::where('campaign_id', $campaign->id)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return back()->with('status', 'Convite já enviado.');
        }

        CampaignInvite::create([
            'campaign_id' => $campaign->id,
            'user_id' => $validated['user_id'],
            'inviter_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return redirect()->route('campaigns.show', $campaign);
    }

    public function accept(CampaignInvite $campaignInvite): RedirectResponse
    {
        abort_unless($campaignInvite->user_id === auth()->id(), 403);

        $campaignInvite->update(['status' => 'accepted']);

        return redirect()->route('friends.index')->with('status', 'Convite aceito.');
    }

    public function reject(CampaignInvite $campaignInvite): RedirectResponse
    {
        abort_unless($campaignInvite->user_id === auth()->id(), 403);

        $campaignInvite->update(['status' => 'rejected']);

        return redirect()->route('friends.index')->with('status', 'Convite recusado.');
    }
}
