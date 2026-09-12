<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    use HasFactory;

    protected $table = 'tokens';

    protected $fillable = [
        'campaign_id',
        'map_id',
        'combat_scene_id',
        'monster_template_id',
        'name',
        'type',
        'x',
        'y',
        'initiative',
        'hp',
        'max_hp',
        'color',
        'details',
    ];

    protected $casts = [
        'x' => 'integer',
        'y' => 'integer',
        'initiative' => 'integer',
        'hp' => 'integer',
        'max_hp' => 'integer',
        'details' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function combatScene(): BelongsTo
    {
        return $this->belongsTo(CombatScene::class);
    }

    public function monsterTemplate(): BelongsTo
    {
        return $this->belongsTo(MonsterTemplate::class);
    }

    public function moveTo(int $x, int $y): void
    {
        $this->x = max(0, $x);
        $this->y = max(0, $y);
        $this->save();
    }

    public function applyDamage(int $amount): void
    {
        $amount = max(0, $amount);
        $this->hp = max(0, $this->hp - $amount);
        $this->save();
    }

    public function applyHealing(int $amount): void
    {
        $amount = max(0, $amount);
        $this->hp = min((int) $this->max_hp, (int) $this->hp + $amount);
        $this->save();
    }
}
