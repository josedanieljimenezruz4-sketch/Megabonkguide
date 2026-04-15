<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildVote extends Model
{
    use HasFactory;

    protected $table = 'build_user_votes';

    protected $fillable = [
        'user_id',
        'build_id',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function build()
    {
        return $this->belongsTo(Build::class);
    }
}
