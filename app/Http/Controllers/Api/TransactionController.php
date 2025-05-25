<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();
        
        if ($request->has('start_date')) {
            try {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->whereDate('created_at', '>=', $startDate->toDateString());
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid start_date format.'], 400);
            }
        }
        
        if ($request->has('end_date')) {
            try {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->whereDate('created_at', '<=', $endDate->toDateString());
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid end_date format.'], 400);
            }
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
}