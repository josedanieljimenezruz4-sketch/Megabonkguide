<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'suggested_tier',
        'status',
    ];

    /**
     * El usuario que hizo la sugerencia.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El item sugerido.
     */
    public function item()
    {
        // En Eloquent si la primary key no es 'id' entera, belongsTo sigue funcionando 
        // siempre y cuando item_id y id sean del mismo tipo (string en este caso).
        return $this->belongsTo(Item::class);
    }
}
