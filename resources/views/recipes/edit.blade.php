@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 cookbook-card p-6 rounded-lg">
        <h1 class="text-3xl font-bold mb-6 cookbook-header text-center">Recept Bewerken</h1>

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Titel:</label>
                <input type="text" name="title" value="{{ old('title', $recipe->title) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Beschrijving:</label>
                <textarea name="description" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" rows="3">{{ old('description', $recipe->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Voorbereidingstijd (min):</label>
                    <input type="number" name="prep_time" value="{{ old('prep_time', $recipe->prep_time) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Kooktijd (min):</label>
                    <input type="number" name="cook_time" value="{{ old('cook_time', $recipe->cook_time) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Aantal personen:</label>
                <input type="number" name="servings" value="{{ old('servings', $recipe->servings) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" min="1">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Afbeelding:</label>
                <input type="file" name="image" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                @if($recipe->image_url)
                    <p class="mt-2">Huidige afbeelding: <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="Current image" class="w-32 h-32 object-cover"></p>
                @endif
            </div>

            <div class="bg-purple-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Ingrediënten</h3>
                <div id="ingredients" class="space-y-2 mb-4">
                    @foreach($recipe->ingredients as $index => $ingredient)
                        <div class="ingredient-group flex space-x-2">
                            <input type="text" name="ingredients[{{ $index }}][name]" value="{{ $ingredient->name }}" placeholder="Naam ingrediënt" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <input type="number" step="0.1" name="ingredients[{{ $index }}][amount]" value="{{ $ingredient->pivot->amount }}" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <input type="text" name="ingredients[{{ $index }}][unit]" value="{{ $ingredient->pivot->unit }}" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                        </div>
                    @endforeach
                    @if($recipe->ingredients->isEmpty())
                        <div class="ingredient-group flex space-x-2">
                            <input type="text" name="ingredients[0][name]" placeholder="Naam ingrediënt" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <input type="number" step="0.1" name="ingredients[0][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <input type="text" name="ingredients[0][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded" style="display: none;">-</button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addIngredient()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Ingrediënt</button>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Benodigdheden</h3>
                <div id="equipment" class="space-y-2 mb-4">
                    @foreach($recipe->equipment as $index => $item)
                        <div class="equipment-group flex space-x-2">
                            <input type="text" name="equipment[{{ $index }}][name]" value="{{ $item->name }}" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                        </div>
                    @endforeach
                    @if($recipe->equipment->isEmpty())
                        <div class="equipment-group flex space-x-2">
                            <input type="text" name="equipment[0][name]" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded" style="display: none;">-</button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addEquipment()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Benodigdheid</button>
            </div>

            <div class="bg-orange-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Stappen</h3>
                <div id="steps" class="space-y-4 mb-4">
                    @foreach($recipe->steps as $index => $step)
                        <div class="step-group space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Stap {{ $index + 1 }}</label>
                            <textarea name="steps[{{ $index }}][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">{{ $step->instruction }}</textarea>
                            <input type="text" name="steps[{{ $index }}][tip]" value="{{ $step->tip }}" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" style="font-style: italic; color: #ff69b4;">
                        </div>
                    @endforeach
                    @if($recipe->steps->isEmpty())
                        <div class="step-group space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Stap 1</label>
                            <textarea name="steps[0][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text"></textarea>
                            <input type="text" name="steps[0][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" style="font-style: italic; color: #ff69b4;">
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addStep()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-2">+ Stap</button>
                <button type="button" onclick="removeLastStep()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mb-6">Verwijder laatste stap</button>
            </div>

            <div class="bg-red-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Algemene Tips</h3>
                <div id="tips" class="space-y-2 mb-6">
                    @foreach($recipe->tips as $index => $tip)
                        <div class="tip-group">
                            <input type="text" name="tips[{{ $index }}][text]" value="{{ $tip->tip }}" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        </div>
                    @endforeach
                    @if($recipe->tips->isEmpty())
                        <div class="tip-group">
                            <input type="text" name="tips[0][text]" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addTip()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Tip</button>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Recept bijwerken</button>
        </form>

        <div class="mt-6">
            <form method="POST" action="{{ route('recipes.delete', $recipe) }}" onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?')">
                @csrf
                @method('DELETE')
                <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700">
                    Verwijder Recept
                </x-primary-button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addIngredient = function() {
                const container = document.getElementById('ingredients');
                const index = container.children.length;
                const newItem = document.createElement('div');
                newItem.className = 'ingredient-group flex space-x-2';
                newItem.innerHTML = `
                    <input type="text" name="ingredients[${index}][name]" placeholder="Naam ingrediënt" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                    <input type="number" step="0.1" name="ingredients[${index}][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                    <input type="text" name="ingredients[${index}][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                    <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                `;
                container.appendChild(newItem);
            };

            window.addEquipment = function() {
                const container = document.getElementById('equipment');
                const index = container.children.length;
                const newItem = document.createElement('div');
                newItem.className = 'equipment-group flex space-x-2';
                newItem.innerHTML = `
                    <input type="text" name="equipment[${index}][name]" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                    <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                `;
                container.appendChild(newItem);
            };

            window.addStep = function() {
                const container = document.getElementById('steps');
                const index = container.children.length;
                const newItem = document.createElement('div');
                newItem.className = 'step-group space-y-2';
                newItem.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Stap ${index + 1}</label>
                    <textarea name="steps[${index}][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text"></textarea>
                    <input type="text" name="steps[${index}][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" style="font-style: italic; color: #ff69b4;">
                `;
                container.appendChild(newItem);
            };

            window.removeLastStep = function() {
                const container = document.getElementById('steps');
                if (container.children.length > 0) {
                    container.removeChild(container.lastElementChild);
                }
            };

            window.addTip = function() {
                const container = document.getElementById('tips');
                const index = container.children.length;
                const newItem = document.createElement('div');
                newItem.className = 'tip-group';
                newItem.innerHTML = `
                    <input type="text" name="tips[${index}][text]" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                `;
                container.appendChild(newItem);
            };

            window.removeItem = function(button) {
                button.parentElement.remove();
            };
        });
    </script>

@endsection
