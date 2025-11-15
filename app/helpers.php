<?php

use App\Models\School;
use InvalidArgumentException;

/**
 * Helper functions for the Scholder application
 */

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('tenant_route')) {
    /**
     * Generate a tenant-aware route with network and school parameters.
     */
    function tenant_route(string $name, School $school, array $parameters = [], bool $absolute = true): string
    {
        $network = $school->network;

        if (! $network) {
            throw new InvalidArgumentException('School must belong to a network to generate tenant routes.');
        }

        return route(
            $name,
            array_merge([
                'network' => $network->slug,
                'school' => $school->slug,
            ], $parameters),
            $absolute,
        );
    }
}

if (!function_exists('getAvatarColor')) {
    /**
     * Get a consistent color gradient for user avatars based on their name
     *
     * @param string $name
     * @return string
     */
    function getAvatarColor($name)
    {
        $colors = [
            'from-blue-500 to-blue-600',
            'from-green-500 to-green-600',
            'from-purple-500 to-purple-600',
            'from-pink-500 to-pink-600',
            'from-indigo-500 to-indigo-600',
            'from-red-500 to-red-600',
            'from-yellow-500 to-yellow-600',
            'from-teal-500 to-teal-600',
        ];

        $index = abs(crc32($name)) % count($colors);

        return $colors[$index];
    }
}

if (!function_exists('isRtl')) {
    /**
     * Check if current locale is RTL
     *
     * @param string|null $locale
     * @return bool
     */
    function isRtl($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return in_array($locale, ['ar', 'he', 'fa', 'ur']);
    }
}

if (!function_exists('getDirection')) {
    /**
     * Get text direction based on locale
     *
     * @param string|null $locale
     * @return string
     */
    function getDirection($locale = null)
    {
        return isRtl($locale) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('oppositeDirection')) {
    /**
     * Get opposite text direction
     *
     * @param string|null $locale
     * @return string
     */
    function oppositeDirection($locale = null)
    {
        return isRtl($locale) ? 'ltr' : 'rtl';
    }
}

if (!function_exists('localizedRoute')) {
    /**
     * Generate a localized route
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    function localizedRoute($name, $parameters = [], $absolute = true)
    {
        $locale = app()->getLocale();

        // Don't add locale parameter if route doesn't need it
        if (!in_array('locale', Route::current()->parameterNames() ?? [])) {
            return route($name, $parameters, $absolute);
        }

        $parameters['locale'] = $locale;
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('switchLocaleUrl')) {
    /**
     * Get URL for switching to a different locale
     *
     * @param string $locale
     * @param bool $keepQuery
     * @return string
     */
    function switchLocaleUrl($locale, $keepQuery = true)
    {
        $currentUrl = request()->url();
        $queryString = $keepQuery ? request()->getQueryString() : null;

        // Get current route parameters
        $routeParams = Route::current() ? Route::current()->parameters() : [];

        // Update locale in route
        $url = route('language.switch', ['locale' => $locale]);

        if ($queryString) {
            $url .= '?' . $queryString;
        }

        return $url;
    }
}

if (!function_exists('currentLocale')) {
    /**
     * Get current application locale
     *
     * @return string
     */
    function currentLocale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('availableLocales')) {
    /**
     * Get available application locales
     *
     * @return array
     */
    function availableLocales()
    {
        return config('app.available_locales', ['en', 'ar']);
    }
}

if (!function_exists('localeDisplayName')) {
    /**
     * Get display name for a locale
     *
     * @param string $locale
     * @return string
     */
    function localeDisplayName($locale)
    {
        $names = [
            'en' => 'English',
            'ar' => 'العربية',
            'fr' => 'Français',
            'es' => 'Español',
            'de' => 'Deutsch',
        ];

        return $names[$locale] ?? $locale;
    }
}

if (!function_exists('formatArabicDate')) {
    /**
     * Format date in Arabic
     *
     * @param \Carbon\Carbon|\DateTime $date
     * @param string $format
     * @return string
     */
    function formatArabicDate($date, $format = 'j F Y')
    {
        // Ensure we have a Carbon instance
        if (!($date instanceof \Carbon\Carbon)) {
            $date = \Carbon\Carbon::parse($date);
        }

        if (app()->getLocale() !== 'ar') {
            return $date->format($format);
        }

        $months = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'أغسطس',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر'
        ];

        $days = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الإثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة'
        ];

        $shortMonths = [
            'Jan' => 'يناير',
            'Feb' => 'فبراير',
            'Mar' => 'مارس',
            'Apr' => 'أبريل',
            'May' => 'مايو',
            'Jun' => 'يونيو',
            'Jul' => 'يوليو',
            'Aug' => 'أغسطس',
            'Sep' => 'سبتمبر',
            'Oct' => 'أكتوبر',
            'Nov' => 'نوفمبر',
            'Dec' => 'ديسمبر'
        ];

        $shortDays = [
            'Sat' => 'السبت',
            'Sun' => 'الأحد',
            'Mon' => 'الإثنين',
            'Tue' => 'الثلاثاء',
            'Wed' => 'الأربعاء',
            'Thu' => 'الخميس',
            'Fri' => 'الجمعة'
        ];

        $formatted = $date->format($format);

        // Replace months and days
        foreach ($months as $english => $arabic) {
            $formatted = str_replace($english, $arabic, $formatted);
        }

        foreach ($days as $english => $arabic) {
            $formatted = str_replace($english, $arabic, $formatted);
        }

        foreach ($shortMonths as $english => $arabic) {
            $formatted = str_replace($english, $arabic, $formatted);
        }

        foreach ($shortDays as $english => $arabic) {
            $formatted = str_replace($english, $arabic, $formatted);
        }

        return $formatted;
    }
}

