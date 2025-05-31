@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('content')
<div class="w-full max-w-4xl mx-auto mt-8">
    {{-- Tab Navigasi --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8 text-sm font-medium">
            <a href="{{ route('transaksi.barang-masuk') }}"
               class="py-2 px-1 text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('transaksi.barang-masuk') ? 'border-b-2 font-semibold text-gray-800 border-sky-600' : '' }}">
                Barang Masuk
            </a>
            <a href="{{ route('transaksi.barang-keluar') }}"
               class="py-2 px-1 text-gray-500 hover:text-gray-700 hover:border-gray-300 {{ request()->routeIs('transaksi.barang-keluar') ? 'border-b-2 font-semibold text-gray-800 border-sky-600' : '' }}">
                Barang Keluar
            </a>
        </nav>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        {{-- Judul --}}
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Form Transaksi Barang Keluar</h2>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('transaksi.barang-keluar.store') }}" class="space-y-5" id="barangKeluarForm">
            @csrf

            <div>
                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" list="barangList" value="{{ old('nama_barang') }}"
                       class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-700 focus:ring-sky-500 focus:border-sky-500"
                       autocomplete="off" required>
                <datalist id="barangList">
                    @foreach ($barangList as $barang)
                        <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                    @endforeach
                </datalist>
                @error('nama_barang')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <p id="invalidBarangMsg" class="text-sm text-red-600 mt-1 hidden">Barang tidak valid. Silakan pilih dari daftar.</p>
            </div>

            <div>
                <label for="stok_keluar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Keluar</label>
                <input type="number" name="stok_keluar" id="stok_keluar" min="1" value="{{ old('stok_keluar') }}"
                       class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-700 focus:ring-sky-500 focus:border-sky-500"
                       required>
                @error('stok_keluar')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('barangKeluarForm');
    const namaBarangInput = document.getElementById('nama_barang');
    const invalidBarangMsg = document.getElementById('invalidBarangMsg');
    const barangOptions = @json($barangList->pluck('nama_barang')->toArray());

    function validateNamaBarang() {
        const currentValue = namaBarangInput.value.trim();
        if (currentValue && !barangOptions.includes(currentValue)) {
            invalidBarangMsg.classList.remove('hidden');
            return false;
        }
        invalidBarangMsg.classList.add('hidden');
        return true;
    }


    form.addEventListener('submit', function(e) {
        if (!validateNamaBarang()) {
            e.preventDefault();
            namaBarangInput.focus();
        }
    });

    namaBarangInput.addEventListener('change', validateNamaBarang);
    namaBarangInput.addEventListener('input', function() {
    
        if (invalidBarangMsg.classList.contains('hidden') === false) {
             validateNamaBarang(); 
        }
    });
});
</script>
@endsection