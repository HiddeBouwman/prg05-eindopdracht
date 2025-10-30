@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 cookbook-card p-6 rounded-lg">
        <h1 class="text-3xl font-bold mb-6 cookbook-header text-center">Nieuw Recept Toevoegen</h1>

        <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Titel:</label>
                <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Beschrijving:</label>
                <textarea name="description" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" rows="3"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Voorbereidingstijd (min):</label>
                    <input type="number" name="prep_time" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Kooktijd (min):</label>
                    <input type="number" name="cook_time" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Aantal personen:</label>
                <input type="number" name="servings" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text" min="1" value="1">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Afbeelding:</label>
                <input type="file" name="image" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
            </div>

            <div class="bg-purple-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Ingrediënten</h3>
                <div id="ingredients" class="space-y-2 mb-4">
                    <div class="ingredient-group flex space-x-2">
                        <input type="text" name="ingredients[0][name]" placeholder="Naam ingrediënt" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        <input type="number" step="0.1" name="ingredients[0][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        <input type="text" name="ingredients[0][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded" style="display: none;">-</button>
                    </div>
                </div>
                <button type="button" onclick="addIngredient()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Ingrediënt</button>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Benodigdheden</h3>
                <div id="equipment" class="space-y-2 mb-4">
                    <div class="equipment-group flex space-x-2">
                        <input type="text" name="equipment[0][name]" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
                        <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded" style="display: none;">-</button>
                    </div>
                </div>
                <button type="button" onclick="addEquipment()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Benodigdheid</button>
            </div>

            <div class="bg-orange-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Stappen</h3>
                <div id="steps" class="space-y-4 mb-4">
                    <div class="step-group space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Stap 1</label>
                        <textarea name="steps[0][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text"></textarea>
                        <input type="text" name="steps[0][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" style="font-style: italic; color: #ff69b4;">
                    </div>
                </div>
                <button type="button" onclick="addStep()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-2">+ Stap</button>
                <button type="button" onclick="removeLastStep()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mb-6">Verwijder laatste stap</button>
            </div>

            <div class="bg-red-100 p-4 rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-4 cookbook-header text-center">Algemene Tips</h3>
                <div id="tips" class="space-y-2 mb-6">
                    <div class="tip-group">
                        <input type="text" name="tips[0][text]" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                <button type="button" onclick="addTip()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-6">+ Tip</button>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Recept opslaan</button>
        </form>
    </div>

    <script>
        let ingredientIndex = 1;
        let equipmentIndex = 1;
        let stepIndex = 1;
        let tipIndex = 1;

        function updateMinusButtons(containerId) {
            const container = document.getElementById(containerId);
            const groups = container.querySelectorAll('.ingredient-group, .equipment-group');
            groups.forEach(group => {
                const minusBtn = group.querySelector('button[onclick="removeItem(this)"]');
                if (minusBtn) {
                    minusBtn.style.display = groups.length > 1 ? 'block' : 'none';
                }
            });
        }

        function removeItem(button) {
            const containerId = button.closest('#ingredients') ? 'ingredients' : 'equipment';
            button.parentElement.remove();
            updateMinusButtons(containerId);
        }

        function addIngredient() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="ingredient-group flex space-x-2">
            <input type="text" name="ingredients[${ingredientIndex}][name]" placeholder="Naam ingrediënt" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
            <input type="number" step="0.1" name="ingredients[${ingredientIndex}][amount]" placeholder="Hoeveelheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
            <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="Eenheid" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
        </div>`;
            document.getElementById('ingredients').appendChild(div);
            ingredientIndex++;
            updateMinusButtons('ingredients');
        }

        function addEquipment() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="equipment-group flex space-x-2">
            <input type="text" name="equipment[${equipmentIndex}][name]" placeholder="Bijv. pan, blender" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
            <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
        </div>`;
            document.getElementById('equipment').appendChild(div);
            equipmentIndex++;
            updateMinusButtons('equipment');
        }

        function addStep() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="step-group space-y-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cookbook-header">Stap ${stepIndex + 1}</label>
            <textarea name="steps[${stepIndex}][instruction]" rows="2" placeholder="Wat moet er gebeuren?" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text"></textarea>
            <input type="text" name="steps[${stepIndex}][tip]" placeholder="Optionele tip" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" style="font-style: italic; color: #ff69b4;">
        </div>`;
            document.getElementById('steps').appendChild(div);
            stepIndex++;
        }

        function removeLastStep() {
            const stepsContainer = document.getElementById('steps');
            const stepGroups = stepsContainer.querySelectorAll('.step-group');
            if (stepGroups.length > 1) { // Prevent removing the initial step
                const lastStep = stepGroups[stepGroups.length - 1];
                const instruction = lastStep.querySelector('textarea').value.trim();
                const tip = lastStep.querySelector('input[type="text"]').value.trim();
                if (instruction || tip) {
                    if (confirm('Deze stap bevat ingevulde informatie. Weet je zeker dat je deze wilt verwijderen?')) {
                        lastStep.remove();
                        stepIndex--;
                    }
                } else {
                    lastStep.remove();
                    stepIndex--;
                }
            }
        }

        function addTip() {
            const div = document.createElement('div');
            div.innerHTML = `
        <div class="tip-group">
            <input type="text" name="tips[${tipIndex}][text]" placeholder="Bijv. serveer direct..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white cookbook-text">
        </div>`;
            document.getElementById('tips').appendChild(div);
            tipIndex++;
        }
    </script>
@endsection
