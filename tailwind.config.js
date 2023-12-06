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
                    100: '#F5F5F5',
                    200: '#929292',
                    300: '#A8A8A8',
                    900: '#3B3B3B',
                    'separator': '#E7E7E7'
                },
                green: {
                    '50': '#E9FBF4',
                    100: '#EFFBE8',
                    200: '#CCD615',
                    300: '#71D080',
                    400: '#5ED615',
                    500: '#25D990',
                    600: '#71D080'
                },
                red: {
                    300: '#EF2A56',
                },
                blue: {
                    300: '#60CFFF',
                    400: '#1A99FF',
                    500: '#25A3D9',
                    '50': '#E9F6FB'
                },
                purple: {
                    300: '#6398FF'
                }
            },
            boxShadow: {
                'head': '0px 4px 4px 0px rgba(0, 0, 0, 0.25);'
            }
        },
    },

    plugins: [forms],
};
