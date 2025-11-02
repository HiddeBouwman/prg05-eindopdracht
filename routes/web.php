<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Publieke Routes (geen authenticatie vereist)
Route::get('/', [RecipeController::class, 'index'])->name('home'); // Toont de hoofdpagina, is eigenlijk de recepten pagina
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index'); // Toont de indexpagina met alle gepubliceerde recepten
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create'); // Hier kan je een nieuw recept aanmaken
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show'); // Toont een recept
Route::get('/recipe_meals', [App\Http\Controllers\RecipeMealController::class, 'index'])->name('recipe_meals.index'); // Toont een lijst van alle recept-maaltijd combinaties. Wordt niet gebruikt
Route::get('/recipe_meals/{id}', [App\Http\Controllers\RecipeMealController::class, 'show'])->name('recipe_meals.show'); // Toont de details van een specifieke recept-maaltijd combinatie. Wordt ook niet gebruikt

// Geauthenticeerde Routes (vereist ingelogde gebruiker)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard'); // Toont het dashboard voor geverifieerde gebruikers. Je komt uiteindelijk toch niet bij het dashboard dus dit maakt niet uit.

    Route::get('/recipes/favorites', [RecipeController::class, 'favorites'])->name('recipes.favorites'); // Toont favoriete recepten

    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store'); // Slaat een nieuw recept op in de database.
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit'); // Bewerk een recept.
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update'); // Update een bestaand recept in de database.
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my'); // Toont de recepten van de ingelogde gebruiker.
    Route::post('/profile/recipes/{recipe}/toggle-published', [ProfileController::class, 'togglePublished'])->name('profile.toggle-published'); // Wijzig of een recept gepubliceerd is of niet
    Route::post('/recipes/{recipe}/toggle-favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.toggle-favorite'); // Wijzig de favorietstatus van een recept.
    Route::post('/recipes/{recipe}/rate', [RecipeController::class, 'rate'])->name('recipes.rate'); // Slaat een beoordeling op voor een recept.
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'deleteRecipe'])->name('recipes.delete'); // Verwijdert een recept.

    // Profiel Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Toont het profiel bewerkformulier.
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update het gebruikersprofiel.
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Verwijdert het gebruikersaccount.
});

// Admin Routes (vereist ingelogde admin gebruiker)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard'); // Toont het admin dashboard.
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users'); // Toont een lijst van alle gebruikers voor beheer.
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete'); // Verwijdert een gebruiker.
    Route::get('/admin/recipes', [AdminController::class, 'recipes'])->name('admin.recipes'); // Toont een lijst van alle recepten voor beheer.
    Route::delete('/admin/recipes/{recipe}', [AdminController::class, 'deleteRecipe'])->name('admin.recipes.delete'); // Verwijdert een recept.
});

// Logout Route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
}); // Brengt je terug naar de recepten pagina zonder dat je ingelogd bent.

require __DIR__.'/auth.php';
