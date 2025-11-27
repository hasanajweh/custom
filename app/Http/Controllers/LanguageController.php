<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,ar'
        ]);

        session(['locale' => $request->locale]);
        app()->setLocale($request->locale);

        return response()->noContent(); // Do NOT redirect to /locale
    }
}
