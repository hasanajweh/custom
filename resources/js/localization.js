
class Localization {
    constructor() {
        this.locale = window.appLocale || 'en';
        this.isRtl = window.isRtl || false;
        this.translations = window.translations || {};
        this.fallbackLocale = 'en';
    }

    /**
     * Translate a key
     */
    __(key, replacements = {}) {
        let translation = this.translations[key] || key;

        // Replace placeholders
        for (const [placeholder, value] of Object.entries(replacements)) {
            translation = translation.replace(`:${placeholder}`, value);
        }

        return translation;
    }

    /**
     * Translate with pluralization
     */
    trans_choice(key, count, replacements = {}) {
        // Simple pluralization logic - can be enhanced
        const translation = this.__(key, { ...replacements, count });

        if (count === 1) {
            return translation;
        }

        // Try to get plural version
        const pluralKey = key + '_plural';
        return this.__(pluralKey, { ...replacements, count });
    }

    /**
     * Format number based on locale
     */
    formatNumber(number, options = {}) {
        const defaults = {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        };

        const formatter = new Intl.NumberFormat(this.locale, { ...defaults, ...options });
        let formatted = formatter.format(number);

        // Convert to Arabic numerals if needed
        if (this.locale === 'ar' && window.useArabicNumerals) {
            formatted = this.convertToArabicNumerals(formatted);
        }

        return formatted;
    }

    /**
     * Format date based on locale
     */
    formatDate(date, options = {}) {
        if (typeof date === 'string') {
            date = new Date(date);
        }

        const defaults = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };

        const formatter = new Intl.DateTimeFormat(this.locale, { ...defaults, ...options });
        let formatted = formatter.format(date);

        // Convert to Arabic numerals if needed
        if (this.locale === 'ar' && window.useArabicNumerals) {
            formatted = this.convertToArabicNumerals(formatted);
        }

