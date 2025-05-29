@extends('layouts.app')

@section('title', 'Freshtrack')
@section('page-title', 'Riwayat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Riwayat Transaksi</h2>
    </div>

    {{-- Date Range Filter --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('riwayat.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Awal</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" 
                           class="w-full p-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" 
                           class="w-full p-2 border border-gray-300 rounded focus:ring-sky-500 focus:border-sky-500">
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        Filter
                    </button>
                    
                    {{-- Tombol PDF --}}
                    <button type="submit" name="print_pdf" value="1"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Cetak PDF
                    </button>
                    
                    @if($startDate || $endDate)
                        <a href="{{ route('riwayat.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 focus:outline-none">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Barang
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelaku
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst($transaction->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->item }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm 
                                @if(in_array($transaction->type, ['keluar', 'hapus'])) text-red-600 font-medium @endif">
                                {{ $transaction->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->actor }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection