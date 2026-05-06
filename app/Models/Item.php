<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Desactivamos la protecciÃ³n de asignaciÃ³n masiva.
    // Esto nos permite crear Ã­tems enviando un array de datos directamente.
    protected $guarded = [];

    // Como nuestros IDs son cadenas de texto (ej. "pj-001", "arma-002") y no nÃºmeros,
    // debemos indicar que el tipo de la clave principal es 'string'.
    protected $keyType = 'string';

    // Al no ser nÃºmeros enteros, los IDs no son autoincrementables.
    // Desactivamos esta opciÃ³n para evitar errores al guardar en base de datos.
    public $incrementing = false;

    /**
     * RelaciÃ³n Muchos a Muchos con Builds.
     * Un Ã­tem (arma, tomo, etc.) puede pertenecer a muchas builds,
     * y una build puede tener varios Ã­tems equipados.
     */
    public function builds()
    {
        return $this->belongsToMany(Build::class, 'build_item', 'item_id', 'build_id')
            ->withPivot('slot_type')
            ->withTimestamps();
    }

    /**
     * RelaciÃ³n Uno a Muchos con Builds (como personaje principal).
     * Si este Ã­tem es un personaje, puede ser el protagonista de mÃºltiples builds.
     */
    public function characterBuilds()
    {
        return $this->hasMany(Build::class, 'character_id');
    }
}