if (!function_exists('localizedDate')) {
    /**
     * Format date according to current locale
     *
     * @param \Carbon\Carbon|\DateTime|string $date
     * @param string $format
     * @return string
     */
    function localizedDate($date, $format = null)
    {
        if (!($date instanceof \Carbon\Carbon)) {
            $date = \Carbon\Carbon::parse($date);
        }

        $locale = app()->getLocale();

        if ($format === null) {
            $format = $locale === 'ar' ? 'j F Y' : 'F j, Y';
        }

        if ($locale === 'ar') {
            return formatArabicDate($date, $format);
        }

        return $date->format($format);
    }
}

if (!function_exists('arabicNumbers')) {
    /**
     * Convert English numbers to Arabic-Indic numerals
     *
     * @param string|int|float $number
     * @param bool $useEasternArabic
     * @return string
     */
    function arabicNumbers($number, $useEasternArabic = true)
    {
        if (app()->getLocale() !== 'ar') {
            return (string) $number;
        }

        $number = (string) $number;

        if ($useEasternArabic) {
            // Eastern Arabic numerals (used in Arab countries)
            $westernArabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $easternArabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return str_replace($westernArabic, $easternArabic, $number);
        }

        // Keep Western numerals (common in modern Arabic interfaces)
        return $number;
    }
}

if (!function_exists('localizedNumber')) {
    /**
     * Format number according to locale
     *
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function localizedNumber($number, $decimals = 0)
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            $formatted = number_format($number, $decimals, ',', '.');
            return arabicNumbers($formatted, false); // Use western numerals by default
        }

        return number_format($number, $decimals, '.', ',');
    }
}

if (!function_exists('getFileTypeIcon')) {
    /**
     * Get the appropriate icon for a file type
     *
     * @param string $extension
     * @return array
     */
    function getFileTypeIcon($extension)
    {
        $extension = strtolower($extension);

        $icons = [
            'pdf' => ['ri-file-pdf-line', 'bg-red-100', 'text-red-600'],
            'doc' => ['ri-file-word-2-line', 'bg-blue-100', 'text-blue-600'],
            'docx' => ['ri-file-word-2-line', 'bg-blue-100', 'text-blue-600'],
            'xls' => ['ri-file-excel-2-line', 'bg-green-100', 'text-green-600'],
            'xlsx' => ['ri-file-excel-2-line', 'bg-green-100', 'text-green-600'],
            'ppt' => ['ri-file-ppt-2-line', 'bg-orange-100', 'text-orange-600'],
            'pptx' => ['ri-file-ppt-2-line', 'bg-orange-100', 'text-orange-600'],
            'jpg' => ['ri-image-line', 'bg-purple-100', 'text-purple-600'],
            'jpeg' => ['ri-image-line', 'bg-purple-100', 'text-purple-600'],
            'png' => ['ri-image-line', 'bg-purple-100', 'text-purple-600'],
            'gif' => ['ri-image-line', 'bg-purple-100', 'text-purple-600'],
            'txt' => ['ri-file-text-line', 'bg-gray-100', 'text-gray-600'],
            'zip' => ['ri-file-zip-line', 'bg-yellow-100', 'text-yellow-600'],
            'rar' => ['ri-file-zip-line', 'bg-yellow-100', 'text-yellow-600'],
        ];

        return $icons[$extension] ?? ['ri-file-line', 'bg-gray-100', 'text-gray-600'];
    }
}

