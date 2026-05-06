<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaStrategyVote extends Model
{
    /**
     * ASIGNACIÓN MASIVA:
     * Array vacío indica que todos los campos son fillables (permitidos).
     */
    protected $guarded = [];

    /**
     * RELACIÓN: Muchos a 1
     * El usuario que emitió el voto para confirmar el meta.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * Conecta el voto con la estrategia meta evaluada.
     */
    public function strategy()
    {
        return $this->belongsTo(MetaStrategy::class, 'meta_strategy_id');
    }
}
