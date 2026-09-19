/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.php',
    './modules/Admin/views/**/*.php',
  ],
  safelist: [
    // Dynamic status / accent classes used in admin badges and sector colors
    'bg-emerald-100', 'text-emerald-800',
    'bg-amber-100', 'text-amber-800',
    'bg-red-100', 'text-red-800',
    'bg-blue-100', 'text-blue-800',
    'bg-purple-100', 'text-purple-800',
    'bg-slate-100', 'text-slate-800',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#FAFAF9',
          100: '#F3F2EF',
          200: '#EBEAE6',
          300: '#D4D2CC',
          400: '#A8A6A0',
          500: '#5E5E5E',
          600: '#4A4A4C',
          700: '#3A3A3C',
          800: '#2A2928',
          900: '#1C1B1A',
          950: '#141312',
        },
        orange: {
          50: '#FCEDEA',
          400: '#F28A7A',
          500: '#EA4C37',
          600: '#D33D2A',
        },
        gold: {
          50: '#FCEDEA',
          400: '#F28A7A',
          500: '#EA4C37',
          600: '#D33D2A',
        },
      },
      fontFamily: {
        sans: ['Montserrat', 'system-ui', 'sans-serif'],
        display: ['Montserrat', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
