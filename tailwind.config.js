import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                'pdf-container': '1400px'
            },
            colors: {
                lime: {
                    200: '#FAFBE8',
                    400: '#CCD615'
                },
                rose: {
                    200: '#FDF2F1',
                    400: '#ED7974'
                },
                amber: {
                    200: '#FDF4F0',
                    400: '#EF8D6A'
                },
                gray: {
                    '50': '#FAFAFA',
                    200: '#929292',
                    300: '#A8A8A8',
                    900: '#3B3B3B',
                },
                green: {
                    '50': '#E9FBF4',
                    100: '#EFFBE8',
                    200: '#CCD615',
                    300: '#71D080',
                    400: '#5ED615',
                    500: '#25D990'
                },
                red: {
                    300: '#EF2A56',
                }
            },
            boxShadow: {
                'head': '0px 4px 4px 0px rgba(0, 0, 0, 0.25);'
            }
        },
    },

    plugins: [forms],
};
