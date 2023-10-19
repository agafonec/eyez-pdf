/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                green: {
                    100: '#E0EBD9',
                    200: '#CCD615',
                    400: '#5ED615',
                    500: '#25D990'
                },
                red: {
                    500: '#EF2A56',
                }
            },
            boxShadow: {
                'head': '0px 4px 4px 0px rgba(0, 0, 0, 0.25);'
            }
        },
    },
    plugins: [],
}

