@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Recepten beheren</h1>
        <details>
            <summary>Filteren</summary>
            <div>
                <!-- Filter Bar -->
                <div
                    class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6 transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                    <form id="filter-form" method="GET" action="{{ route('admin.recipes') }}"
                          class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Publicatie Datum Filter -->
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

                        <!-- Update Datum Filter -->
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

                        <!-- Sorteren Toggle -->
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

                        <!-- Totale Tijd Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximale
                                totale tijd:</label>
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

                        <!-- Reset Knop -->
                        <div>
                            <button type="button" id="reset-btn" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </details>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <div class="cookbook-card p-6 rounded-lg bg-white shadow-md mb-4 flex flex-col items-center">
                    {{-- Recipe Photo --}}
                    <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}"
                         class="w-96 h-36 object-cover rounded-lg">

                    {{-- Recipe Details --}}
                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-center mt-2">
                            <a href="{{ route('recipes.show', $recipe->id) }}"
                               class="text-blue-600 hover:text-blue-800">
                                {{ $recipe->title }}
                            </a>
                        </h1>
                        <p class="text-gray-600">{{ Str::limit($recipe->description, 100) }}</p>
                    </div>
                    <p class="mt-2 mb-4">Status: {{ $recipe->is_published ? 'Gepubliceerd' : 'Concept' }}</p>

                    <form action="{{ route('admin.recipes.delete', $recipe) }}" method="POST"
                          onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Verwijderen</button>
                    </form>
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
        const paginationContainer = document.querySelector('.pagination-container') || document.createElement('div'); // Assuming pagination is after grid

        const fetchRecipes = () => {
            const params = new URLSearchParams(new FormData(form));
            fetch(`{{ route('admin.recipes') }}?${params.toString()}`, {
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
                        <div class="cookbook-card p-6 rounded-lg bg-white shadow-md mb-4 flex flex-col items-center">
                            <img src="/storage/${recipe.image_url}" alt="${recipe.title}" class="w-96 h-36 object-cover rounded-lg">
                            <div class="flex-1">
                                <h1 class="text-2xl font-semibold text-center mt-2">
                                    <a href="/recipes/${recipe.id}" class="text-blue-600 hover:text-blue-800">${recipe.title}</a>
                                </h1>
                                <p class="text-gray-600">${recipe.description ? recipe.description.substring(0, 100) : ''}</p>
                            </div>
                            <p class="mt-2 mb-4">Status: ${recipe.is_published ? 'Gepubliceerd' : 'Concept'}</p>
                            <form action="/admin/recipes/${recipe.id}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Verwijderen</button>
                            </form>
                        </div>
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

        // Reset button functionality
        document.getElementById('reset-btn').addEventListener('click', () => {
            form.reset();
            fetchRecipes();
        });
    });
</script>
