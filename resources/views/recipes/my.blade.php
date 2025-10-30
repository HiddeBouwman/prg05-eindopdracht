@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-6">
            <a href="{{ route('recipes.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Nieuw Recept Toevoegen</a>
        </div>
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Mijn Recepten</h1>

        @if($recipes->isEmpty())
            <p class="cookbook-text">Je hebt nog geen recepten aangemaakt.</p>
        @else
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
        @endif
    </div>

    <script>
        document.querySelectorAll('.toggle-published').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const recipeId = this.dataset.recipeId;
                const toggleDiv = this.nextElementSibling;
                const toggleText = toggleDiv.querySelector('.toggle-text');
                const dot = toggleDiv.nextElementSibling;

                fetch(`/profile/recipes/${recipeId}/toggle-published`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_published) {
                            toggleDiv.classList.remove('bg-red-500');
                            toggleDiv.classList.add('bg-green-500');
                            toggleText.textContent = 'Gepubliceerd';
                            dot.classList.remove('translate-x-1');
                            dot.classList.add('translate-x-[8.25rem]');
                        } else {
                            toggleDiv.classList.remove('bg-green-500');
                            toggleDiv.classList.add('bg-red-500');
                            toggleText.textContent = 'Concept';
                            dot.classList.remove('translate-x-[8.25rem]');
                            dot.classList.add('translate-x-1');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
