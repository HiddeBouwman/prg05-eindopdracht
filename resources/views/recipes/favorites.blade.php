@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <details>
            <summary>Filteren</summary>
            <div>
                <!-- Filter Bar -->
                <div
                    class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6 transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                    <form id="filter-form" method="GET" action="{{ route('recipes.favorites') }}"
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
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Favoriete Recepten</h1>
        @if($recipes->isEmpty())
            <p class="cookbook-text">Je hebt nog geen favoriete recepten.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filter-form');
            const grid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
            const paginationContainer = document.querySelector('.pagination-container');

            const update = () => {
                const params = new URLSearchParams(new FormData(form));
                fetch(`{{ route('recipes.favorites') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (grid) {
                        grid.innerHTML = '';
                        if (data.recipes.length === 0) {
                            grid.innerHTML = '<p class="cookbook-text">Je hebt nog geen favoriete recepten.</p>';
                        } else {
                            data.recipes.forEach(recipe => {
                                const card = `
<a href="/recipes/${recipe.id}" class="block cookbook-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    ${recipe.image_url ? `<img src="/storage/${recipe.image_url}" alt="${recipe.title}" class="w-full h-64 object-cover">` : '<div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="cookbook-text">Geen afbeelding</span></div>'}
    <div class="p-4">
        <h2 class="text-2xl font-bold cookbook-header">${recipe.title}</h2>
        <p class="cookbook-text">Tijd: ${(recipe.prep_time || 0) + (recipe.cook_time || 0)} minuten</p>
    </div>
</a>`;
                                grid.insertAdjacentHTML('beforeend', card);
                            });
                        }
                    }
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                })
                .catch(console.error);
            };

            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    update();
                });

                form.querySelectorAll('input, select').forEach(el => {
                    const evt = (el.tagName === 'INPUT' && ['text','number','date'].includes(el.type)) ? 'input' : 'change';
                    el.addEventListener(evt, update);
                });
            }

            // Reset button functionality
            const resetButton = document.getElementById('reset-btn');
            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    form.reset();
                    update();
                });
            }
        });
    </script>
@endsection
