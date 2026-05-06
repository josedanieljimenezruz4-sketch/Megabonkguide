<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Evitamos que nos inyecten un estado 'completado' forzado limitando a estos campos.
     */
    protected $fillable = [
        'user_id',
        'name',
        'subject',
        'content',
    ];

    /**
     * RELACIÓN: Muchos a 1
     * La sugerencia la crea un usuario autenticado.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
