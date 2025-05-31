{{-- resources/views/registrasi/registrasi.blade.php --}}
@extends('layouts.app')

@section('title', 'Registrasi Barang') 
@section('page-title', 'Registrasi Barang Baru') 

@section('content')
<div class="container mt-5"> 
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-semibold text-gray-700">Formulir Registrasi Barang</h2>
        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> 
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <span class="font-medium">Oops! Ada kesalahan:</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registrasiForm" action="{{ route('registrasi.store') }}" method="POST">
            @csrf
            <div class="mb-4"> 
                <label for="nama_barang" class="block mb-2 text-sm font-medium text-gray-900">Nama Barang</label>
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                
                <div id="invalidBarang" class="text-red-600 text-sm hidden mt-1">
                    Nama barang sudah terdaftar. Silakan gunakan nama lain.
                </div>
            </div>

            <div class="mb-6"> 
                <label for="satuan" class="block mb-2 text-sm font-medium text-gray-900">Satuan</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5" id="satuan" name="satuan" required>
                    <option value="" selected disabled>Pilih satuan</option>
                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>liter</option>
                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                    <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>pack</option>
                    <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>unit</option>
                    <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>gram</option>
                    <option value="ml" {{ old('satuan') == 'ml' ? 'selected' : '' }}>ml</option>
                </select>
            </div>

            <button type="submit" class="text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Simpan Barang
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrasiForm');
    const namaBarangInput = document.getElementById('nama_barang');
    const invalidBarangMsg = document.getElementById('invalidBarang');
    
    const barangOptions = @json($barangList->pluck('nama_barang')->map(fn($b) => strtolower(trim($b))) ?? []);

    function validateNamaBarang() {
        const inputVal = namaBarangInput.value.toLowerCase().trim();

        if (inputVal && barangOptions.includes(inputVal)) {
            invalidBarangMsg.classList.remove('hidden'); 
           
            return false;
        } else {
            invalidBarangMsg.classList.add('hidden'); 
            return true;
        }
    }

    form.addEventListener('submit', function (e) {
        if (!validateNamaBarang() && namaBarangInput.value.trim() !== '') {
            e.preventDefault();
            namaBarangInput.focus();
        }
    });

    namaBarangInput.addEventListener('input', function () {
        validateNamaBarang();
    });

    if(namaBarangInput.value.trim() !== ''){
        validateNamaBarang();
    } else {
        invalidBarangMsg.classList.add('hidden');
    }
});
</script>
@endsection
