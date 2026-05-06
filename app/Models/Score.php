<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * ASIGNACIÓN MASIVA:
     * Permitido llenar todo. Usamos SoftDeletes para no borrar récords, solo ocultarlos.
     */
    protected $guarded = [];

    /**
     * RELACIÓN: Muchos a 1
     * Propietario del récord.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * Personaje usado en la partida (vinculado a la tabla items).
     */
    public function character()
    {
        return $this->belongsTo(Item::class, 'character_id');
    }

    /**
     * RELACIÓN: Muchos a 1
     * Build utilizada para conseguir la puntuación (opcional).
     */
    public function build()
    {
        return $this->belongsTo(Build::class);
    }
}
