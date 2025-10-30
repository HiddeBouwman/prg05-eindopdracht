<?php

namespace App\Models;

use Database\Factories\RecipeEquipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeEquipment extends Model
{
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    protected $fillable = [
        'name',
        'quantity',
    ];

    protected $touches = ['recipe'];

    /** @use HasFactory<RecipeEquipmentFactory> */
    use HasFactory;
}
