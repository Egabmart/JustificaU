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
            // Nos aseguramos de que esta sección exista
            colors: {
                'uam-blue': {
                    '500': '#00a99d', // Color principal del logo
                    '600': '#00938A', // Tono para el hover
                },
            },
        },
    },

    plugins: [forms],
};