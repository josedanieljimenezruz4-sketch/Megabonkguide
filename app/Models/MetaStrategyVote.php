<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaStrategyVote extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function strategy()
    {
        return $this->belongsTo(MetaStrategy::class, 'meta_strategy_id');
    }
}
