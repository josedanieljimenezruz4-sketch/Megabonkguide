<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Campos permitidos para creación masiva, protegiendo contra inyección de datos.
     */
    protected $fillable = ['user_id', 'tier_list_id', 'community_post_id', 'parent_id', 'content'];

    /**
     * RELACIÓN: Muchos a 1
     * El comentario es escrito por un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * Opcional: El comentario pertenece a una TierList concreta.
     */
    public function tierList()
    {
        return $this->belongsTo(TierList::class);
    }

    /**
     * RELACIÓN: Muchos a 1
     * Opcional: El comentario pertenece a una publicación de la comunidad.
     */
    public function communityPost()
    {
        return $this->belongsTo(CommunityPost::class);
    }

    /**
     * RELACIÓN: Muchos a 1 (Auto-referencial)
     * Permite responder a otros comentarios vinculando el parent_id al id del padre.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * RELACIÓN: 1 a Muchos (Auto-referencial)
     * Obtiene todas las respuestas (hijos) de este comentario. Carga también el usuario.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with(['user', 'replies']);
    }
}