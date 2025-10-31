<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [RecipeController::class, 'index'])->name('home');
Route::get('/recipes', [\App\Http\Controllers\RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
Route::get('/recipes/favorites', [RecipeController::class, 'favorites'])->middleware('auth')->name('recipes.favorites');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/recipe_meals', [\App\Http\Controllers\RecipeMealController::class, 'index'])->name('recipe_meals.index');
Route::get('/recipe_meals/{id}', [\App\Http\Controllers\RecipeMealController::class, 'show'])->name('recipe_meals.show');

Route::middleware('auth')->group(function() {
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my');
    Route::post('/profile/recipes/{recipe}/toggle-published', [ProfileController::class, 'togglePublished'])->name('profile.toggle-published');
    Route::post('/recipes/{recipe}/toggle-favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.toggle-favorite');
    Route::post('/recipes/{recipe}/rate', [RecipeController::class, 'rate'])->name('recipes.rate');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'deleteRecipe'])->name('recipes.delete');
});

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/recipes', [AdminController::class, 'recipes'])->name('admin.recipes');
    Route::delete('/admin/recipes/{recipe}', [AdminController::class, 'deleteRecipe'])->name('admin.recipes.delete');
});

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
});

require __DIR__.'/auth.php';
