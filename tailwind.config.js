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
                    '500': '#0099a8', // Color principal del logo
                    '600': '#007a86', // Tono para el hover
                },
            },
        },
    },

    plugins: [forms],
};