<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    protected $fillable = [
        'type',
        'step_number',
        'instruction',
        'tip',
    ];

    /** @use HasFactory<\Database\Factories\RecipeStepFactory> */
    use HasFactory;
}
