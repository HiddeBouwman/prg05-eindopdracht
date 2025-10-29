@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 cookbook-header">Favoriete Recepten</h1>

        @if($recipes->isEmpty())
            <p class="cookbook-text">Je hebt nog geen favoriete recepten.</p>
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
                            <a href="{{ route('recipes.show', $recipe) }}" class="mt-4 inline-block bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700">Bekijk recept</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