if (!function_exists('getSubmissionTypeLabel')) {
    /**
     * Get localized label for submission type
     *
     * @param string $type
     * @return string
     */
    function getSubmissionTypeLabel($type)
    {
        return __('files.' . $type, [], currentLocale());
    }
}

if (!function_exists('getRoleLabel')) {
    /**
     * Get localized label for user role
     *
     * @param string $role
     * @return string
     */
    function getRoleLabel($role)
    {
        return __('users.' . $role, [], currentLocale());
    }
}

if (!function_exists('trans_choice_locale')) {
    /**
     * Get translated choice with specific locale
     *
     * @param string $key
     * @param int $number
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function trans_choice_locale($key, $number, array $replace = [], $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return trans_choice($key, $number, $replace, $locale);
    }
}

if (!function_exists('getLocaleFlag')) {
    /**
     * Get flag emoji for locale
     *
     * @param string $locale
     * @return string
     */
    function getLocaleFlag($locale)
    {
        $flags = [
            'en' => '🇬🇧',
            'ar' => '🇸🇦',
            'fr' => '🇫🇷',
            'es' => '🇪🇸',
            'de' => '🇩🇪',
        ];

        return $flags[$locale] ?? '🌐';
    }
}

if (!function_exists('getAlignmentClass')) {
    /**
     * Get text alignment class based on locale
     *
     * @param string $default
     * @return string
     */
    function getAlignmentClass($default = 'left')
    {
        if (isRtl()) {
            return $default === 'left' ? 'text-right' : 'text-left';
        }
        return $default === 'left' ? 'text-left' : 'text-right';
    }
}

if (!function_exists('getMarginClass')) {
    /**
     * Get margin class based on locale (for RTL support)
     *
     * @param string $side
     * @param int $value
     * @return string
     */
    function getMarginClass($side, $value)
    {
        if (isRtl()) {
            $side = $side === 'left' ? 'right' : ($side === 'right' ? 'left' : $side);
        }

        $prefix = [
            'left' => 'ml',
            'right' => 'mr',
            'top' => 'mt',
            'bottom' => 'mb'
        ];

        return ($prefix[$side] ?? 'ml') . '-' . $value;
    }
}

if (!function_exists('getPaddingClass')) {
    /**
     * Get padding class based on locale (for RTL support)
     *
     * @param string $side
     * @param int $value
     * @return string
     */
    function getPaddingClass($side, $value)
    {
        if (isRtl()) {
            $side = $side === 'left' ? 'right' : ($side === 'right' ? 'left' : $side);
        }

        $prefix = [
            'left' => 'pl',
            'right' => 'pr',
            'top' => 'pt',
            'bottom' => 'pb'
        ];

        return ($prefix[$side] ?? 'pl') . '-' . $value;
    }
}

if (!function_exists('getFloatClass')) {
    /**
     * Get float class based on locale
     *
     * @param string $direction
     * @return string
     */
    function getFloatClass($direction)
    {
        if (isRtl()) {
            return $direction === 'left' ? 'float-right' : 'float-left';
        }
        return 'float-' . $direction;
    }
}

if (!function_exists('getFlexClass')) {
    /**
     * Get flex direction class based on locale
     *
     * @param string $class
     * @return string
     */
    function getFlexClass($class)
    {
        if (!isRtl()) {
            return $class;
        }

        $rtlMap = [
            'flex-row' => 'flex-row-reverse',
            'flex-row-reverse' => 'flex-row',
            'justify-start' => 'justify-end',
            'justify-end' => 'justify-start',
        ];

        return $rtlMap[$class] ?? $class;
    }
}
