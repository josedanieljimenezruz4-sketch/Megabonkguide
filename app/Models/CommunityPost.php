<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    /**
     * ASIGNACIÓN MASIVA:
     * Variables protegidas que pueden recibir datos del formulario directamente.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'likes_count',
        'image_path'
    ];

    /**
     * RELACIÓN: Muchos a 1
     * Autor de la publicación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIÓN: 1 a Muchos
     * Múltiples comentarios asociados a este post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * RELACIÓN: Muchos a Muchos
     * Los usuarios que le han dado 'Like' al post. Usamos tabla intermedia.
     * withTimestamps() gestiona automáticamente cuándo se dio el Like.
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'community_post_likes', 'community_post_id', 'user_id')->withTimestamps();
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
