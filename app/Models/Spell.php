<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'school',
        'casting_time',
        'range',
        'components',
        'duration',
        'concentration',
        'ritual',
        'description',
        'classes',
        'races',
    ];

    protected $casts = [
        'classes' => 'array',
        'races' => 'array',
        'concentration' => 'boolean',
        'ritual' => 'boolean',
        'level' => 'integer',
    ];
}
