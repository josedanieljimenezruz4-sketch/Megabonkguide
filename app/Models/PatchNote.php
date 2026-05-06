<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatchNote extends Model
{
    /**
     * ASIGNACIÓN MASIVA:
     * Permitimos asignación directa para crear notas de parche rápidamente.
     */
    protected $guarded = [];
}
