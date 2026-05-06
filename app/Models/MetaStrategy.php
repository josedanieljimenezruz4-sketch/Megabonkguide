<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaStrategy extends Model
{
    /**
     * ASIGNACIÃ“N MASIVA:
     * Al estar vacÃ­o el array $guarded, permitimos la asignaciÃ³n masiva de todos los campos.
     * Ideal para guardar estrategias directamente desde los formularios del panel de control.
     */
    protected $guarded = [];

    /**
     * RELACIÃ“N: 1 A MUCHOS
     * Una Estrategia Meta puede tener muchos votos (confirmando si funciona o no).
     */
    public function votes()
    {
        return $this->hasMany(MetaStrategyVote::class);
    }

    public function getConfidencePercentageAttribute()
    {
        $totalVotes = $this->votes->count();
        if ($totalVotes === 0) {
            return null; // indica que aÃºn no hay votos
        }
        $yesVotes = $this->votes->where('is_meta', true)->count();
        return round(($yesVotes / $totalVotes) * 100);
    }

    /**
     * RELACIÃ“N: 1 A MUCHOS
     * Una Estrategia Meta puede tener mÃºltiples Builds asociadas por la comunidad.
     * Esto conecta 'meta_strategy_id' en la tabla 'builds' con nuestro 'id'.
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    public function getTopBuildsAttribute()
    {
        return $this->builds()
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();
    }
}
