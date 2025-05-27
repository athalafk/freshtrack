@extends('layouts.app')

@section('title', 'Inventori')
@section('page-title', 'Inventori')

@section('content')
    <div x-data="{ activeTab: 'daftarBarang' }" class="container mx-auto">

        {{-- Navigasi Tab --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'daftarBarang'"
                    :class="{ 'border-sky-500 text-sky-600': activeTab === 'daftarBarang', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'daftarBarang' }"
                    class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 focus:outline-none">
                    Daftar Barang
                </button>
                <button @click="activeTab = 'statusKadaluarsa'"
                    :class="{ 'border-sky-500 text-sky-600': activeTab === 'statusKadaluarsa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'statusKadaluarsa' }"
                    class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 focus:outline-none">
                    Status Kadaluarsa
                </button>
            </nav>
        </div>

        {{-- Search Bar --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('inventori.index') }}">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        {{-- Ganti SVG dengan Font Awesome --}}
                        <i class="fas fa-search text-gray-400 w-5 h-5"></i>
                    </div>
                    <input type="search" name="search" id="search" value="{{ $searchTerm ?? '' }}"
                           class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Cari Barang...">
                </div>
            </form>
        </div>

        {{-- Konten Tab Daftar Barang --}}
        <div x-show="activeTab === 'daftarBarang'" x-cloak>
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Daftar Barang</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Barang</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Stok</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Satuan</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($daftarBarang as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->total_stok }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->satuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-sky-600 hover:text-sky-900 mr-3" title="Edit">
                                        {{-- Ganti SVG dengan Font Awesome --}}
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                    <button type="button" class="text-red-600 hover:text-red-900" title="Hapus">
                                        {{-- Ganti SVG dengan Font Awesome --}}
                                        <i class="fas fa-trash-alt fa-fw"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">Tidak ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($daftarBarang->hasPages())
                <div class="p-4">
                    {{ $daftarBarang->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Konten Tab Status Kadaluarsa --}}
        <div x-show="activeTab === 'statusKadaluarsa'" x-cloak>
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Status Kadaluarsa</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Barang</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Stok</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tgl Kadaluarsa</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sisa Hari</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($statusKadaluarsa as $batch)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $batch->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $batch->stok }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $batch->tanggal_kadaluarsa_formatted }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $batch->hari_menuju_kadaluarsa <= 0 ? 'text-red-600 font-semibold' : ($batch->hari_menuju_kadaluarsa <= 14 ? 'text-yellow-600 font-semibold' : 'text-gray-500') }}">
                                    @if ($batch->hari_menuju_kadaluarsa < 0)
                                        Sudah Kadaluwarsa
                                    @elseif ($batch->hari_menuju_kadaluarsa == 0)
                                        Hari Ini
                                    @else
                                        {{ $batch->hari_menuju_kadaluarsa }} hari
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if ($batch->hari_menuju_kadaluarsa > 14)
                                        <span class="text-green-500" title="Aman">
                                            {{-- Ganti SVG dengan Font Awesome --}}
                                            <i class="fas fa-check-circle fa-lg"></i>
                                        </span>
                                    @elseif ($batch->hari_menuju_kadaluarsa >= 0 && $batch->hari_menuju_kadaluarsa <= 14)
                                        <span class="text-yellow-500" title="Segera Periksa">
                                            {{-- Ganti SVG dengan Font Awesome --}}
                                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                                        </span>
                                    @else
                                        <span class="text-red-500" title="Kadaluwarsa">
                                            {{-- Ganti SVG dengan Font Awesome --}}
                                            <i class="fas fa-times-circle fa-lg"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">Tidak ada data batch barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($statusKadaluarsa->hasPages())
                <div class="p-4">
                     {{ $statusKadaluarsa->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection