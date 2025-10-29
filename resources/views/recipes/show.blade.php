@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 cookbook-card p-6 rounded-lg">
        <h1 class="text-5xl font-bold mb-4 cookbook-header text-center">{{ $recipe->title }}</h1>

        @if($recipe->image_url)
            <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
        @endif

        <p class="cookbook-text mb-4 text-center">{{ $recipe->description }}</p>

        <div class="flex flex-col items-center space-y-2 mb-6 text-xl">
            <div>
                <strong class="cookbook-header">Voorbereidingstijd:</strong> {{ $recipe->prep_time ?? 0 }} minuten
            </div>
            <div>
                <strong class="cookbook-header">Kooktijd:</strong> {{ $recipe->cook_time ?? 0 }} minuten
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-1">
                <div class="text-center mb-4 text-xl">
                    <strong class="cookbook-header">Aantal personen:</strong>
                    <div class="flex items-center justify-center space-x-2">
                        <button id="decrease-servings" class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                        <span id="servings-display">{{ $recipe->servings }}</span>
                        <button id="increase-servings" class="bg-green-500 text-white px-2 py-1 rounded">+</button>
                    </div>
                </div>

                <div class="bg-purple-100 p-4 rounded-lg mb-4">
                    <h2 class="text-2xl font-semibold mb-4 cookbook-header text-center">Ingrediënten</h2>
                    <ul class="list-disc list-inside cookbook-text" id="ingredients-list">
                        @foreach($recipe->ingredients as $ingredient)
                            <li data-original-amount="{{ $ingredient->pivot->amount }}" data-unit="{{ $ingredient->pivot->unit }}">
                                <span class="amount-display">{{ $ingredient->pivot->amount }}</span>
                                <span class="unit-display">{{ $ingredient->pivot->unit }}</span>
                                {{ $ingredient->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <h2 class="text-2xl font-semibold mb-4 cookbook-header text-center">Benodigdheden</h2>
                    <ul class="list-disc list-inside cookbook-text">
                        @foreach($recipe->equipment as $item)
                            <li>{{ $item->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="md:col-span-3">
                <div class="bg-orange-100 p-4 rounded-lg mb-4">
                    <h2 class="text-2xl font-semibold mb-4 cookbook-header text-center">Bereiding</h2>
                    @foreach($recipe->steps as $step)
                        <div class="flex items-start space-x-4 mb-4 bg-white p-3 rounded shadow-sm">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ $step->step_number }}
                            </div>
                            <div class="cookbook-text">
                                {{ $step->instruction }}
                                @if($step->tip)
                                    <em class="text-pink-600">Tip: {{ $step->tip }}</em>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-red-100 p-4 rounded-lg">
                    <h2 class="text-2xl font-semibold mb-4 cookbook-header text-center">Tips</h2>
                    <ul class="list-disc list-inside cookbook-text">
                        @foreach($recipe->tips as $tip)
                            <li>{{ $tip->tip }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center">
            <p>Geschreven door {{ $recipe->user->name }}</p>
            <p class="text-sm">Gepubliceerd op {{ $recipe->created_at }} | Bijgewerkt op {{ $recipe->updated_at }}</p>
        </div>
        <a href="{{ route('recipes.index') }}" class="mt-6 inline-block bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700">Terug naar recepten</a>

        @if(Auth::check() && Auth::id() === $recipe->user_id)
            <a href="{{ route('recipes.edit', $recipe) }}" class="mt-6 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 ml-4">Bewerk recept</a>
        @endif
    </div>

    <script>
        const originalServings = {{ $recipe->servings }};
        let currentServings = originalServings;

        // dit hele stuk hier werkt niet eens
        function normalizeUnit(unit) {
            const lowerUnit = unit.toLowerCase();
            if (['kg', 'kilogram'].includes(lowerUnit)) return 'Kg';
            if (['l', 'liter'].includes(lowerUnit)) return 'Liter';
            if (lowerUnit === 'ml') return 'milliliter';
            if (lowerUnit === 'g') return 'gram';
            return unit;
        }

        function updateIngredients() {
            const scale = currentServings / originalServings;
            document.querySelectorAll('#ingredients-list li').forEach(li => {
                const originalAmount = parseFloat(li.dataset.originalAmount);
                if (!isNaN(originalAmount)) {
                    let newAmount = originalAmount * scale;
                    let unit = li.dataset.unit || '';

                    // Unit conversions
                    if (unit === 'kg' && newAmount < 1) {
                        newAmount *= 1000;
                        unit = 'g';
                    } else if (unit === 'l' && newAmount < 1) {
                        newAmount *= 1000;
                        unit = 'ml';
                    } else if (unit === 'g' && newAmount >= 1000) {
                        newAmount /= 1000;
                        unit = 'kg';
                    } else if (unit === 'ml' && newAmount >= 1000) {
                        newAmount /= 1000;
                        unit = 'l';
                    }

                    // Normalize unit
                    unit = normalizeUnit(unit);

                    // Format as fraction if needed
                    const fractionChars = ['½', '⅓', '⅔', '¼', '¾', '⅕', '⅖', '⅗', '⅘', '⅙', '⅚', '⅛', '⅜', '⅝', '⅞'];
                    const fractions = [0.5, 1/3, 2/3, 0.25, 0.75, 0.2, 0.4, 0.6, 0.8, 1/6, 5/6, 0.125, 0.375, 0.625, 0.875];
                    let displayAmount = '';
                    let foundFraction = false;
                    for (let i = 0; i < fractions.length; i++) {
                        if (Math.abs(newAmount - fractions[i]) < 0.01) {
                            displayAmount = fractionChars[i];
                            foundFraction = true;
                            break;
                        }
                    }
                    if (!foundFraction) {
                        displayAmount = newAmount % 1 === 0 ? newAmount.toString() : newAmount.toFixed(2);
                    }

                    li.querySelector('.amount-display').textContent = displayAmount;
                    li.querySelector('.unit-display').textContent = unit;
                }
            });
        }

        document.getElementById('decrease-servings').addEventListener('click', () => {
            if (currentServings > 1) {
                currentServings--;
                document.getElementById('servings-display').textContent = currentServings;
                updateIngredients();
            }
        });

        document.getElementById('increase-servings').addEventListener('click', () => {
            currentServings++;
            document.getElementById('servings-display').textContent = currentServings;
            updateIngredients();
        });
    </script>
@endsection
