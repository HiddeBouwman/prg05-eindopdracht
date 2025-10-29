<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return view('welcome');
});

Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// routes/web.php
Route::get('/recipes', [\App\Http\Controllers\RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/filter', [RecipeController::class, 'filter'])->name('recipes.filter');

Route::get('/recipe_meals', [\App\Http\Controllers\RecipeMealController::class, 'index'])->name('recipe_meals.index');
Route::get('/recipe_meals/{id}', [\App\Http\Controllers\RecipeMealController::class, 'show'])->name('recipe_meals.show');

Route::middleware('auth')->group(function () {
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::get('/recipes/favorites', [RecipeController::class, 'favorites'])->name('recipes.favorites');
    Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');
});

require __DIR__.'/auth.php';
