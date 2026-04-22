<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierListRow extends Model
{
    use HasFactory;

    protected $fillable = ['tier_list_id', 'item_id', 'rank'];

    public function tierList()
    {
        return $this->belongsTo(TierList::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
