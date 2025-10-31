@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <details>
            <summary>Filteren</summary>
            <div>
                <!-- Filter Bar -->
                <div
                    class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6 transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                    <form id="filter-form" method="GET" action="{{ route('recipes.index') }}"
                          class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Publication Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gepubliceerd
                                van:</label>
                            <input type="date" name="published_start" value="{{ $publishedStart }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gepubliceerd
                                tot:</label>
                            <input type="date" name="published_end" value="{{ $publishedEnd }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Update Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bijgewerkt
                                van:</label>
                            <input type="date" name="updated_start" value="{{ $updatedStart }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bijgewerkt
                                tot:</label>
                            <input type="date" name="updated_end" value="{{ $updatedEnd }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Sort Order Toggle -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sortering:</label>
                            <select name="sort_order"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="newest" {{ $sortOrder === 'newest' ? 'selected' : '' }}>Nieuw naar oud
                                </option>
                                <option value="oldest" {{ $sortOrder === 'oldest' ? 'selected' : '' }}>Oud naar nieuw
                                </option>
                            </select>
                        </div>

                        <!-- Total Time Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximale
                                totale tijd:</label>
                            <input type="number" name="total_time_max" value="{{ $totalTimeMax }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <a href="{{ route('recipes.index') }}"
                               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </details>
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Recepten</h1>
        @if($recipes->isEmpty())
            <p class="cookbook-text">Geen recepten gevonden.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recipes as $recipe)
                    <a href="{{ route('recipes.show', $recipe) }}"
                       class="block cookbook-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($recipe->image_url)
                            <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}"
                                 class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="cookbook-text">Geen afbeelding</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="text-2xl font-bold cookbook-header">{{ $recipe->title }}</h2>
                            <p class="cookbook-text">
                                Tijd: {{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} minuten
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="pagination-container mt-6">
            {{ $recipes->links() }}
        </div>
    </div>
@endsection
<!-- Het grootste deel van het onderstaande script is met AI gegenereerd, ik kwam er echt niet uit -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('filter-form');
        const grid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
        const paginationContainer = document.querySelector('.pagination-container');

        const fetchRecipes = () => {
            const params = new URLSearchParams(new FormData(form));
            fetch(`{{ route('recipes.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Update grid
                    grid.innerHTML = '';
                    if (data.recipes.length === 0) {
                        grid.innerHTML = '<p class="cookbook-text">Geen recepten gevonden.</p>';
                    } else {
                        data.recipes.forEach(recipe => {
                            const card = `
                        <a href="/recipes/${recipe.id}" class="block cookbook-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            ${recipe.image_url ? `<img src="/storage/${recipe.image_url}" alt="${recipe.title}" class="w-full h-64 object-cover">` : '<div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="cookbook-text">Geen afbeelding</span></div>'}
                            <div class="p-4">
                                <h2 class="text-2xl font-bold cookbook-header">${recipe.title}</h2>
                                <p class="cookbook-text">Tijd: ${(recipe.prep_time || 0) + (recipe.cook_time || 0)} minuten</p>
                            </div>
                        </a>
                    `;
                            grid.insertAdjacentHTML('beforeend', card);
                        });
                    }
                    // Update pagination
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                })
                .catch(error => console.error('Error:', error));
        };

        // Prevent form submit
        form.addEventListener('submit', (e) => {
            e.preventDefault();
        });

        // Trigger fetch on input/change
        form.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', fetchRecipes);
            el.addEventListener('change', fetchRecipes);
        });
    });
</script>
