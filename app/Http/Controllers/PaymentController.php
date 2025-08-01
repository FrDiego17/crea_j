<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PaymentController extends Controller
{
    public function getBalance(Request $request)
    {
        return response()->json([
            'balance' => $request->user()->balance ?? 0,
        ]);
    }

    public function registerPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.5',
        ]);

        $user = $request->user();

        $user->balance += floatval($request->amount);
        $user->save();

        return response()->json([
            'message' => 'Pago registrado',
            'balance' => $user->balance,
        ]);
    }

}
