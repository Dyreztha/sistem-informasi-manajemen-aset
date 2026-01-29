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
            keyframes:{
                fadeinUpper: {
                    '0%' : {
                        opacity: '0',
                        transform: 'translateY(-30px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)'
                    }

                },
                fadeinLower: {
                    '0%' : {
                        opacity: '0',
                        transform: 'translateX(-100px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)'
                    }

                },
                fadeinDashboardAdmin: {
                    '0%' : {
                        opacity: '0',
                        transform: 'translateY(-100px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)'
                    }
                }
            },
                animation: {
                    fadeinUpper: 'fadeinUpper 1.5s ease-out forwards',
                    fadeinLower: 'fadeinLower 0.5s ease-out backwards 2s',
                    fadeinDashboardAdmin: 'fadeinDashboardAdmin 0.5s ease-out forwards 0.5s',
                },







        },

    },


    plugins: [forms],
};
