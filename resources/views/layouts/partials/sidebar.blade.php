<aside
    class="fixed inset-y-0 left-0 z-30 w-64 bg-[#4796BD] text-white transition-transform duration-300"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
>
    <div class="flex items-center justify-center p-4 bg-[#4796BD]">
        <a href="{{ route('inventori.index') }}" class="flex items-center">
            <img src="{{ asset('images/logo3.png') }}" alt="Freshtrack Logo" class="w-10 h-auto mr-2">
            <span class="text-2xl font-bold text-white">Freshtrack</span>
        </a>
    </div>

    <nav class="mt-4">
        {{-- Inventori --}}
        <a class="flex items-center px-6 py-3 text-gray-100 {{ request()->routeIs('inventori.index') ? 'bg-sky-700' : 'hover:bg-sky-700' }}"
            href="{{ route('inventori.index') }}">
            <i class="fas fa-warehouse fa-fw w-5 mr-3"></i>
            <span>Inventori</span>
        </a>

        {{-- Daftar Barang (Admin Only) --}}
        @if (Auth::check() && Auth::user()->role === 'admin')
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('barang.create') ? 'bg-sky-700' : 'hover:bg-sky-700' }}"
            href="{{ route('barang.create') }}">
            <i class="fas fa-plus-square fa-fw w-5 mr-3"></i>
            <span>Daftar Barang</span>
        </a>
        @endif

        {{-- Transaksi --}}
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('transaksi.barang-masuk') ? 'bg-sky-700' : 'hover:bg-sky-700' }}"
            href="{{ route('transaksi.barang-masuk') }}">
            <i class="fas fa-exchange-alt fa-fw w-5 mr-3"></i>
            <span>Transaksi</span>
        </a>

        {{-- Riwayat (Admin Only) --}}
        @if (Auth::check() && Auth::user()->role === 'admin')
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('riwayat.index') ? 'bg-sky-700' : 'hover:bg-sky-700' }}"
            href="{{ route('riwayat.index') }}">
            <i class="fas fa-history fa-fw w-5 mr-3"></i>
            <span>Riwayat</span>
        </a>
        @endif
    </nav>
</aside>