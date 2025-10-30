<?php

namespace App\Http\Controllers;

use App\Models\Recipe_Meal;
use Illuminate\Http\Request;

class RecipeMealController extends Controller
{
    // app/Http/Controllers/RecipeMealController.php
    public function index()
    {
        $recipe_meals = Recipe_Meal::all();
        return view('recipe_meals.index', compact('recipe_meals'));
    }
    public function show($id)
    {
        $recipe_meal = Recipe_Meal::findOrFail($id);
        return view('recipe_meals.show', compact('recipe_meal'));
    }
}
