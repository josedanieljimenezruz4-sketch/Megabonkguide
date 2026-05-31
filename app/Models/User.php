<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * PROTECCIÃ“N DE ASIGNACIÃ“N MASIVA:
     * El array $fillable define exactamente quÃ© campos se pueden llenar de forma masiva
     * al crear o actualizar un usuario (ej: User::create($request->all())).
     * Esto evita que usuarios malintencionados inyecten datos como 'is_admin' => true.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', // AsegÃºrate de que diga username aquÃ­
        'email',
        'password',
        'google_id',
        'discord_id',
        'avatar',
        'banned_until',
    ];

    /**
     * Los atributos que deben ocultarse para la serializaciÃ³n.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_until' => 'datetime',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        $avatarUrl = asset('images/default-avatar.png');
        
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                $avatarUrl = $this->avatar;
            } else {
                $avatarUrl = asset('storage/avatars/' . $this->avatar);
            }
        }
        
        return $avatarUrl;
    }

    /**
     * RELACIÓN: MUCHOS A MUCHOS
     * Un usuario puede desbloquear muchos Ã­tems y un Ã­tem puede ser desbloqueado por muchos usuarios.
     * Conecta la tabla 'users' y 'items' a travÃ©s de la tabla intermedia 'user_unlocks'.
     * ->withTimestamps() mantiene actualizadas las fechas created_at y updated_at en la tabla pivote.
     */
    public function unlocks()
    {
        return $this->belongsToMany(Item::class, 'user_unlocks', 'user_id', 'item_id')->withTimestamps();
    }

    /**
     * RELACIÓN: 1 A MUCHOS
     * Un usuario puede escribir múltiples comentarios a lo largo de la aplicación.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * RELACIÓN: 1 A MUCHOS
     * Un usuario puede publicar varios posts en la sección de la Comunidad.
     */
    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    /**
     * RELACIÓN: 1 A MUCHOS
     * Un usuario es autor de muchas Builds (estrategias de equipamiento).
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    /**
     * RELACIÓN: 1 A MUCHOS
     * Un usuario puede hacer varias sugerencias de tier.
     */
    public function tierSuggestions()
    {
        return $this->hasMany(TierSuggestion::class);
    }
}
