<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;
    
    // Permitir asignación masiva para todos los campos pasados
    protected $guarded = [];

    protected $appends = ['created_at_human'];

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }

    // Relación con el Dueño
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function metaStrategy() {
        return $this->belongsTo(MetaStrategy::class);
    }
    
    // Relación con el personaje (Lee de tabla Items)
    public function character() {
        return $this->belongsTo(Item::class, 'character_id', 'id');
    }

    // Relación Muchos-a-Muchos con items (todas las ranuras)
    public function items() {
        return $this->belongsToMany(Item::class, 'build_item', 'build_id', 'item_id')
                    ->withPivot('slot_type')
                    ->withTimestamps();
    }

    // Helpers opcionales si quieres obtener la colección filtrada directamente desde Eloquent
    public function weapons() {
        return $this->items()->wherePivot('slot_type', 'Arma');
    }

    public function tomes() {
        return $this->items()->wherePivot('slot_type', 'Tomo');
    }

    public function accessories() {
        return $this->items()->wherePivot('slot_type', 'Item');
    }

    public function votes()
    {
        return $this->hasMany(BuildVote::class);
    }
}
