<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BatchBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

class HistoryController extends Controller
{
    public function indexHistory(Request $request)
    {
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = Transaction::query()->orderBy('created_at', 'desc');

    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    // Handle PDF export
    if ($request->has('print_pdf')) {
        $transactions = $query->get();
        
        $pdf = \PDF::loadView('riwayat.pdf', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        
        return $pdf->download('riwayat-transaksi-'.now()->format('Y-m-d').'.pdf');
    }

    $transactions = $query->paginate(10);

    return view('riwayat.index', compact('transactions', 'startDate', 'endDate'));
    }
}
