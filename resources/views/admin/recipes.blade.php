@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Recepten beheren</h1>
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
