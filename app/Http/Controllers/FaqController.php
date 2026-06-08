<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FaqLanguage;
use Illuminate\Http\Request;


class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FaqCategory::with(['faqs' => function ($q) {
            $q->where('status', 1);
        }])->get();

        return view('faq.index', compact('categories'));
    }

    public function show(Faq $faq)
    {
        //
    }
}
