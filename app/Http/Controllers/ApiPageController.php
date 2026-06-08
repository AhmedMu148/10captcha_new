<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiPageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('api.index', compact('user'));
    }

    public function regenerate(Request $request)
    {
        $user = $request->user();
        $user->api_key = '10c_' . Str::random(32);
        $user->save();

        return redirect()->route('api.page')->with('success', 'API key regenerated successfully.');
    }

    public function docs()
    {
        return view('api.docs');
    }
}
