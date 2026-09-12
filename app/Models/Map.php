<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Map extends Model
{
    use HasFactory;

    protected $table = 'maps';

    protected $fillable = [
        'campaign_id',
        'name',
        'terrain',
        'width',
        'height',
        'grid_size',
        'description',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'grid_size' => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
