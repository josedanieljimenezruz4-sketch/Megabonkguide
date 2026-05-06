<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInfo extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Igual que Faq, protege la inserción de datos de la wiki.
     */
    protected $fillable = ['title', 'content', 'category'];
}
