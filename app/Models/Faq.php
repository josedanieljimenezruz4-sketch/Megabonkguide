<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Permite rellenar titulo, contenido y categoría desde el admin sin errores.
     */
    protected $fillable = ['title', 'content', 'category'];
}
