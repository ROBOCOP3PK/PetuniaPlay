/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#A97447', // Marrón caramelo
          dark: '#8B5E3C',
          light: '#C08B5F',
        },
        cream: '#F8F4EC',  // Blanco crema
        dark: '#2B2B2B',   // Negro profundo
        beige: '#D6B890',  // Beige cálido
        gray: {
          soft: '#B0A99F', // Gris suave
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
