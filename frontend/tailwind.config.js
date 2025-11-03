/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // Habilitar dark mode basado en clase
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#6B5D54', // Gris oscuro con toque café (inspirado en Beagle)
          dark: '#4A423C',    // Gris carbón oscuro
          light: '#8B7D74',   // Gris café claro
        },
        cream: '#F8F4EC',     // Blanco crema (mantiene modo claro)
        dark: {
          DEFAULT: '#2B2826', // Negro con tinte cálido
          soft: '#3A3632',    // Gris oscuro cálido
          lighter: '#4F4943', // Gris medio cálido
        },
        beige: '#D6C4B0',     // Beige suave Beagle
        gray: {
          soft: '#A8A09A',    // Gris cálido suave
          warm: '#8A827C',    // Gris tibio medio
        },
        accent: {
          DEFAULT: '#9B8B7E', // Café grisáceo suave
          light: '#B5A99D',   // Café gris claro
        },
        admin: {
          price: {
            light: '#6B5D54',  // Color para precios en modo claro (primary)
            dark: '#fbbf24',   // Color para precios en modo oscuro (amber-400)
          },
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
  // Optimizaciones de producción
  future: {
    hoverOnlyWhenSupported: true,
  },
  corePlugins: {
    // Deshabilitar plugins no utilizados para reducir el tamaño del bundle
    preflight: true, // Mantener para reset CSS
  },
}
