<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $homeFaqs = Faq::where('status', 'Active')->get();
        $plans = Plan::where('status', 'Active')->orderBy('sort')->get();

        return view('welcome', compact('homeFaqs', 'plans'));
    }
}
