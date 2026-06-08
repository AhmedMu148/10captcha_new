<?php

namespace App\Http\Controllers;

use App\Models\ReportDaily;
use App\Models\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public static function typeName(int $type): string
    {
        return match ($type) {
            1 => 'Image Captcha',
            2 => 'reCAPTCHA v2',
            3 => 'reCAPTCHA v3',
            4 => 'hCaptcha',
            default => 'Unknown',
        };
    }    

    public function index()
    {
        return view('reports.index');
    }

}
