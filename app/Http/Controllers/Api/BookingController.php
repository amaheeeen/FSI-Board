<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Http\Resources\InvoiceResource;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Jamaah

        // Get Transactions where Jamaah is listed in details
        $transactions = Transaction::whereHas('details', function ($query) use ($user) {
            $query->where('jamaah_id', $user->id);
        })->with('packet')->latest()->get();

        return response()->json(['data' => $transactions]);
    }

    public function payment(Request $request, $id)
    {
        $user = $request->user();

        $transaction = Transaction::where('id', $id)
            ->whereHas('details', function ($query) use ($user) {
                 $query->where('jamaah_id', $user->id);
            })
            ->firstOrFail();

        return new InvoiceResource($transaction);
    }
}
