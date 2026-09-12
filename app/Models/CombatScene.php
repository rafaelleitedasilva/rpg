<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CombatScene extends Model
{
    use HasFactory;

    protected $table = 'combat_scenes';

    protected $fillable = [
        'campaign_id',
        'map_id',
        'name',
        'status',
        'ruleset',
        'round',
        'turn',
        'initiative_order',
        'notes',
    ];

    protected $casts = [
        'round' => 'integer',
        'turn' => 'integer',
        'initiative_order' => 'string',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function advanceTurn(): void
    {
        $this->turn += 1;

        if ($this->turn > 10) {
            $this->turn = 1;
            $this->round += 1;
        }

        $this->save();
    }
}
