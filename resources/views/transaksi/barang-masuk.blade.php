{{-- resources/views/transaksi/barang-masuk.blade.php --}}
@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
<div class="w-full max-w-4xl mx-auto mt-8">
    {{-- Tab Navigasi --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8 text-sm font-medium text-gray-500">
            <a href="{{ route('transaksi.barang-masuk') }}"
               class="py-2 px-1 border-b-2 font-semibold text-gray-800 border-blue-600">
                Barang Masuk
            </a>
        </nav>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        {{-- Judul --}}
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Form Transaksi Barang Masuk</h2>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('transaksi.barang-masuk.store') }}" class="space-y-5" id="barangMasukForm">
            @csrf

            <div>
                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" list="barangList"
                       class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                       autocomplete="off" required>
                <datalist id="barangList">
                    @foreach ($barangList as $barang)
                        <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                    @endforeach
                </datalist>
                @error('nama_barang')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <p id="invalidBarang" class="text-sm text-red-600 mt-1 hidden">Barang tidak valid. Silakan pilih dari daftar.</p>
            </div>

            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stok" id="stok" min="1"
                       class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('stok')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_kadaluarsa" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                       class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('tanggal_kadaluarsa')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('barangMasukForm');
    const namaBarangInput = document.getElementById('nama_barang');
    const invalidBarangMsg = document.getElementById('invalidBarang');
    const barangOptions = @json($barangList->pluck('nama_barang'));

    form.addEventListener('submit', function(e) {
        if (!barangOptions.includes(namaBarangInput.value)) {
            e.preventDefault();
            namaBarangInput.focus();
            invalidBarangMsg.classList.remove('hidden');
        } else {
            invalidBarangMsg.classList.add('hidden');
        }
    });

    namaBarangInput.addEventListener('change', function() {
        if (!barangOptions.includes(this.value)) {
            invalidBarangMsg.classList.remove('hidden');
        } else {
            invalidBarangMsg.classList.add('hidden');
        }
    });
});
</script>
@endsection