<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildVote extends Model
{
    use HasFactory;

    protected $table = 'build_user_votes';

    /**
     * ASIGNACIÓN MASIVA:
     * Define qué campos se pueden llenar de forma segura mediante arrays (ej: request->all()).
     */
    protected $fillable = [
        'user_id',
        'build_id',
        'score',
    ];

    /**
     * RELACIÓN: Muchos a 1
     * Cada voto pertenece a un único usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * Cada voto se emite sobre una única Build.
     */
    public function build()
    {
        return $this->belongsTo(Build::class);
    }
}
