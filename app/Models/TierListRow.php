<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierListRow extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Campos de la fila específica (la tier y el ítem asociado).
     */
    protected $fillable = ['tier_list_id', 'item_id', 'rank'];

    /**
     * RELACIÓN: Muchos a 1
     * Vincula esta fila a su Tier List padre.
     */
    public function tierList()
    {
        return $this->belongsTo(TierList::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * El ítem (personaje, arma) que está clasificado en esta fila.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
