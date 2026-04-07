import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#3525cd',
                secondary: '#58579b',
                surface: '#f7f9fb',
                'surface-container': '#eceef0',
                'surface-container-high': '#e6e8ea',
                'surface-container-low': '#f2f4f6',
                'on-surface': '#191c1e',
                'on-surface-variant': '#464555',
                error: '#ba1a1a',
            },
            fontFamily: {
                headline: ['Manrope', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [forms, containerQueries],
};
