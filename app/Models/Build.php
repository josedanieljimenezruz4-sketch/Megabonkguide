<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;
    
    // Permitir asignación masiva para todos los campos pasados
    protected $guarded = [];

    // Relación con el Dueño
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    // Relación con el personaje (Lee de tabla Items)
    public function character() {
        return $this->belongsTo(Item::class, 'character_id', 'id');
    }

    // Opcional: Relaciones individuales para armas si requieres
    public function weapon1() {
        return $this->belongsTo(Item::class, 'weapon_1_id', 'id');
    }
}
