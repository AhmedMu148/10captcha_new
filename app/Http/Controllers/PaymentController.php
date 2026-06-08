<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{



    public function topup(Request $request)
    {
        $user = $request->user();
        return view('payment.topup', compact('user'));
    }

    public function history()
    {
        return view('payment.history');
    }

    
}
