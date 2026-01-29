/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: 'oklch(0.55 0.15 165)',
                secondary: 'oklch(0.65 0.12 165)',
                background: 'oklch(0.98 0.01 165)',
                'text-primary': 'oklch(0.25 0.01 260)',
                'text-secondary': 'oklch(0.45 0.01 260)',
                'text-muted': 'oklch(0.60 0.01 260)',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            fontSize: {
                base: '1.125rem', // 18px untuk readability
            },
            spacing: {
                'touch': '3rem', // 48px minimum touch target
            },
            borderRadius: {
                'card': '1rem',
            },
        },
    },
    plugins: [],
}
