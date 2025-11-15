const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const forms = require('@tailwindcss/forms');
const typography = require('@tailwindcss/typography');
const aspectRatio = require('@tailwindcss/aspect-ratio');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                // Primary brand colors
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9', // Main primary color
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },

                // Secondary colors for different roles
                secondary: {
                    admin: {
                        500: '#8b5cf6', // Violet for admin
                        600: '#7c3aed',
                    },
                    teacher: {
                        500: '#10b981', // Emerald for teachers
                        600: '#059669',
                    },
                    supervisor: {
                        500: '#f59e0b', // Amber for supervisors
                        600: '#d97706',
                    },
                    student: {
                        500: '#3b82f6', // Blue for students
                        600: '#2563eb',
                    }
                },

                // Status colors
                success: colors.emerald,
                warning: colors.amber,
                danger: colors.rose,
                info: colors.sky,

                // Extended grayscale
                gray: colors.slate,

                // Background colors
                background: {
                    light: '#f8fafc',
                    dark: '#0f172a',
                }
            },

            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Cal Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },

            boxShadow: {
                'glow': '0 0 15px rgba(59, 130, 246, 0.5)',
                'glow-sm': '0 0 10px rgba(59, 130, 246, 0.3)',
                'glow-lg': '0 0 20px rgba(59, 130, 246, 0.7)',
            },

            animation: {
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },

            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            }
        },
    },

    plugins: [
        forms,
        typography,
        aspectRatio,
        require('tailwindcss-animate'),
        require('@tailwindcss/line-clamp'),
    ],
};
