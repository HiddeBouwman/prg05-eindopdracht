<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')
            ->withPivot(['amount', 'unit'])
            ->withTimestamps();
    }

    public function equipment()
    {
        return $this->hasMany(RecipeEquipment::class);
    }

    public function steps()
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function tips()
    {
        return $this->hasMany(RecipeTip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'instructions',
        'prep_time',
        'cook_time',
        'servings',
        'image_url',
        'video_url',
        'is_published',
    ];
}
