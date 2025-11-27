<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function update(Request $request)
    {
        $locale = $request->input('locale');

        if (!in_array($locale, ['ar', 'en'])) {
            return response()->json(['error' => 'Invalid locale'], 422);
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return response()->json(['status' => 'ok']);
    }
}
