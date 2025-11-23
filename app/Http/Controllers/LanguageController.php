<?php

namespace App\Http\Controllers;

use App\Models\Network;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class LanguageController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        return $this->switchLanguage($request, $locale);
    }

    /**
     * Available languages for the application
     *
     * @var array
     */
    protected $availableLocales = ['en', 'ar'];

    /**
     * Language display names
     *
     * @var array
     */
    protected $localeNames = [
        'en' => 'English',
        'ar' => 'العربية'
    ];

    /**
     * Switch the application language
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function switchLanguage(Request $request, string $locale)
    {
        // Validate locale
        if (!in_array($locale, $this->availableLocales)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.invalid_language', [], Session::get('locale', 'en')),
                    'error' => 'Invalid locale provided'
                ], 400);
            }
            return back()->with('error', __('messages.invalid_language'));
        }

        // Store the old locale for comparison
        $oldLocale = Session::get('locale', config('app.locale'));

        // Update session
        Session::put('locale', $locale);

        // Set application locale immediately
        App::setLocale($locale);

        // Set Carbon locale for date formatting
        $this->setCarbonLocale($locale);

        // If user is authenticated, update their preference in database
        if (auth()->check()) {
            try {
                auth()->user()->update(['locale' => $locale]);
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to update user locale preference: ' . $e->getMessage());
            }
        }

        // Set cookie for persistence (30 days)
        Cookie::queue('locale', $locale, 60 * 24 * 30);

        // Handle AJAX/JSON requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'localeName' => $this->localeNames[$locale],
                'message' => __('messages.language_switched', ['language' => $this->localeNames[$locale]], $locale),
                'isRtl' => $locale === 'ar',
                'direction' => $locale === 'ar' ? 'rtl' : 'ltr',
                'oldLocale' => $oldLocale,
                'requiresRefresh' => $this->requiresPageRefresh($oldLocale, $locale)
            ]);
        }

        // Flash success message in the new language
        $message = __('messages.language_switched', ['language' => $this->localeNames[$locale]], $locale);

        $redirect = $request->headers->has('referer')
            ? redirect()->back()
            : $this->determineFallbackRedirect($request);

        return $redirect
            ->with('success', $message)
            ->with('locale_changed', true)
            ->with('new_locale', $locale);
    }

    /**
     * Get available languages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLanguages(Request $request)
    {
        $currentLocale = App::getLocale();

        $languages = collect($this->availableLocales)->map(function ($locale) use ($currentLocale) {
            return [
                'code' => $locale,
                'name' => $this->localeNames[$locale],
                'isActive' => $locale === $currentLocale,
                'isRtl' => $locale === 'ar',
                'flag' => $this->getLocaleFlag($locale)
            ];
        });

        return response()->json([
            'success' => true,
            'languages' => $languages,
            'current' => $currentLocale
        ]);
    }

    /**
     * Set Carbon locale
     *
     * @param string $locale
     * @return void
     */
    protected function setCarbonLocale(string $locale): void
    {
        try {
            Carbon::setLocale($locale);

            // Set system locale for proper date formatting
            if ($locale === 'ar') {
                setlocale(LC_TIME, 'ar_SA.UTF-8', 'ar_SA', 'ar', 'Arabic');
                setlocale(LC_ALL, 'ar_SA.UTF-8', 'ar_SA', 'ar', 'Arabic');
            } else {
                setlocale(LC_TIME, 'en_US.UTF-8', 'en_US', 'en', 'English');
                setlocale(LC_ALL, 'en_US.UTF-8', 'en_US', 'en', 'English');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to set Carbon locale: ' . $e->getMessage());
        }
    }

    /**
     * Check if page refresh is required for proper RTL/LTR switch
     *
     * @param string $oldLocale
     * @param string $newLocale
     * @return bool
     */
    protected function requiresPageRefresh(string $oldLocale, string $newLocale): bool
    {
        // Check if switching between RTL and LTR languages
        $oldIsRtl = $oldLocale === 'ar';
        $newIsRtl = $newLocale === 'ar';

        return $oldIsRtl !== $newIsRtl;
    }

    /**
     * Get locale flag emoji or icon
     *
     * @param string $locale
     * @return string
     */
    protected function getLocaleFlag(string $locale): string
    {
        $flags = [
            'en' => '🇬🇧',
            'ar' => '🇸🇦'
        ];

        return $flags[$locale] ?? '🌐';
    }

    /**
     * Determine the safest redirect target when no referrer is present.
     */
    protected function determineFallbackRedirect(Request $request)
    {
        $networkSlug = $request->input('network');
        $branchSlug = $request->input('branch');

        $school = null;

        if ($networkSlug && $branchSlug) {
            $network = Network::where('slug', $networkSlug)->first();
            $school = School::where('slug', $branchSlug)->first();

            if ($network && $school && $school->network_id !== $network->id) {
                $school = null;
            }
        }

        if (!$school && auth()->check()) {
            $school = auth()->user()->school;
        }

        return $school
            ? redirect()->to(tenant_route('dashboard', $school))
            : redirect('/');
    }

    /**
     * Get current language info
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentLanguage(Request $request)
    {
        $locale = App::getLocale();

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'name' => $this->localeNames[$locale] ?? $locale,
            'isRtl' => $locale === 'ar',
            'direction' => $locale === 'ar' ? 'rtl' : 'ltr',
            'flag' => $this->getLocaleFlag($locale)
        ]);
    }
}
