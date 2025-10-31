@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-6">
            <a href="{{ route('recipes.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Nieuw Recept Toevoegen</a>
        </div>
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Mijn Recepten</h1>
        <details>
            <summary>Filteren</summary>
            <div>
                <form id="filter-form" method="GET" action="{{ route('recipes.my') }}"
                      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gepubliceerd van:</label>
                        <input type="date" name="published_start" value="{{ $publishedStart }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gepubliceerd tot:</label>
                        <input type="date" name="published_end" value="{{ $publishedEnd }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bijgewerkt van:</label>
                        <input type="date" name="updated_start" value="{{ $updatedStart }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bijgewerkt tot:</label>
                        <input type="date" name="updated_end" value="{{ $updatedEnd }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sortering:</label>
                        <select name="sort_order"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="newest" {{ $sortOrder === 'newest' ? 'selected' : '' }}>Nieuw naar oud</option>
                            <option value="oldest" {{ $sortOrder === 'oldest' ? 'selected' : '' }}>Oud naar nieuw</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximale totale tijd:</label>
                        <select name="total_time_max"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="" {{ !$totalTimeMax ? 'selected' : '' }}>Alle</option>
                            <option value="10" {{ $totalTimeMax == 10 ? 'selected' : '' }}>10 minuten</option>
                            <option value="20" {{ $totalTimeMax == 20 ? 'selected' : '' }}>20 minuten</option>
                            <option value="30" {{ $totalTimeMax == 30 ? 'selected' : '' }}>30 minuten</option>
                            <option value="45" {{ $totalTimeMax == 45 ? 'selected' : '' }}>45 minuten</option>
                            <option value="60" {{ $totalTimeMax == 60 ? 'selected' : '' }}>1 uur</option>
                            <option value="90" {{ $totalTimeMax == 90 ? 'selected' : '' }}>Anderhalf uur</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('recipes.my') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Reset</a>
                    </div>
                </form>
            </div>
        </details>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <div class="cookbook-card rounded-lg shadow-md overflow-hidden">
                    @if($recipe->image_url)
                        <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="cookbook-text">Geen afbeelding</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2 cookbook-header">{{ $recipe->title }}</h2>
                        <p class="cookbook-text">
                            Tijd: {{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} minuten
                        </p>
                        <div class="flex items-center justify-between mt-4">
                            <a href="{{ route('recipes.show', $recipe) }}" class="bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700">Bekijk recept</a>
                            <a href="{{ route('recipes.edit', $recipe) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Bewerk recept</a>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only toggle-published" data-recipe-id="{{ $recipe->id }}" {{ $recipe->is_published ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-md shadow-inner transition-colors duration-300 h-10 w-40 {{ $recipe->is_published ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center text-white font-semibold">
                                    <span class="toggle-text">{{ $recipe->is_published ? 'Gepubliceerd' : 'Concept' }}</span>
                                </div>
                                <div class="dot absolute w-6 h-6 bg-white rounded-full shadow transition-transform duration-300 {{ $recipe->is_published ? 'translate-x-[8.25rem]' : 'translate-x-1' }}"></div>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $recipes->links() }}
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('filter-form');
        const grid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
        const paginationContainer = document.querySelector('.pagination-container') || document.createElement('div');

        const fetchRecipes = () => {
            const params = new URLSearchParams(new FormData(form));
            fetch(`{{ route('recipes.my') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    grid.innerHTML = '';
                    if (data.recipes.length === 0) {
                        grid.innerHTML = '<p class="cookbook-text">Je hebt nog geen recepten aangemaakt.</p>';
                    } else {
                        data.recipes.forEach(recipe => {
                            const card = `
                        <div class="cookbook-card rounded-lg shadow-md overflow-hidden">
                            ${recipe.image_url ? `<img src="/storage/${recipe.image_url}" alt="${recipe.title}" class="w-full h-48 object-cover">` : '<div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="cookbook-text">Geen afbeelding</span></div>'}
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2 cookbook-header">${recipe.title}</h2>
                                <p class="cookbook-text">Tijd: ${(recipe.prep_time || 0) + (recipe.cook_time || 0)} minuten</p>
                                <div class="flex items-center justify-between mt-4">
                                    <a href="/recipes/${recipe.id}" class="bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700">Bekijk recept</a>
                                    <a href="/recipes/${recipe.id}/edit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Bewerk recept</a>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only toggle-published" data-recipe-id="${recipe.id}" ${recipe.is_published ? 'checked' : ''}>
                                        <div class="px-4 py-2 rounded-md shadow-inner transition-colors duration-300 h-10 w-40 ${recipe.is_published ? 'bg-green-500' : 'bg-red-500'} flex items-center justify-center text-white font-semibold">
                                            <span class="toggle-text">${recipe.is_published ? 'Gepubliceerd' : 'Concept'}</span>
                                        </div>
                                        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow transition-transform duration-300 ${recipe.is_published ? 'translate-x-[8.25rem]' : 'translate-x-1'}"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                            grid.insertAdjacentHTML('beforeend', card);
                        });
                    }
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                })
                .catch(error => console.error('Error:', error));
        };

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
            });

            form.querySelectorAll('input, select').forEach(el => {
                el.addEventListener('input', fetchRecipes);
                el.addEventListener('change', fetchRecipes);
            });
        }

        // Reset button functionality
        const resetBtn = document.querySelector('a[href*="recipes.my"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', (e) => {
                e.preventDefault();
                form.reset();
                fetchRecipes();
            });
        }
    });
</script>
