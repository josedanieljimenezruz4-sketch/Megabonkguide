<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierList extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Evita inyección de datos no deseados.
     */
    protected $fillable = ['user_id', 'titulo', 'categoria', 'descripcion'];

    /**
     * RELACIÓN: Muchos a 1
     * Dueño o creador de la Tier List.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: 1 a Muchos
     * Trae todas las filas (ítems y sus rangos S, A, B...) dentro de esta lista.
     */
    public function rows()
    {
        return $this->hasMany(TierListRow::class);
    }

    /**
     * RELACIÓN: 1 a Muchos
     * Comentarios que la comunidad deja en esta Tier List.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
