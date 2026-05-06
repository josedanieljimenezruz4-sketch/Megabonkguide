<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;
    
    /**
     * ASIGNACIÃ“N MASIVA:
     * Al usar $guarded vacÃ­o, indicamos que todos los campos de este modelo se pueden 
     * llenar mediante asignaciÃ³n masiva (ej: Build::create($array)). Es Ãºtil porque
     * tenemos control manual de quÃ© datos pasamos desde el controlador.
     */
    protected $guarded = [];

    protected $appends = ['created_at_human'];

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }

    /**
     * RELACIÃ“N: N a 1 (Inversa de Uno a Muchos)
     * Una Build pertenece a un Ãºnico Usuario (su autor).
     * Esto conecta 'user_id' en la tabla builds con 'id' en la tabla users.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÃ“N: N a 1
     * Una Build puede estar enlazada a una Ãºnica Estrategia Meta.
     * Permite saber si esta build es ejemplo de una estrategia competitiva oficial.
     */
    public function metaStrategy() {
        return $this->belongsTo(MetaStrategy::class);
    }
    
    /**
     * RELACIÃ“N: N a 1
     * Una Build estÃ¡ protagonizada por un Ãºnico personaje.
     * Como la clave externa no se llama 'item_id' sino 'character_id', lo especificamos
     * en el segundo y tercer parÃ¡metro.
     */
    public function character() {
        return $this->belongsTo(Item::class, 'character_id', 'id');
    }

    /**
     * RELACIÃ“N: MUCHOS A MUCHOS
     * Una Build puede tener equipados varios Ãtems (armas, accesorios, tomos) y un Ãtem puede estar en muchas Builds.
     * ->withPivot('slot_type') nos permite saber en quÃ© hueco se equipÃ³ este Ã­tem (arma principal, secundaria, etc.).
     * ->withTimestamps() guarda automÃ¡ticamente cuÃ¡ndo se creÃ³ o actualizÃ³ la fila en la tabla intermedia 'build_item'.
     */
    public function items() {
        return $this->belongsToMany(Item::class, 'build_item', 'build_id', 'item_id')
                    ->withPivot('slot_type')
                    ->withTimestamps();
    }

    // Helpers opcionales si quieres obtener la colecciÃ³n filtrada directamente desde Eloquent
    public function weapons() {
        return $this->items()->wherePivot('slot_type', 'Arma');
    }

    public function tomes() {
        return $this->items()->wherePivot('slot_type', 'Tomo');
    }

    public function accessories() {
        return $this->items()->wherePivot('slot_type', 'Item');
    }

    /**
     * RELACIÃ“N: 1 A MUCHOS
     * Una Build puede recibir mÃºltiples votos de distintos usuarios.
     */
    public function votes()
    {
        return $this->hasMany(BuildVote::class);
    }
}
