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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', // Asegúrate de que diga username aquí
        'email',
        'password',
        'google_id',
        'discord_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
     * Get the items that the user has unlocked.
     */
    public function unlocks()
    {
        return $this->belongsToMany(Item::class, 'user_unlocks', 'user_id', 'item_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class);
    }
}
