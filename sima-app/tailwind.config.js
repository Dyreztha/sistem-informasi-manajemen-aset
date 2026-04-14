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
                },
                fadeoutupperwelcome: {
                    '0%' : {
                        opacity: '1',
                        transform: 'translateY(0)'
                    },
                      '100%': {
                        opacity: '0',
                        transform: 'translateY(-100px)'
                    }
                },
                fadeoutlowerwelcome: {
                    '0%' : {
                        opacity: '1',
                        transform: 'translateY(0)'
                    },
                      '100%': {
                        opacity: '0',
                        transform: 'translateY(100px)'
                    }
                },
                fadeinLogin: {
                    '0%': {
                        opacity: '0',
                        transform: 'scale(0.5)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'scale(1)'
                    }

                },
                fadeputleftlogin: {
                    '0%': {
                        opacity: '1',
                        transform: 'translateX(0)'
                    },
                    '100%': {
                        opacity: '0',
                        transform: 'translateX(-100px)'
                    }
                },
                fadeputrightlogin: {
                    '0%': {
                        opacity: '1',
                        transform: 'translateX(0)'
                    },
                    '100%': {
                        opacity: '0',
                        transform: 'translateX(100px)'
                    }
                },
                fadeoutbglogin: {
                    '0%': {
                        opacity: '1',
                    },
                    '100%': {
                        opacity: '0',
                    }
                },
                fadeleftdashboard: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(-100px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)'
                    }
                },
                  fadeinUpperdashboard: {
                    '0%' : {
                        opacity: '0',
                        transform: 'translateY(-30px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)'
                    }

                },


            },
                animation: {
                    fadeinUpper: 'fadeinUpper 1.5s ease-out forwards',
                    fadeinLower: 'fadeinLower 0.5s ease-out backwards 2s',
                    fadeinDashboardAdmin: 'fadeinDashboardAdmin 0.5s ease-out forwards 0.5s',
                    fadeoutupperwelcome: 'fadeoutupperwelcome 0.5s ease-out forwards 0.2s',
                    fadeoutlowerwelcome: 'fadeoutlowerwelcome 0.5s ease-out forwards 0.2s',
                    fadeinLogin: 'fadeinLogin 0.5s ease-out forwards',
                    fadeputleftlogin: 'fadeputleftlogin 0.5s ease-out forwards',
                    fadeputrightlogin: 'fadeputrightlogin 0.5s ease-out forwards',
                    fadeoutbglogin: 'fadeoutbglogin 0.5s ease-out forwards',
                    fadeleftdashboard: 'fadeleftdashboard 0.5s ease-out forwards',
                    fadeinUpperdashboard: 'fadeinUpperdashboard 0.5s ease-out both 0.5s'
                },







        },

    },


    plugins: [forms],
};
