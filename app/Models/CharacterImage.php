<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CharacterImage extends Model
{
    protected $fillable = [
        'character_id',
        'path',
        'is_cover',
        'position',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'position' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Public URL for the stored file, on whichever disk is configured
     * (local "public" disk in development, an S3-compatible disk in
     * production — see config/filesystems.php).
     */
    public function url(): string
    {
        return Storage::disk(config('filesystems.default'))->url($this->path);
    }
}
