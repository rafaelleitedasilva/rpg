<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_id',
        'name',
        'setting',
        'description',
        'status',
        'max_players',
    ];

    protected $casts = [
        'max_players' => 'integer',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function maps()
    {
        return $this->hasMany(Map::class);
    }

    public function combatScenes()
    {
        return $this->hasMany(CombatScene::class);
    }

    public function monsterTemplates()
    {
        return $this->hasMany(MonsterTemplate::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function invites()
    {
        return $this->hasMany(CampaignInvite::class);
    }
}
