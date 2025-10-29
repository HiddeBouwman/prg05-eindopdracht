@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Recepten</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <a href="{{ route('recipes.show', $recipe) }}" class="block cookbook-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($recipe->image_url)
                        <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}" class="w-full h-64 object-cover">
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

        <div class="pagination-container mt-6">
            {{ $recipes->links() }}
        </div>
    </div>
@endsection
