<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Update the application locale and redirect back.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:en,ar'],
        ]);

        $locale = $validated['locale'];

        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }

    /**
     * Provide available languages for clients that need them.
     */
    public function getLanguages(): array
    {
        return [
            'available' => ['ar', 'en'],
            'current' => App::getLocale(),
        ];
    }

    /**
     * Return the currently active language.
     */
    public function getCurrentLanguage(): array
    {
        return [
            'locale' => App::getLocale(),
        ];
    }
}
