<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return view('welcome');
});

Route::get('/ingredients', [IngredientsController::class, 'index'])->name('ingredients.index');
Route::get('/ingredients/{id}', [IngredientsController::class, 'show'])->name('ingredients.show');

Route::get('/recipes', [\App\Http\Controllers\RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [\App\Http\Controllers\RecipeController::class, 'show'])->name('recipes.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