        return formatted;
    }

    /**
     * Format relative time (e.g., "2 hours ago")
     */
    formatRelativeTime(date) {
        if (typeof date === 'string') {
            date = new Date(date);
        }

        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60
        };

        for (const [unit, seconds] of Object.entries(intervals)) {
            const interval = Math.floor(diffInSeconds / seconds);

            if (interval >= 1) {
                return this.formatRelativeUnit(unit, interval);
            }
        }

        return this.__('just_now');
    }

    /**
     * Format relative time unit
     */
    formatRelativeUnit(unit, count) {
        if (this.locale === 'ar') {
            const units = {
                year: count === 1 ? 'سنة' : count === 2 ? 'سنتان' : 'سنوات',
                month: count === 1 ? 'شهر' : count === 2 ? 'شهران' : 'أشهر',
                week: count === 1 ? 'أسبوع' : count === 2 ? 'أسبوعان' : 'أسابيع',
                day: count === 1 ? 'يوم' : count === 2 ? 'يومان' : 'أيام',
                hour: count === 1 ? 'ساعة' : count === 2 ? 'ساعتان' : 'ساعات',
                minute: count === 1 ? 'دقيقة' : count === 2 ? 'دقيقتان' : 'دقائق'
            };

            const unitName = units[unit] || unit;
            const countStr = window.useArabicNumerals ? this.convertToArabicNumerals(count.toString()) : count;

            if (count === 1) {
                return `منذ ${unitName}`;
            } else if (count === 2) {
                return `منذ ${unitName}`;
            } else {
                return `منذ ${countStr} ${unitName}`;
            }
        }

        // English
        const suffix = count === 1 ? '' : 's';
        return `${count} ${unit}${suffix} ago`;
    }

    /**
     * Convert Western numerals to Arabic numerals
     */
    convertToArabicNumerals(text) {
        const western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        let result = text.toString();
        for (let i = 0; i < western.length; i++) {
            result = result.replace(new RegExp(western[i], 'g'), arabic[i]);
        }

        return result;
    }

    /**
     * Format file size with localization
     */
    formatFileSize(bytes, precision = 2) {
        if (bytes === 0) {
            return this.locale === 'ar' ? '٠ بايت' : '0 B';
        }

        const units = this.locale === 'ar' ?
            ['بايت', 'ك.بايت', 'م.بايت', 'ج.بايت', 'ت.بايت'] :
            ['B', 'KB', 'MB', 'GB', 'TB'];

        let size = bytes;
        let unitIndex = 0;

        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }

        let formatted = `${size.toFixed(precision)} ${units[unitIndex]}`;

        if (this.locale === 'ar' && window.useArabicNumerals) {
            formatted = this.convertToArabicNumerals(formatted);
        }

        return formatted;
    }

    /**
     * Get direction class
     */
    getDirectionClass() {
        return this.isRtl ? 'rtl' : 'ltr';
    }

    /**
     * Get text alignment class
     */
    getTextAlignClass() {
        return this.isRtl ? 'text-right' : 'text-left';
    }

    /**
     * Get margin class (for icons, etc.)
     */
    getMarginClass() {
        return this.isRtl ? 'ml-2' : 'mr-2';
    }

    /**
     * Get flex direction for RTL
     */
    getFlexDirection() {
        return this.isRtl ? 'flex-row-reverse' : 'flex-row';
    }

    /**
     * Apply RTL transformations to element
     */
    applyRtlTransforms(element) {
        if (this.isRtl) {
            element.classList.add('rtl');
            element.style.direction = 'rtl';
        }
    }

    /**
     * Format currency based on locale
     */
    formatCurrency(amount, currency = 'USD') {
        const formatter = new Intl.NumberFormat(this.locale, {
            style: 'currency',
            currency: currency
        });

        let formatted = formatter.format(amount);

        if (this.locale === 'ar' && window.useArabicNumerals) {
            formatted = this.convertToArabicNumerals(formatted);
        }

        return formatted;
    }

    /**
     * Validate form with localized messages
     */
    validateForm(form, rules) {
        const errors = {};

        for (const [field, fieldRules] of Object.entries(rules)) {
            const input = form.querySelector(`[name="${field}"]`);
            const value = input ? input.value.trim() : '';

            for (const rule of fieldRules) {
                if (rule.type === 'required' && !value) {
                    errors[field] = this.__('validation.required');
                    break;
                }

                if (rule.type === 'email' && value && !this.isValidEmail(value)) {
                    errors[field] = this.__('validation.email');
                    break;
                }

                if (rule.type === 'min' && value.length < rule.value) {
                    errors[field] = this.__('validation.min', { min: rule.value });
                    break;
                }

                if (rule.type === 'max' && value.length > rule.value) {
                    errors[field] = this.__('validation.max', { max: rule.value });
                    break;
                }
            }
        }

        return errors;
    }

    /**
     * Validate email format
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Show form validation errors
     */
    showFormErrors(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });

        // Show new errors
        for (const [field, message] of Object.entries(errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                // Highlight input
                input.classList.remove('border-gray-300');
                input.classList.add('border-red-500');

                // Add error message
                const errorDiv = document.createElement('p');
                errorDiv.className = `error-message text-sm text-red-600 flex items-center mt-1 ${this.getTextAlignClass()}`;
                errorDiv.innerHTML = `<i class="ri-error-warning-line ${this.getMarginClass()}"></i>${message}`;

                input.parentNode.appendChild(errorDiv);
            }
        }
    }

    /**
     * Format notification text with user data
     */
    formatNotification(template, data) {
        let message = template;

        for (const [key, value] of Object.entries(data)) {
            message = message.replace(`{{${key}}}`, value);
        }

        return message;
    }

    /**
     * Set page title with locale support
     */
    setPageTitle(title, includeAppName = true) {
        const appName = this.__('common.app_name') || 'Scholder';
        document.title = includeAppName ? `${title} - ${appName}` : title;
    }

    /**
     * Update URL with locale parameter
     */
    updateUrlWithLocale(url) {
        const urlObj = new URL(url, window.location.origin);
        urlObj.searchParams.set('lang', this.locale);
        return urlObj.toString();
    }

    /**
     * Load translations dynamically
     */
    async loadTranslations(group = 'common') {
        try {
            const response = await fetch(`/api/translations/${this.locale}/${group}`);
            const translations = await response.json();

            // Merge with existing translations
            this.translations = { ...this.translations, ...translations };

            return translations;
        } catch (error) {
            console.warn('Failed to load translations:', error);
            return {};
        }
    }

    /**
     * Switch locale dynamically
     */
    async switchLocale(newLocale) {
        if (newLocale === this.locale) return;

        // Update locale
        this.locale = newLocale;
        this.isRtl = ['ar', 'he', 'fa', 'ur'].includes(newLocale);

        // Update HTML attributes
        document.documentElement.lang = newLocale;
        document.documentElement.dir = this.isRtl ? 'rtl' : 'ltr';
        document.documentElement.className = this.getDirectionClass();

        // Load new translations
        await this.loadTranslations();

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('localeChanged', {
            detail: { locale: newLocale, isRtl: this.isRtl }
        }));
    }
}

