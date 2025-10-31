@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 cookbook-card p-6 rounded-lg">
        <div class="relative">
            <h1 class="text-5xl font-bold mb-4 cookbook-header text-center">{{ $recipe->title }}</h1>
            @auth
                <div class="absolute top-0 right-0 flex flex-col items-center">
                    <button id="favorite-btn"
                            class="text-3xl {{ Auth::user()->favoriteRecipes()->where('recipe_id', $recipe->id)->exists() ? 'text-red-500' : 'text-gray-400' }} {{ Auth::id() === $recipe->user_id ? 'cursor-not-allowed opacity-50' : 'hover:text-red-500' }} transition-colors"
                            {{ Auth::id() === $recipe->user_id ? 'disabled' : '' }}>
                        <i class="fas fa-heart" id="heart-icon"></i>
                    </button>
                    <span id="favorite-count" class="text-sm text-gray-600">{{ $recipe->favoritedBy()->count() }}</span>
                </div>
            @endauth
        </div>

        @if($recipe->image_url)
            <img src="{{ asset('storage/' . $recipe->image_url) }}" alt="{{ $recipe->title }}"
                 class="w-full h-64 object-cover rounded-lg mb-6">
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

        @auth
            @if(Auth::id() !== $recipe->user_id)
                <div class="flex flex-col items-center space-y-2 mb-6">
                    <label class="cookbook-header text-xl">Beoordeling: {{ number_format($recipe->averageRating(), 1) }}</label>
                    <form id="rating-form" method="POST" action="{{ route('recipes.rate', $recipe) }}">
                        @csrf
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star" data-rating="{{ $i }}">
                                    @if($recipe->averageRating() >= $i)
                                        <i class="fas fa-star text-yellow-500"></i>
                                    @elseif($recipe->averageRating() >= $i - 0.5)
                                        <i class="fas fa-star-half-alt text-yellow-500"></i>
                                    @else
                                        <i class="far fa-star text-gray-400"></i>
                                    @endif
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="{{ Auth::user()->ratings()->where('recipe_id', $recipe->id)->first()->rating ?? 0 }}">
                    </form>
                </div>
            @else
                <div class="flex flex-col items-center space-y-2 mb-6">
                    <label class="cookbook-header text-xl">Rating: {{ $recipe->averageRating() ? number_format($recipe->averageRating(), 1) : 'n/a' }}</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($recipe->averageRating() >= $i)
                                <i class="fas fa-star text-yellow-500"></i>
                            @elseif($recipe->averageRating() >= $i - 0.5)
                                <i class="fas fa-star-half-alt text-yellow-500"></i>
                            @else
                                <i class="far fa-star text-gray-400"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif
        @else
            <div class="flex flex-col items-center space-y-2 mb-6">
                <label class="cookbook-header text-xl">Beoordeling: {{ $recipe->averageRating() !== null ? number_format($recipe->averageRating(), 1) : 'n/a' }}</label>
                <div class="flex space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                        @if($recipe->averageRating() >= $i)
                            <i class="fas fa-star text-yellow-500"></i>
                        @elseif($recipe->averageRating() >= $i - 0.5)
                            <i class="fas fa-star-half-alt text-yellow-500"></i>
                        @else
                            <i class="far fa-star text-gray-400"></i>
                        @endif
                    @endfor
                </div>
            </div>
        @endauth

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
                            <li data-original-amount="{{ $ingredient->pivot->amount }}"
                                data-unit="{{ $ingredient->pivot->unit }}">
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
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold">
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
            <p class="text-sm">Oorspronkelijk gepubliceerd op {{ $recipe->created_at }} | Bijgewerkt
                op {{ $recipe->updated_at }}</p>
        </div>
        @if(Auth::check() && Auth::id() === $recipe->user_id)
            <div class="text-center mt-6">
                <a href="{{ route('recipes.edit', $recipe) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Bewerk recept</a>
            </div>
        @endif

    </div>

    <script>
        const originalServings = {{ $recipe->servings }};
        let currentServings = originalServings;

        @if(Auth::check() && Auth::id() !== $recipe->user_id)
        document.getElementById('favorite-btn').addEventListener('click', function() {
            const icon = document.getElementById('heart-icon');
            const isFavorited = icon.classList.contains('text-red-500');

            // Animation: scale and color change
            icon.style.transform = 'scale(1.2)';
            setTimeout(() => icon.style.transform = 'scale(1)', 200);

            fetch('/recipes/{{ $recipe->id }}/toggle-favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.favorited) {
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-red-500');
                        icon.classList.add('heart-bounce'); // Add bounce animation
                        setTimeout(() => icon.classList.remove('heart-bounce'), 600);
                    } else {
                        icon.classList.remove('text-red-500');
                        icon.classList.add('text-gray-400');
                    }
                    // Update favorite count
                    document.getElementById('favorite-count').textContent = data.favoriteCount;
                })
                .catch(error => console.error('Error:', error));
        });
        @endif

        function updateIngredients() {
            const scale = currentServings / originalServings;
            document.querySelectorAll('#ingredients-list li').forEach(li => {
                const originalAmount = parseFloat(li.dataset.originalAmount);
                if (!isNaN(originalAmount)) {
                    let newAmount = originalAmount * scale;
                    let displayAmount = newAmount % 1 === 0 ? newAmount.toString() : newAmount.toFixed(2);
                    li.querySelector('.amount-display').textContent = displayAmount;
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

        // Star rating script
        const ratingForm = document.getElementById('rating-form');
        const ratingInput = document.getElementById('rating-input');
        const stars = ratingForm ? ratingForm.querySelectorAll('.star') : [];

        function updateAverageStars(average) {
            stars.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (average >= index + 1) {
                    icon.className = 'fas fa-star text-yellow-500';
                } else if (average >= index + 1 - 0.5) {
                    icon.className = 'fas fa-star-half-alt text-yellow-500';
                } else {
                    icon.className = 'far fa-star text-gray-400';
                }
            });
        }

        function highlightUserRating(userRating) {
            stars.forEach((star, index) => {
                if (index < userRating) {
                    star.classList.add('user-rated');
                } else {
                    star.classList.remove('user-rated');
                }
            });
        }

        // Set initial user rating highlight
        const initialUserRating = parseInt(ratingInput.value) || 0;
        highlightUserRating(initialUserRating);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;

                // Submit via AJAX
                const formData = new FormData(ratingForm);
                fetch(ratingForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the average rating stars
                        updateAverageStars(data.average);
                        // Highlight the user's new rating
                        highlightUserRating(rating);
                        // Update the label
                        const label = document.querySelector('label.cookbook-header');
                        if (label) {
                            label.textContent = 'Beoordeling: ' + (data.average !== null ? data.average.toFixed(1) : 'n/a');
                        }
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
