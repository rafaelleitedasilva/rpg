<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendRequestController extends Controller
{
    public function index(): View
    {
        $sentRequests = auth()->user()->sentFriendRequests()->with('recipient')->latest()->get();
        $receivedRequests = auth()->user()->receivedFriendRequests()->with('sender')->latest()->get();
        $campaignInvites = auth()->user()->campaignInvites()->with('campaign.master', 'inviter')->latest()->get();
        $friends = User::whereHas('sentFriendRequests', fn ($query) => $query->where('recipient_id', auth()->id())->where('status', 'accepted'))
            ->orWhereHas('receivedFriendRequests', fn ($query) => $query->where('sender_id', auth()->id())->where('status', 'accepted'))
            ->get();

        return view('friends.index', compact('sentRequests', 'receivedRequests', 'campaignInvites', 'friends'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'different:'.auth()->id()],
        ]);

        $duplicate = FriendRequest::where(function ($query) use ($validated) {
            $query->where('sender_id', auth()->id())
                ->where('recipient_id', $validated['user_id']);
        })->orWhere(function ($query) use ($validated) {
            $query->where('sender_id', $validated['user_id'])
                ->where('recipient_id', auth()->id());
        })->exists();

        if ($duplicate) {
            return back()->with('status', 'Solicitação já existente.');
        }

        FriendRequest::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['user_id'],
            'status' => 'pending',
        ]);

        return redirect()->route('friends.index');
    }

    public function accept(FriendRequest $friendRequest): RedirectResponse
    {
        abort_unless($friendRequest->recipient_id === auth()->id(), 403);

        $friendRequest->update(['status' => 'accepted']);

        return redirect()->route('friends.index');
    }

    public function reject(FriendRequest $friendRequest): RedirectResponse
    {
        abort_unless($friendRequest->recipient_id === auth()->id(), 403);

        $friendRequest->update(['status' => 'rejected']);

        return redirect()->route('friends.index');
    }
}
