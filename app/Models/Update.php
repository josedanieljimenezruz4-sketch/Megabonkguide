<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Estos campos reciben los datos de las novedades / noticias externas.
     */
    protected $fillable = [
        'external_id',
        'title',
        'content',
        'url',
        'type',
        'source',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
