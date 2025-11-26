<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    // For legacy single-action route (if anything still calls it)
    public function __invoke(Request $request)
    {
        return $this->update($request);
    }

    public function update(Request $request)
    {
        // locale can come from POST body or (legacy) route param
        $locale = $request->input('locale') ?? $request->route('locale');

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = config('app.locale', 'ar');
        }

        session(['locale' => $locale]);
        App::setLocale($locale);

        // Redirect back to same page; if no referrer, go to landing
        $fallback = route('landing');
        $previous = url()->previous();

        $redirectUrl = $previous ?: $fallback;

        return redirect()->to($redirectUrl);
    }

    // Optional: still expose language list APIs if used by JS
    public function getLanguages()
    {
        return response()->json([
            ['code' => 'ar', 'name' => 'العربية'],
            ['code' => 'en', 'name' => 'English'],
        ]);
    }

    public function getCurrentLanguage()
    {
        $locale = session('locale', config('app.locale', 'ar'));

        return response()->json([
            'code' => $locale,
        ]);
    }
}
