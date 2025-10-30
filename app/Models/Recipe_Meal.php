<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe_Meal extends Model
{
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }


    use HasFactory;
    protected $table = 'recipe_meal';
    protected $fillable = [
        'recipe_id',
        'meal_id'
    ];
}
