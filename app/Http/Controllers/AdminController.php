<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    public function recipes(Request $request)
    {
        $query = $request->input('search');
        $publishedStart = $request->input('published_start');
        $publishedEnd = $request->input('published_end');
        $updatedStart = $request->input('updated_start');
        $updatedEnd = $request->input('updated_end');
        $sortOrder = $request->input('sort_order', 'newest');
        $totalTimeMax = $request->input('total_time_max');

        $recipes = Recipe::with('user')
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
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'recipes' => $recipes->items(),
                'pagination' => $recipes->links()->toHtml(),
            ]);
        }

        return view('admin.recipes', compact('recipes', 'query', 'publishedStart', 'publishedEnd', 'updatedStart', 'updatedEnd', 'sortOrder', 'totalTimeMax'));
    }

    public function deleteRecipe(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('admin.recipes')->with('success', 'Recipe deleted.');
    }
}
