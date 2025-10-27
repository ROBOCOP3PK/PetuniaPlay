<template>
  <footer class="bg-dark text-white mt-16">
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
              PP
            </div>
            <span class="text-xl font-bold">Petunia Play</span>
          </div>
          <p class="text-gray-300 text-sm">
            Tu tienda de confianza para productos de mascotas. Calidad y amor para tus compañeros peludos.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="font-bold text-lg mb-4">Enlaces Rápidos</h3>
          <ul class="space-y-2 text-sm">
            <li>
              <router-link to="/" class="text-gray-300 hover:text-primary transition">
                Inicio
              </router-link>
            </li>
            <li>
              <router-link to="/products" class="text-gray-300 hover:text-primary transition">
                Productos
              </router-link>
            </li>
            <li>
              <router-link to="/about" class="text-gray-300 hover:text-primary transition">
                Sobre Nosotros
              </router-link>
            </li>
            <li>
              <router-link to="/faq" class="text-gray-300 hover:text-primary transition">
                Preguntas Frecuentes
              </router-link>
            </li>
            <li>
              <router-link to="/contact" class="text-gray-300 hover:text-primary transition">
                Contacto
              </router-link>
            </li>
            <li>
              <router-link to="/tracking" class="text-gray-300 hover:text-primary transition">
                Rastrear Pedido
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h3 class="font-bold text-lg mb-4">Legal</h3>
          <ul class="space-y-2 text-sm">
            <li>
              <router-link to="/terms" class="text-gray-300 hover:text-primary transition">
                Términos y Condiciones
              </router-link>
            </li>
            <li>
              <router-link to="/privacy" class="text-gray-300 hover:text-primary transition">
                Política de Privacidad
              </router-link>
            </li>
            <li>
              <router-link to="/returns" class="text-gray-300 hover:text-primary transition">
                Devoluciones y Reembolsos
              </router-link>
            </li>
            <li>
              <router-link to="/shipping" class="text-gray-300 hover:text-primary transition">
                Política de Envíos
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="font-bold text-lg mb-4">Contacto</h3>
          <ul class="space-y-2 text-sm text-gray-300">
            <li class="flex items-center space-x-2">
              <span>📧</span>
              <span>contacto@petuniaplay.com</span>
            </li>
            <li class="flex items-center space-x-2">
              <span>📞</span>
              <span>+57 305 759 4088</span>
            </li>
            <li class="flex items-center space-x-2">
              <span>📍</span>
              <span>Bogotá, Colombia</span>
            </li>
          </ul>

          <!-- Social Media -->
          <div class="flex space-x-4 mt-4">
            <a href="#" class="text-gray-300 hover:text-primary transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-300 hover:text-primary transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Developer CTA Section -->
      <div class="mt-12 mb-8">
        <div class="bg-gradient-to-r from-primary/20 via-primary/10 to-primary/20 border-2 border-primary/30 rounded-xl p-8 text-center relative overflow-hidden">
          <!-- Decorative elements -->
          <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full -mr-16 -mt-16"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full -ml-12 -mb-12"></div>

          <div class="relative z-10">
            <!-- Icon/Emoji -->
            <div class="text-5xl mb-4">💻✨</div>

            <!-- Main Message -->
            <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">
              ¿Te gusta este sitio web?
            </h3>
            <p class="text-gray-300 text-lg mb-2">
              ¿Quieres uno igual o mejor para tu negocio?
            </p>
            <p class="text-gray-400 text-sm mb-6 max-w-2xl mx-auto">
              Este sitio fue desarrollado con tecnologías modernas: Vue.js, Laravel, y diseño responsive.
              Puedo crear la plataforma perfecta para tu emprendimiento.
            </p>

            <!-- CTA Button -->
            <a
              href="https://robocop3pk.github.io/Portafolio/"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-3 bg-primary hover:bg-primary-dark text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
            >
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
              <span>Ver Mi Portafolio</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>

            <!-- Tech Stack Icons -->
            <div class="mt-6 flex justify-center items-center gap-6 text-gray-400 text-sm">
              <div class="flex items-center gap-2">
                <span>🚀</span>
                <span>Vue.js</span>
              </div>
              <div class="flex items-center gap-2">
                <span>⚡</span>
                <span>Laravel</span>
              </div>
              <div class="flex items-center gap-2">
                <span>🎨</span>
                <span>Tailwind CSS</span>
              </div>
              <div class="flex items-center gap-2">
                <span>📱</span>
                <span>Responsive</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
        <p>&copy; {{ currentYear }} Petunia Play. Todos los derechos reservados.</p>
        <p class="mt-2">Hecho con ❤️ para las mascotas</p>
        <p class="mt-3 text-xs">
          Desarrollado por
          <a
            href="https://robocop3pk.github.io/Portafolio/"
            target="_blank"
            rel="noopener noreferrer"
            class="text-primary hover:text-primary-dark font-semibold transition"
          >
            David González
          </a>
          • Full Stack Developer
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'

const currentYear = computed(() => new Date().getFullYear())
</script>
