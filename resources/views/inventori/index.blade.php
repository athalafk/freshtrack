@extends('layouts.app')

@section('title', 'Inventori')
@section('page-title', 'Inventori')

@php
function sort_link($column, $title, $currentSortBy, $currentSortDirection, $currentTab) {
    $direction = ($currentSortBy == $column && $currentSortDirection == 'asc') ? 'desc' : 'asc';
    $icon = '';
    if ($currentSortBy == $column) {
        $icon = $currentSortDirection == 'asc' ? '<i class="fas fa-sort-up ml-1"></i>' : '<i class="fas fa-sort-down ml-1"></i>';
    } else {
        $icon = '<i class="fas fa-sort text-gray-400 ml-1"></i>';
    }

    $queryParams = request()->query();

    $queryParams['tab'] = $currentTab;
    $queryParams['sort_by'] = $column;
    $queryParams['sort_direction'] = $direction;

    unset($queryParams['barangPage']);
    unset($queryParams['batchPage']);

    $url = route('inventori.index', $queryParams);

    return '<a href="' . $url . '" class="flex items-center hover:text-sky-800">' . e($title) . $icon . '</a>';
}
@endphp

@section('content')
@php $defaultTab = request('tab', 'daftarBarang'); @endphp
    <div 
    x-data="{
        activeTab: '{{ $defaultTab }}',
        isEditModalOpen: false,
        isDeleteModalOpen: false,
        editingItem: { id: null, nama_barang: '', satuan: '' },
        itemToDelete: { id: null, nama_barang: '' },
        predefinedUnits: ['kg', 'liter', 'pcs', 'pack', 'unit', 'gram', 'ml'],
        editFormError: {},
        editFormMessage: '',

        openEditModal(item) {
            this.editingItem = { ...item };
            this.editFormError = {};
            this.editFormMessage = '';
            this.isEditModalOpen = true;
        },

        openDeleteModal(item) {
            this.itemToDelete = { ...item };
            this.isDeleteModalOpen = true;
        },

        async submitEditForm() {
            this.editFormError = {};
            this.editFormMessage = '';
            const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

            try {
                const response = await fetch(`{{ url('inventori') }}/${this.editingItem.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        nama_barang: this.editingItem.nama_barang,
                        satuan: this.editingItem.satuan
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        this.editFormError = data.errors;
                        this.editFormMessage = data.message || 'Periksa input Anda.';
                    } else {
                        this.editFormMessage = data.message || 'Terjadi kesalahan server.';
                    }
                    return;
                }

                this.isEditModalOpen = false;
                window.location.reload();
            } catch (error) {
                this.editFormMessage = 'Gagal terhubung ke server.';
                console.error(error);
            }
        }
    }"
    class="container mx-auto"
>

        {{-- Navigasi Tab --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                <a href="{{ route('inventori.index', array_merge(request()->query(), ['tab' => 'daftarBarang', 'barangPage' => null, 'batchPage' => null])) }}"
                    @click.prevent="activeTab = 'daftarBarang'; window.location.href=$el.href"
                    :class="{ 'border-sky-500 text-sky-600': activeTab === 'daftarBarang', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'daftarBarang' }"
                    class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 focus:outline-none">
                    Daftar Barang
                </a>
                <a href="{{ route('inventori.index', array_merge(request()->query(), ['tab' => 'statusKadaluarsa', 'barangPage' => null, 'batchPage' => null])) }}"
                   @click.prevent="activeTab = 'statusKadaluarsa'; window.location.href=$el.href"
                   :class="{ 'border-sky-500 text-sky-600': activeTab === 'statusKadaluarsa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'statusKadaluarsa' }"
                   class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 focus:outline-none">
                   Status Kadaluarsa
                </a>
            </nav>
        </div>

        {{-- Flash Messages for Success/Error --}}
        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <span class="font-medium">Success!</span> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <span class="font-medium">Error!</span> {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                <span class="font-medium">Info!</span> {{ session('info') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('inventori.index') }}">
                <input type="hidden" name="tab" :value="activeTab">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="sort_direction" value="{{ $sortDirection }}">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
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
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('nama_barang', 'Barang', $sortBy, $sortDirection, 'daftarBarang') !!}
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('total_stok', 'Stok', $sortBy, $sortDirection, 'daftarBarang') !!}
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('satuan', 'Satuan', $sortBy, $sortDirection, 'daftarBarang') !!}
                            </th>
                            @if (auth()->user()?->role === 'admin')
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($daftarBarang as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->total_stok }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->satuan }}</td>
                                @if (auth()->user()?->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" @click="openEditModal({
                                        id: {{ $item->id }},
                                        nama_barang: '{{ addslashes($item->nama_barang) }}',
                                        satuan: '{{ $item->satuan }}'
                                    })" class="text-sky-600 hover:text-sky-900 mr-3" title="Edit">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </button>

                                    <button type="button" @click="openDeleteModal({
                                        id: {{ $item->id }},
                                        nama_barang: '{{ addslashes($item->nama_barang) }}'
                                    })" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash-alt fa-fw"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->role === 'admin' ? '4' : '3' }}" class="px-6 py-4 text-sm text-center text-gray-500">Tidak ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($daftarBarang->hasPages())
                <div class="p-4">
                    {{ $daftarBarang->appends(request()->except('page') + ['tab' => 'daftarBarang'])->links() }}
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
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('nama_barang', 'Barang', $sortBy, $sortDirection, 'statusKadaluarsa') !!}
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('stok', 'Stok', $sortBy, $sortDirection, 'statusKadaluarsa') !!}
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('tanggal_kadaluarsa', 'Tgl Kadaluarsa', $sortBy, $sortDirection, 'statusKadaluarsa') !!}
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {!! sort_link('hari_menuju_kadaluarsa', 'Sisa Hari', $sortBy, $sortDirection, 'statusKadaluarsa') !!}
                            </th>
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
                                        Sudah Kadaluarsa
                                    @elseif ($batch->hari_menuju_kadaluarsa == 0)
                                        Hari Ini
                                    @else
                                        {{ $batch->hari_menuju_kadaluarsa }} hari
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if ($batch->hari_menuju_kadaluarsa > 14)
                                        <span class="text-green-500" title="Aman">
                                            <i class="fas fa-check-circle fa-lg"></i>
                                        </span>
                                    @elseif ($batch->hari_menuju_kadaluarsa >= 0 && $batch->hari_menuju_kadaluarsa <= 14)
                                        <span class="text-yellow-500" title="Segera Periksa">
                                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                                        </span>
                                    @else
                                        <span class="text-red-500" title="Kadaluarsa">
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
                    {{ $statusKadaluarsa->appends(request()->except('page') + ['tab' => 'statusKadaluarsa'])->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="isEditModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50"
            @keydown.escape.window="isEditModalOpen = false">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md mx-4 sm:mx-0" @click.away="isEditModalOpen = false">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold" x-text="'Edit Barang: ' + editingItem.nama_barang"></h2>
                    <button @click="isEditModalOpen = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div x-show="editFormMessage" class="mb-4 p-3 rounded-md text-sm"
                    :class="{ 'bg-red-100 text-red-700': Object.keys(editFormError).length > 0, 'bg-green-100 text-green-700': Object.keys(editFormError).length === 0 && editFormMessage }">
                    <p x-text="editFormMessage"></p>
                </div>

                <form @submit.prevent="submitEditForm()">

                    <div class="mb-4">
                        <label for="modal_nama_barang" class="block mb-2 text-sm font-medium text-gray-900">Nama Barang</label>
                        <input type="text" id="modal_nama_barang" x-model="editingItem.nama_barang" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5"
                            required>
                        <template x-if="editFormError.nama_barang">
                            <p class="mt-1 text-xs text-red-600" x-text="editFormError.nama_barang[0]"></p>
                        </template>
                    </div>

                    <div class="mb-6">
                        <label for="modal_satuan" class="block mb-2 text-sm font-medium text-gray-900">Satuan</label>
                        <select id="modal_satuan" x-model="editingItem.satuan" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5"
                                required>
                            <option value="">Pilih Satuan...</option>
                            <template x-for="unit in predefinedUnits" :key="unit">
                                <option :value="unit" x-text="unit.charAt(0).toUpperCase() + unit.slice(1)"></option>
                            </template>
                        </select>
                        <template x-if="editFormError.satuan">
                            <p class="mt-1 text-xs text-red-600" x-text="editFormError.satuan[0]"></p>
                        </template>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" @click="isEditModalOpen = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="isDeleteModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50"
            @keydown.escape.window="isDeleteModalOpen = false">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md mx-4 sm:mx-0" @click.away="isDeleteModalOpen = false">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Hapus</h2>
                    <button @click="isDeleteModalOpen = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="mb-6 text-gray-600">
                    Apakah Anda yakin ingin menghapus barang <strong class="font-medium text-gray-800" x-text="itemToDelete.nama_barang"></strong>? Tindakan ini juga akan menghapus seluruh batch stok terkait dan tidak dapat diurungkan.
                </p>

                <form :action="'{{ url('inventori') }}/' + itemToDelete.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" @click="isDeleteModalOpen = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection