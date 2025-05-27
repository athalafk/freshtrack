<aside
    class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 ease-in-out transform bg-[#4796BD] text-white lg:translate-x-0 lg:static lg:inset-0"
    :class="{'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen}">

    <div class="flex items-center justify-center p-4 bg-[#4796BD]">
        <a href="{{ route('inventori.index') }}" class="flex items-center">
            <img src="{{ asset('images/logo3.png') }}" alt="Freshtrack Logo" class="w-10 h-auto mr-2">
            <span class="text-2xl font-bold text-white">Freshtrack</span>
        </a>
    </div>

    <nav class="mt-4">
        {{-- Inventori --}}
        <a class="flex items-center px-6 py-3 text-gray-100 {{ request()->routeIs('inventori.index') ? 'bg-[#4796BD]' : 'hover:bg-[#4796BD]' }}"
            href="{{ route('inventori.index') }}">
            <i class="fas fa-warehouse fa-fw w-5 mr-3"></i>
            Inventori
        </a>

        {{-- Daftar Barang --}}
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('barang.create') ? 'bg-[#4796BD]' : 'hover:bg-[#4796BD]' }}"
            href="{{ route('barang.create') }}">
            <i class="fas fa-plus-square fa-fw w-5 mr-3"></i>
            <span class="mx-3">Daftar Barang</span>
        </a>

        {{-- Transaksi --}}
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('transaksi.index') ? 'bg-[#4796BD]' : 'hover:bg-[#4796BD]' }}"
            href="{{ route('transaksi.index') }}">
            <i class="fas fa-exchange-alt fa-fw w-5 mr-3"></i>
            <span class="mx-3">Transaksi</span>
        </a>

        {{-- Riwayat --}}
        <a class="flex items-center px-6 py-3 mt-2 text-gray-100 {{ request()->routeIs('riwayat.index') ? 'bg-[#4796BD]' : 'hover:bg-[#4796BD]' }}"
            href="{{ route('riwayat.index') }}">
            <i class="fas fa-history fa-fw w-5 mr-3"></i>
            <span class="mx-3">Riwayat</span>
        </a>
    </nav>
</aside>