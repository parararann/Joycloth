/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#f2fbe0',
          100: '#e1f6bc',
          200: '#c6ef8d',
          300: '#a3e455',
          400: '#86d628',
          500: '#69ba10', // Acid Green vibe
          600: '#50930a',
          700: '#3e700b',
          800: '#34590e',
          900: '#2d4b10',
          950: '#142a04',
        },
        accent: '#ff2e93', // Hot pink
        dark: {
          50:  '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#111111', // Almost pitch black
          950: '#000000',
        },
      },
      fontFamily: {
        sans: ['Space Grotesk', 'system-ui', 'sans-serif'],
        display: ['Syne', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'brutal': '4px 4px 0px rgba(0, 0, 0, 1)',
        'brutal-lg': '8px 8px 0px rgba(0, 0, 0, 1)',
        'brutal-sm': '2px 2px 0px rgba(0, 0, 0, 1)',
        'brutal-hover': '2px 2px 0px rgba(0, 0, 0, 1)',
      },
      borderWidth: {
        '3': '3px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
