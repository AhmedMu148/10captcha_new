<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $homeFaqs = Faq::whereRaw("FIND_IN_SET(1, `show`)")->where('status', 1)->get();
        $plans = Plan::where('status', 1)->orderBy('sort')->get();

        return view('welcome', compact('homeFaqs', 'plans'));
    }
}

