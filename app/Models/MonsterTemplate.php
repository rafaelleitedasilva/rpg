<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonsterTemplate extends Model
{
    use HasFactory;

    protected $table = 'monster_templates';

    protected $fillable = [
        'campaign_id',
        'master_id',
        'name',
        'description',
        'armor_class',
        'max_hp',
        'initiative',
        'speed',
        'challenge_rating',
        'attributes',
    ];

    protected $casts = [
        'armor_class' => 'integer',
        'max_hp' => 'integer',
        'initiative' => 'integer',
        'speed' => 'integer',
        'challenge_rating' => 'decimal:1',
        'attributes' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_id');
    }
}
