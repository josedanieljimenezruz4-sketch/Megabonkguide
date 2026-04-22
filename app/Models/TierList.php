<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierList extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'titulo', 'categoria', 'descripcion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rows()
    {
        return $this->hasMany(TierListRow::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