// Initialize global localization instance
window.__ = new Localization();

// Export for modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Localization;
}

// Usage examples and helper functions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-update timestamps
    const timestamps = document.querySelectorAll('[data-timestamp]');
    timestamps.forEach(el => {
        const date = el.getAttribute('data-timestamp');
        el.textContent = window.__.formatRelativeTime(date);
    });

    // Auto-format numbers
    const numbers = document.querySelectorAll('[data-format-number]');
    numbers.forEach(el => {
        const number = parseFloat(el.getAttribute('data-format-number'));
        el.textContent = window.__.formatNumber(number);
    });

    // Auto-format file sizes
    const fileSizes = document.querySelectorAll('[data-file-size]');
    fileSizes.forEach(el => {
        const bytes = parseInt(el.getAttribute('data-file-size'));
        el.textContent = window.__.formatFileSize(bytes);
    });

    // Form validation
    const forms = document.querySelectorAll('[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const rules = JSON.parse(form.getAttribute('data-validate') || '{}');
            const errors = window.__.validateForm(form, rules);

            if (Object.keys(errors).length > 0) {
                e.preventDefault();
                window.__.showFormErrors(form, errors);

                // Show toast notification
                showToast(window.__.__('validation.form_has_errors'), 'error');
            }
        });
    });

    // Language switcher
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-switch-locale]')) {
            e.preventDefault();
            const newLocale = e.target.getAttribute('data-switch-locale');
            window.__.switchLocale(newLocale);
        }
    });
});

// Toast notification function
function showToast(message, type = 'info', duration = 5000) {
    const toast = document.createElement('div');
    const typeClasses = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };

    const typeIcons = {
        success: 'ri-checkbox-circle-fill',
        error: 'ri-error-warning-fill',
        warning: 'ri-alert-fill',
        info: 'ri-information-fill'
    };

    toast.className = `fixed top-4 ${window.__.isRtl ? 'left-4' : 'right-4'} max-w-sm w-full border rounded-lg p-4 shadow-lg z-50 transform transition-all duration-300 ${typeClasses[type]} translate-y-0 opacity-100`;

    toast.innerHTML = `
        <div class="flex items-start ${window.__.getFlexDirection()}">
            <i class="${typeIcons[type]} text-xl ${window.__.getMarginClass()}"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ${window.__.isRtl ? 'mr-auto' : 'ml-auto'}">
                <i class="ri-close-line text-lg opacity-70 hover:opacity-100"></i>
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-remove after duration
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.transform = `translateY(-100%) ${window.__.isRtl ? 'translateX(100%)' : 'translateX(-100%)'}`;
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, duration);
}

// Export toast function globally
window.showToast = showToast;
