<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;


class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::with(['faqs' => function ($q) {
            $q->where('status', 'Active');
        }])->get();

        return view('faq.index', compact('categories'));
    }
}
