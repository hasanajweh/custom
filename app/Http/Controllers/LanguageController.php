<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
}
