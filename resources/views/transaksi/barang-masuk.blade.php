{{-- resources/views/transaksi/barang-masuk.blade.php --}}
@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
<div class="w-full max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

    {{-- Judul Halaman --}}
    <h2 class="text-2xl font-bold mb-4">Transaksi Barang Masuk</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('transaksi.barang-masuk.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nama_barang" class="block text-sm font-medium">Nama Barang</label>
            <select name="nama_barang" id="nama_barang" class="w-full mt-1 border rounded p-2">
                <option value="">Pilih barang</option>
                @foreach ($barangList as $barang)
                    <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                @endforeach
            </select>
            @error('nama_barang')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="stok" class="block text-sm font-medium">Stok</label>
            <input type="number" name="stok" id="stok" class="w-full mt-1 border rounded p-2" required>
            @error('stok')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tanggal_kadaluarsa" class="block text-sm font-medium">Tanggal Kadaluarsa</label>
            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" class="w-full mt-1 border rounded p-2" required>
            @error('tanggal_kadaluarsa')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Simpan Transaksi
        </button>
    </form>
</div>
@endsection
