import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  build: {
    // Optimizaciones de producción
    target: 'esnext',
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true, // Eliminar console.log en producción
        drop_debugger: true,
      },
    },
    // Code splitting optimizado
    rollupOptions: {
      output: {
        manualChunks: {
          // Separar vendor libraries en chunks
          'vue-vendor': ['vue', 'vue-router', 'pinia'],
          'ui-vendor': ['primevue', 'vue-toastification'],
          'utils-vendor': ['axios', '@googlemaps/js-api-loader'],
        },
      },
    },
    // Optimizar assets
    assetsInlineLimit: 4096, // Inline assets menores a 4kb
    cssCodeSplit: true,
    sourcemap: false, // Desactivar sourcemaps en producción para reducir tamaño
    chunkSizeWarningLimit: 1000, // Aumentar límite de advertencia
  },
  // Optimizaciones de servidor de desarrollo
  server: {
    hmr: {
      overlay: true,
    },
  },
  // Pre-bundling optimizado
  optimizeDeps: {
    include: ['vue', 'vue-router', 'pinia', 'axios', 'primevue', 'vue-toastification'],
    exclude: ['vite-plugin-vue-devtools'],
  },
})
