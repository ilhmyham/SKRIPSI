/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: 'oklch(0.55 0.15 165)',
                    dark: 'oklch(0.45 0.18 165)',
                },
                secondary: 'oklch(0.65 0.12 165)',
                accent: 'oklch(0.75 0.10 165)',
                surface: 'oklch(0.96 0.01 165)',
            }
        },
    },
    plugins: [],
}
