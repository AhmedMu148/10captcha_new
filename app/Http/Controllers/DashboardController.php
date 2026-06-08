<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user       = $request->user();
        $userDetail = $user->detail;

        // Solved captcha counts — tables don't exist yet, default to 0
        $totalSolved  = 0;
        $solvedToday  = 0;

        return view('dashboard', compact('user', 'userDetail', 'totalSolved', 'solvedToday'));
    }
}
