<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('recipes.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('recipes.index')" :active="request()->routeIs('recipes.*')">
                        {{ __('Recepten') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 flex justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="max-w-lg w-full lg:max-w-xs">
                    <form id="search-form" method="GET" action="{{ route('recipes.index') }}" class="relative">
                        <label for="search" class="sr-only">Zoek recepten</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="search" name="search"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Zoek recepten..." type="search" value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Account Dropdown -->
                    <div class="ml-3 relative" style="z-index: 99999">
                        <button id="account-menu-button" type="button"
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <img class="h-8 w-8 rounded-full"
                                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                 alt="User Avatar">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="account-menu"
                             class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                                <a href="{{ route('recipes.my') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Mijn recepten</a>
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">Login</a>
                @endauth
            </div>

            <!-- Hamburger Menu Button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button id="mobile-menu-button" type="button"
                        class="bg-white dark:bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('recipes.index')" :active="request()->routeIs('recipes.*')">
                {{ __('Recepten') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @else
                    <a href="{{ route('login') }}"
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Login</a>
                @endauth
            </div>
            @auth
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('recipes.my')">
                        {{ __('Mijn recepten') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="display:none;">
                        @csrf
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = mobileMenuButton.querySelector('svg:first-child');
    const closeIcon = mobileMenuButton.querySelector('svg:last-child');

    mobileMenuButton.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.contains('hidden');
        if (isOpen) {
            mobileMenu.classList.remove('hidden');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    //searchbar javascript zodat je niet die pagina hoeft te verversen dat was verschrikkelijk irritant
    //searchbar javascript zodat je niet die pagina hoeft te verversen dat was verschrikkelijk irritant
    document.getElementById('search').addEventListener('input', function () {
        const query = this.value;
        fetch('{{ route("recipes.index") }}?search=' + encodeURIComponent(query), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                const container = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                container.innerHTML = '';
                if (data.recipes.length === 0) {
                    container.innerHTML = '<p class="cookbook-text">Geen recepten gevonden.</p>';
                } else {
                    data.recipes.forEach(recipe => {
                        const card = `
        <a href="/recipes/${recipe.id}" class="block cookbook-card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            ${recipe.image_url ? `<img src="/storage/${recipe.image_url}" alt="${recipe.title}" class="w-full h-64 object-cover">` : '<div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="cookbook-text">Geen afbeelding</span></div>'}
            <div class="p-4">
                <h2 class="text-2xl font-bold cookbook-header">${recipe.title}</h2>
                <p class="cookbook-text">Tijd: ${(recipe.prep_time || 0) + (recipe.cook_time || 0)} minuten</p>
            </div>
        </a>
    `;
                        container.insertAdjacentHTML('beforeend', card);
                    });
                }
                const paginationContainer = document.querySelector('.pagination-container');
                if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                const newUrl = '{{ route("recipes.index") }}' + (query ? '?search=' + encodeURIComponent(query) : '');
                history.pushState(null, '', newUrl);
            })
            .catch(error => console.error('Error:', error));
    });


    // Account dropdown toggle (desktop)
    @auth
    const accountMenuButton = document.getElementById('account-menu-button');
    const accountMenu = document.getElementById('account-menu');

    accountMenuButton.addEventListener('click', () => {
        accountMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!accountMenuButton.contains(event.target) && !accountMenu.contains(event.target)) {
            accountMenu.classList.add('hidden');
        }
    });
    @endauth
</script>
