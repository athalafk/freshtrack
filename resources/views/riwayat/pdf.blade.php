<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Riwayat Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-danger { color: #dc3545; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Freshtrack</h1>
        <h2>Laporan Riwayat Transaksi</h2>
        @if($startDate || $endDate)
        <p>
            Periode: 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Semua' }}
            -
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Semua' }}
        </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Barang</th>
                <th>Stok</th>
                <th>Pelaku</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->item }}</td>
                <td @if(in_array($transaction->type, ['keluar', 'hapus'])) class="text-danger" @endif>
                    {{ $transaction->stock }}
                </td>
                <td>{{ $transaction->actor }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>