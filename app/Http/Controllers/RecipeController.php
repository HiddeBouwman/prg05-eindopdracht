<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Toon het formulier om een recept aan te maken.
     */
    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();

        return view('recipes.create', compact('ingredients'));
    }

    /**
     * Sla een nieuw recept op.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // Upload afbeelding
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipe_images', 'public');
            $validated['image_url'] = $path;
        }

        // Voeg extra velden toe
        $validated['user_id'] = Auth::id();
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Recipe::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_published'] = true;

        // Maak het recept
        $recipe = Recipe::create($validated);

        // Ingrediënten koppelen
        if ($request->filled('ingredients')) {
            foreach ($request->input('ingredients') as $data) {
                if (!empty($data['name'])) {
                    $ingredient = Ingredient::firstOrCreate(['name' => $data['name']]);
                    $recipe->ingredients()->attach($ingredient->id, [
                        'amount' => $data['amount'] ?? null,
                        'unit' => $data['unit'] ?? null,
                    ]);
                }
            }
        }

        // Benodigdheden
        if ($request->filled('equipment')) {
            foreach ($request->input('equipment') as $item) {
                if (!empty($item['name'])) {
                    $recipe->equipment()->create([
                        'name' => $item['name'],
                    ]);
                }
            }
        }

        // Stappen
        if ($request->filled('steps')) {
            foreach ($request->input('steps') as $i => $step) {
                if (!empty($step['instruction'])) {
                    $recipe->steps()->create([
                        'type' => $step['type'] ?? 'cooking',
                        'step_number' => $i + 1,
                        'instruction' => $step['instruction'],
                        'tip' => $step['tip'] ?? null,
                    ]);
                }
            }
        }

        // Tips
        if ($request->filled('tips')) {
            foreach ($request->input('tips') as $tip) {
                if (!empty($tip['text'])) {
                    $recipe->tips()->create(['tip' => $tip['text']]);
                }
            }
        }

        return redirect()->route('recipes.show', $recipe->id)
            ->with('success', 'Recept succesvol toegevoegd!');
    }

    /**
     * Toon een enkel recept.
     */
    public function show(Recipe $recipe)
    {
        if (!$recipe->is_published && (!Auth::check() || (Auth::id() !== $recipe->user_id && !Auth::user()->is_admin))) {
            return redirect()->route('recipes.index');
        }

        $recipe->load('user');
        $recipe->load(['ingredients', 'steps', 'equipment', 'tips']);
        return view('recipes.show', compact('recipe'));
    }

    public function index(Request $request)
    {
        $query = $request->input('search');

        $recipes = Recipe::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhereHas('ingredients', function ($subSubQ) use ($query) {
                            $subSubQ->where('name', 'like', '%' . $query . '%');
                        });
                });
            })
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'recipes' => $recipes->items(),
                'pagination' => $recipes->links()->toHtml(),
            ]);
        }

        return view('recipes.index', compact('recipes', 'query'));
    }

    public function edit(Recipe $recipe)
    {
        if ($recipe->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $ingredients = Ingredient::orderBy('name')->get();
        return view('recipes.edit', compact('recipe', 'ingredients'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'servings' => 'required|integer|min:1',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($recipe->image_url) {
                \Storage::disk('public')->delete($recipe->image_url);
            }
            $path = $request->file('image')->store('recipe_images', 'public');
            $validated['image_url'] = $path;
        }

        if ($validated['title'] !== $recipe->title) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Recipe::where('slug', $slug)->where('id', '!=', $recipe->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $recipe->update($validated);

        $recipe->ingredients()->detach();
        if ($request->filled('ingredients')) {
            foreach ($request->input('ingredients') as $data) {
                if (!empty($data['name'])) {
                    $ingredient = Ingredient::firstOrCreate(['name' => $data['name']]);
                    $recipe->ingredients()->attach($ingredient->id, [
                        'amount' => $data['amount'] ?? null,
                        'unit' => $data['unit'] ?? null,
                    ]);
                }
            }
        }

        $recipe->equipment()->delete();
        if ($request->filled('equipment')) {
            foreach ($request->input('equipment') as $item) {
                if (!empty($item['name'])) {
                    $recipe->equipment()->create(['name' => $item['name']]);
                }
            }
        }

        $recipe->steps()->delete();
        if ($request->filled('steps')) {
            foreach ($request->input('steps') as $i => $step) {
                if (!empty($step['instruction'])) {
                    $recipe->steps()->create([
                        'step_number' => $i + 1,
                        'instruction' => $step['instruction'],
                        'tip' => $step['tip'] ?? null,
                    ]);
                }
            }
        }

        $recipe->tips()->delete();
        if ($request->filled('tips')) {
            foreach ($request->input('tips') as $tip) {
                if (!empty($tip['text'])) {
                    $recipe->tips()->create(['tip' => $tip['text']]);
                }
            }
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Recept bijgewerkt!');
    }

    public function myRecipes()
    {
        $recipes = Auth::user()->recipes()->paginate(12);
        return view('recipes.my', compact('recipes'));
    }

    public function toggleFavorite(Recipe $recipe)
    {
        $user = Auth::user();
        if ($user->id === $recipe->user_id) {
            return response()->json(['error' => 'Cannot favorite own recipe'], 403);
        }

        $isFavorited = $user->favoriteRecipes()->where('recipe_id', $recipe->id)->exists();
        if ($isFavorited) {
            $user->favoriteRecipes()->detach($recipe->id);
        } else {
            $user->favoriteRecipes()->attach($recipe->id);
        }

        return response()->json(['favorited' => !$isFavorited]);
    }


    public function favorites()
    {
        $recipes = Auth::user()->favoriteRecipes()->where('is_published', true)->get();
        return view('recipes.favorites', compact('recipes'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $recipes = Recipe::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();
        return view('recipes.search', compact('recipes', 'query'));
    }

    public function filter(Request $request)
    {
        $query = Recipe::query();
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        // Voeg meer filters toe indien nodig
        $recipes = $query->get();
        return view('recipes.index', compact('recipes'));
    }


}
