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
                poppins: ['Poppins', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
                nunito: ['Nunito', 'sans-serif'],
                dmSans: ['DM Sans', 'sans-serif'],
            },
            colors: {
                'violeta-moderno': '#7C3AED',   // ejemplo violeta moderno
                'azul-confiable': '#2563EB',    // ejemplo azul confiable
                'verde-fresco': '#10B981',      // ejemplo verde fresco
                'naranja-energico': '#F97316',  // ejemplo naranja enérgico
                'rosa-creativo': '#EC4899',     // ejemplo rosa creativo
            },
        },
    },

    plugins: [forms],
};
