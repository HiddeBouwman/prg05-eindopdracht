@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Nieuw Recept Toevoegen</h1>

        <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titel:</label>
                <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Beschrijving:</label>
                <textarea name="description" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" rows="3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Aantal personen:</label>
                <input type="number" name="servings" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" min="1" value="1">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Voorbereidingstijd (min):</label>
                    <input type="number" name="prep_time" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kooktijd (min):</label>
                    <input type="number" name="cook_time" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Afbeelding:</label>
                <input type="file" name="image" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>

            <h3 class="text-xl font-semibold mb-4">Ingrediënten</h3>
            <datalist id="ingredients-list">
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->name }}">
                @endforeach
            </datalist>
            <div id="ingredients" class="space-y-2 mb-4">
                <div class="ingredient-group flex space-x-2">
                    <input type="text" name="ingredients[0][name]" placeholder="Naam ingrediënt" list="ingredients-list" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                    <input type="number" step="0.1" name="ingredients[0][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <input type="text" name="ingredients[0][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <button type="button" onclick="addIngredient()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Ingrediënt</button>

            <h3 class="text-xl font-semibold mb-4">Benodigdheden</h3>
            <div id="equipment" class="space-y-2 mb-4">
                <div class="equipment-group flex space-x-2">
                    <input type="text" name="equipment[0][name]" placeholder="Bijv. blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <input type="text" name="equipment[0][quantity]" placeholder="Aantal/stuks" class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <button type="button" onclick="addEquipment()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Benodigdheid</button>

            <h3 class="text-xl font-semibold mb-4">Stappen</h3>
            <div id="steps" class="space-y-2 mb-4">
                <div class="step-group space-y-2">
                    <select name="steps[0][type]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="preparation">Voorbereiding</option>
                        <option value="cooking" selected>Koken</option>
                    </select>
                    <textarea name="steps[0][instruction]" placeholder="Wat moet er gebeuren?" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                    <input type="text" name="steps[0][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <button type="button" onclick="addStep()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Stap</button>

            <h3 class="text-xl font-semibold mb-4">Algemene Tips</h3>
            <div id="tips" class="space-y-2 mb-6">
                <div class="tip-group">
                    <input type="text" name="tips[0][text]" placeholder="Bijv. laat het even afkoelen..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <button type="button" onclick="addTip()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Tip</button>

            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Recept opslaan</button>
        </form>
    </div>

    <script>
        let ingredientIndex = 1;
        let equipmentIndex = 1;
        let stepIndex = 1;
        let tipIndex = 1;

        function addIngredient() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="ingredient-group flex space-x-2">
            <select name="ingredients[${ingredientIndex}][id]" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <option value="">-- Kies een ingrediënt --</option>
                @foreach($ingredients as $ingredient)
            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
            <input type="number" step="0.1" name="ingredients[${ingredientIndex}][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>`;
            document.getElementById('ingredients').appendChild(div);
            ingredientIndex++;
        }

        function addEquipment() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="equipment-group flex space-x-2">
            <input type="text" name="equipment[${equipmentIndex}][name]" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            <input type="text" name="equipment[${equipmentIndex}][quantity]" placeholder="Aantal/stuks" class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>`;
            document.getElementById('equipment').appendChild(div);
            equipmentIndex++;
        }

        function addStep() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="step-group space-y-2">
            <select name="steps[${stepIndex}][type]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <option value="preparation">Voorbereiding</option>
                <option value="cooking" selected>Koken</option>
            </select>
            <textarea name="steps[${stepIndex}][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
            <input type="text" name="steps[${stepIndex}][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>`;
            document.getElementById('steps').appendChild(div);
            stepIndex++;
        }

        function addTip() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="tip-group">
            <input type="text" name="tips[${tipIndex}][text]" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>`;
            document.getElementById('tips').appendChild(div);
            tipIndex++;
        }
    </script>
@endsection
