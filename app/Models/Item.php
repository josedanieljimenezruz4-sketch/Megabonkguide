<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function builds()
    {
        return $this->belongsToMany(Build::class, 'build_item', 'item_id', 'build_id')
                    ->withPivot('slot_type')
                    ->withTimestamps();
    }
}
