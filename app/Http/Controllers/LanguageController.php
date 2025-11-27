<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,ar'
        ]);

        session(['locale' => $request->locale]);
        App::setLocale($request->locale);

        return redirect()->back();
    }
}
