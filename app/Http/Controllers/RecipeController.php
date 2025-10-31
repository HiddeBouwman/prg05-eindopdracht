<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $publishedStart = $request->input('published_start');
        $publishedEnd = $request->input('published_end');
        $updatedStart = $request->input('updated_start');
        $updatedEnd = $request->input('updated_end');
        $sortOrder = $request->input('sort_order', 'newest'); // Default to newest
        $totalTimeMax = $request->input('total_time_max');

        $recipes = Recipe::where('is_published', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhereHas('ingredients', function ($subSubQ) use ($query) {
                            $subSubQ->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('user', function ($subSubQ) use ($query) {
                            $subSubQ->where('name', '=', $query);
                        });
                });
            })
            ->when($publishedStart, function ($q) use ($publishedStart){
                $q->where('created_at', '>=', $publishedStart);
            })
            ->when($publishedEnd, function ($q) use ($publishedEnd){
                $q->where('created_at', '<=', $publishedEnd . ' 23:59:59');
            })
            ->when($updatedStart, function ($q) use ($updatedStart){
                $q->where('updated_at', '>=', $updatedStart);
            })
            ->when($updatedEnd, function ($q) use ($updatedEnd){
                $q->where('updated_at', '<=', $updatedEnd . ' 23:59:59');
            })
            ->when($request->filled('total_time_max'), function ($q) use ($request) {
                $totalTimeMax = (int) $request->input('total_time_max');
                $q->whereRaw('(COALESCE(prep_time, 0) + COALESCE(cook_time, 0)) <= ?', [$totalTimeMax]);
            })
            ->orderBy('created_at', $sortOrder === 'oldest' ? 'asc' : 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'recipes' => $recipes->items(),
                'pagination' => $recipes->links()->toHtml(),
            ]);
        }

        return view('recipes.index', compact('recipes', 'query', 'publishedStart', 'publishedEnd', 'updatedStart', 'updatedEnd', 'sortOrder', 'totalTimeMax'));
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

    public function myRecipes(Request $request)
    {
        $query = $request->input('search');
        $publishedStart = $request->input('published_start');
        $publishedEnd = $request->input('published_end');
        $updatedStart = $request->input('updated_start');
        $updatedEnd = $request->input('updated_end');
        $sortOrder = $request->input('sort_order', 'newest');
        $totalTimeMax = $request->input('total_time_max');

        $recipes = Auth::user()->recipes()
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->when($publishedStart, function ($q) use ($publishedStart){
                $q->where('created_at', '>=', $publishedStart);
            })
            ->when($publishedEnd, function ($q) use ($publishedEnd){
                $q->where('created_at', '<=', $publishedEnd . ' 23:59:59');
            })
            ->when($updatedStart, function ($q) use ($updatedStart){
                $q->where('updated_at', '>=', $updatedStart);
            })
            ->when($updatedEnd, function ($q) use ($updatedEnd){
                $q->where('updated_at', '<=', $updatedEnd . ' 23:59:59');
            })
            ->when($request->filled('total_time_max'), function ($q) use ($request) {
                $totalTimeMax = (int) $request->input('total_time_max');
                $q->whereRaw('(COALESCE(prep_time, 0) + COALESCE(cook_time, 0)) <= ?', [$totalTimeMax]);
            })
            ->orderBy('created_at', $sortOrder === 'oldest' ? 'asc' : 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'recipes' => $recipes->items(),
                'pagination' => $recipes->links()->toHtml(),
            ]);
        }

        return view('recipes.my', compact('recipes', 'query', 'publishedStart', 'publishedEnd', 'updatedStart', 'updatedEnd', 'sortOrder', 'totalTimeMax'));
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

        return response()->json(['favorited' => !$isFavorited, 'favoriteCount' => $recipe->favoritedBy()->count()]);
    }


    public function favorites(Request $request)
    {
        $query = $request->input('search');
        $publishedStart = $request->input('published_start');
        $publishedEnd = $request->input('published_end');
        $updatedStart = $request->input('updated_start');
        $updatedEnd = $request->input('updated_end');
        $sortOrder = $request->input('sort_order', 'newest'); // Default to newest
        $totalTimeMax = $request->input('total_time_max');

        $recipes = Auth::user()->favoriteRecipes()
            ->where('is_published', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhereHas('ingredients', function ($subSubQ) use ($query) {
                            $subSubQ->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('user', function ($subSubQ) use ($query) {
                            $subSubQ->where('name', '=', $query);
                        });
                });
            })
            ->when($publishedStart || $publishedEnd, function ($q) use ($publishedStart, $publishedEnd) {
                if ($publishedStart && $publishedEnd) {
                    $q->whereBetween('created_at', [$publishedStart, $publishedEnd . ' 23:59:59']);
                } elseif ($publishedStart) {
                    $q->where('created_at', '>=', $publishedStart);
                } elseif ($publishedEnd) {
                    $q->where('created_at', '<=', $publishedEnd . ' 23:59:59');
                }
            })
            ->when($updatedStart || $updatedEnd, function ($q) use ($updatedStart, $updatedEnd) {
                if ($updatedStart && $updatedEnd) {
                    $q->whereBetween('updated_at', [$updatedStart, $updatedEnd . ' 23:59:59']);
                } elseif ($updatedStart) {
                    $q->where('updated_at', '>=', $updatedStart);
                } elseif ($updatedEnd) {
                    $q->where('updated_at', '<=', $updatedEnd . ' 23:59:59');
                }
            })
            ->when($request->filled('total_time_max'), function ($q) use ($request) {
                $totalTimeMax = (int) $request->input('total_time_max');
                $q->whereRaw('(COALESCE(prep_time, 0) + COALESCE(cook_time, 0)) <= ?', [$totalTimeMax]);
            })
            ->orderBy('created_at', $sortOrder === 'oldest' ? 'asc' : 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'recipes' => $recipes->items(),
                'pagination' => $recipes->links()->toHtml(),
            ]);
        }

        return view('recipes.favorites', compact('recipes', 'query', 'publishedStart', 'publishedEnd', 'updatedStart', 'updatedEnd', 'sortOrder', 'totalTimeMax'));
    }


    public function rate(Request $request, Recipe $recipe)
    {
        \Log::info('Rate method called', ['user' => auth()->id(), 'recipe' => $recipe->id, 'rating' => $request->rating]);

        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
        ]);

        if ($recipe->user_id === auth()->id()) {
            return response()->json(['error' => 'You cannot rate your own recipe'], 403);
        }

        if (!auth()->user()->is_admin) {
            $loginDays = DB::table('sessions')->where('user_id', auth()->id())->selectRaw('DATE(last_activity) as date')->distinct()->count();
            if ($loginDays < 5) {
                return response()->json(['error' => 'Je moet minimaal 5 verschillende dagen hebben ingelogd om te kunnen beoordelen.'], 403);
            }
        }

        $rating = Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'recipe_id' => $recipe->id],
            ['rating' => $request->rating]
        );

        \Log::info('Rating saved', ['rating_id' => $rating->id, 'rating_value' => $rating->rating]);

        return response()->json(['success' => 'Rating saved', 'average' => $recipe->fresh()->averageRating()]);
    }

}
