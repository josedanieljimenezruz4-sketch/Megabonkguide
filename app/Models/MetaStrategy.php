<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaStrategy extends Model
{
    protected $guarded = [];

    public function votes()
    {
        return $this->hasMany(MetaStrategyVote::class);
    }

    public function getConfidencePercentageAttribute()
    {
        $totalVotes = $this->votes->count();
        if ($totalVotes === 0) {
            return null; // indicates no votes yet
        }
        $yesVotes = $this->votes->where('is_meta', true)->count();
        return round(($yesVotes / $totalVotes) * 100);
    }

    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    public function getTopBuildsAttribute()
    {
        return $this->builds()
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();
    }
}
