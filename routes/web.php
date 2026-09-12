<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpellController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('characters', CharacterController::class);
    Route::get('/spells', [SpellController::class, 'index'])->name('spells.index');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::post('/campaigns/{campaign}/maps', [\App\Http\Controllers\MapController::class, 'store'])->name('campaigns.maps.store');
    Route::post('/campaigns/{campaign}/monster-templates', [\App\Http\Controllers\MonsterTemplateController::class, 'store'])->name('campaigns.monster-templates.store');
    Route::post('/campaigns/{campaign}/combat-scenes', [\App\Http\Controllers\CombatSceneController::class, 'store'])->name('campaigns.combat-scenes.store');
    Route::post('/campaigns/{campaign}/combat-scenes/{combatScene}/advance', [\App\Http\Controllers\CombatSceneController::class, 'advanceTurn'])->name('campaigns.combat-scenes.advance');
    Route::post('/campaigns/{campaign}/combat-scenes/{combatScene}/tokens', [\App\Http\Controllers\TokenController::class, 'store'])->name('campaigns.combat-scenes.tokens.store');
    Route::post('/campaigns/{campaign}/combat-scenes/{combatScene}/tokens/{token}/move', [\App\Http\Controllers\TokenController::class, 'move'])->name('campaigns.combat-scenes.tokens.move');
    Route::post('/campaigns/{campaign}/combat-scenes/{combatScene}/tokens/{token}/damage', [\App\Http\Controllers\TokenController::class, 'damage'])->name('campaigns.combat-scenes.tokens.damage');
    Route::post('/campaigns/{campaign}/combat-scenes/{combatScene}/tokens/{token}/heal', [\App\Http\Controllers\TokenController::class, 'heal'])->name('campaigns.combat-scenes.tokens.heal');
    Route::post('/campaigns/{campaign}/invites', [\App\Http\Controllers\CampaignInviteController::class, 'store'])->name('campaigns.invites.store');
    Route::post('/campaigns/invites/{campaignInvite}/accept', [\App\Http\Controllers\CampaignInviteController::class, 'accept'])->name('campaigns.invites.accept');
    Route::post('/campaigns/invites/{campaignInvite}/reject', [\App\Http\Controllers\CampaignInviteController::class, 'reject'])->name('campaigns.invites.reject');

    Route::get('/friends', [\App\Http\Controllers\FriendRequestController::class, 'index'])->name('friends.index');
    Route::post('/friends/requests', [\App\Http\Controllers\FriendRequestController::class, 'store'])->name('friends.requests.store');
    Route::post('/friends/requests/{friendRequest}/accept', [\App\Http\Controllers\FriendRequestController::class, 'accept'])->name('friends.requests.accept');
    Route::post('/friends/requests/{friendRequest}/reject', [\App\Http\Controllers\FriendRequestController::class, 'reject'])->name('friends.requests.reject');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
