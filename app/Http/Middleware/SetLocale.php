<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SetLocale
{
    /**
     * The application's locale configuration.
     *
     * @var array
     */
    protected $locales = ['en', 'ar'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Priority order for locale determination:
        // 1. Session
        // 2. User preference (if authenticated)
        // 3. URL parameter
        // 4. Cookie
        // 5. Browser preference
        // 6. Default fallback

        $locale = $this->determineLocale($request);

        // Validate locale
        if (!in_array($locale, $this->locales)) {
            $locale = config('app.locale', 'ar');
        }

        // Set the application locale
        App::setLocale($locale);

        // Store in session if not already there or if different
        if (Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }

        // Set Carbon locale for date formatting
        $this->setCarbonLocale($locale);

        // Set URL default parameter for locale
        URL::defaults(['locale' => $locale]);

        // Share locale data with all views
        $this->shareLocaleData($locale);

        // Add locale to response cookie for persistence
        $response = $next($request);

        if (method_exists($response, 'cookie')) {
            $response->cookie('locale', $locale, 60 * 24 * 30); // 30 days
        }

        return $response;
    }

    /**
     * Determine the locale for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function determineLocale(Request $request): string
    {
        // Check session first (highest priority)
        if ($locale = Session::get('locale')) {
            return $locale;
        }

        // Check authenticated user's preference
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
            Session::put('locale', $locale);
            return $locale;
        }

        // Check URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $this->locales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
            return $locale;
        }

        // Check cookie
        if ($locale = $request->cookie('locale')) {
            if (in_array($locale, $this->locales)) {
                Session::put('locale', $locale);
                return $locale;
            }
        }

        // Check browser language preference
        $locale = $this->getBrowserLocale($request);
        Session::put('locale', $locale);

        return $locale;
    }

    /**
     * Get browser locale preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getBrowserLocale(Request $request): string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!$acceptLanguage) {
            return config('app.locale', 'ar');
        }

        // Parse Accept-Language header
        $languages = $this->parseAcceptLanguage($acceptLanguage);

        foreach ($languages as $lang) {
            // Check for exact match
            if (in_array($lang, $this->locales)) {
                return $lang;
            }

            // Check for language code match (e.g., 'en-US' matches 'en')
            $langCode = substr($lang, 0, 2);
            if (in_array($langCode, $this->locales)) {
                return $langCode;
            }
        }

        return config('app.locale', 'ar');
    }

    /**
     * Parse Accept-Language header.
     *
     * @param  string  $acceptLanguage
     * @return array
     */
    protected function parseAcceptLanguage(string $acceptLanguage): array
    {
        $languages = [];
        $langParse = [];

        // Parse the Accept-Language header
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLanguage, $langParse);

        if (count($langParse[1])) {
            // Create list of languages with their q values
            $languages = array_combine($langParse[1], $langParse[2]);

            // Set default q value to 1 for languages without q values
            foreach ($languages as $lang => $val) {
                if ($val === '') {
                    $languages[$lang] = 1;
                }
            }

            // Sort by q value (highest first)
            arsort($languages, SORT_NUMERIC);

            return array_keys($languages);
        }

        return [];
    }

    /**
     * Set Carbon locale for date formatting.
     *
     * @param  string  $locale
     * @return void
     */
    protected function setCarbonLocale(string $locale): void
    {
        // Map application locales to Carbon locales
        $carbonLocales = [
            'en' => 'en',
            'ar' => 'ar',
        ];

        $carbonLocale = $carbonLocales[$locale] ?? 'en';

        try {
            Carbon::setLocale($carbonLocale);

            // For Arabic, set specific formatting if needed
            if ($locale === 'ar') {
                setlocale(LC_TIME, 'ar_SA.UTF-8', 'ar_SA', 'ar');
            } else {
                setlocale(LC_TIME, 'en_US.UTF-8', 'en_US', 'en');
            }
        } catch (\Exception $e) {
            // Fallback to English if locale setting fails
            Carbon::setLocale('en');
        }
    }

    /**
     * Share locale data with all views.
     *
     * @param  string  $locale
     * @return void
     */
    protected function shareLocaleData(string $locale): void
    {
        $isRtl = $locale === 'ar';
        $direction = $isRtl ? 'rtl' : 'ltr';
        $oppositeLocale = $locale === 'ar' ? 'en' : 'ar';

        view()->share([
            'currentLocale' => $locale,
            'isRtl' => $isRtl,
            'direction' => $direction,
            'oppositeLocale' => $oppositeLocale,
            'availableLocales' => $this->locales,
            'localeNames' => [
                'en' => 'English',
                'ar' => 'العربية'
            ],
            'localeFlagIcons' => [
                'en' => '🇬🇧',
                'ar' => '🇸🇦'
            ]
        ]);
    }
}
