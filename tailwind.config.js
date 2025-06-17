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
            colors: {
                'custom-cyan': '#0099a8',
                'custom-cyan-dark': '#007a86',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // AÑADIR ESTA SECCIÓN DE COLORES
            colors: {
                'uam-blue': {
                    '500': '#00a99d', // Color principal del logo de la UAM
                    '600': '#00938A', // Un tono un poco más oscuro para el hover
                },
            },
        },
    },

    plugins: [forms],
};