import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#F4F7FB',
                    100: '#EAF2FB',
                    200: '#C7DBF2',
                    300: '#94BCE4',
                    400: '#5A96D2',
                    500: '#3575BE',
                    600: '#255CA0',
                    700: '#1F4A80',
                    800: '#1E3A5F',
                    900: '#152943',
                    950: '#0D1A2C',
                },
                brand: {
                    navy: '#1E3A5F',
                    light: '#EAF2FB',
                    bg: '#F7F9FC',
                    emerald: '#10B981',
                },
            },
        },
    },

    plugins: [forms],
};
