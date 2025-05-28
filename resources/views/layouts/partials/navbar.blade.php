<header class="flex items-center justify-between px-6 py-3 bg-[#4796BD] text-white shadow-md relative z-30">
    <div class="flex items-center">
        <button @click="toggleSidebar" class="mr-4 text-white">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <div class="relative">
            <h1 class="text-xl font-semibold text-white">@yield('page-title', 'Inventori')</h1>
        </div>
    </div>

    <div class="flex items-center">
        @auth
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen"
                class="relative z-10 flex items-center p-2 text-sm text-sky-100 hover:text-white bg-[#4796BD] border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <span class="mx-1">{{ Auth::user()->username }}</span>
                <i class="fas fa-chevron-down fa-fw w-5 h-5 mx-1"></i>
            </button>
            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                class="absolute right-0 z-20 w-48 py-2 mt-2 overflow-hidden bg-white rounded-md shadow-xl">
                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                    Login sebagai: <strong>{{ Auth::user()->username }}</strong>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-[#4796BD] hover:text-white">
                        <i class="fas fa-sign-out-alt fa-fw w-5 h-5 mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</header>