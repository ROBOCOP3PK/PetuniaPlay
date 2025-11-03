<template>
  <footer class="bg-dark text-white mt-16">
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <img
              src="/assets/images/logo_full_sin_fondo.jpeg"
              alt="Petunia Play Logo"
              class="h-10 w-auto object-contain"
            />
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
              <i class="pi pi-envelope text-base"></i>
              <span>contacto@petuniaplay.com</span>
            </li>
            <li class="flex items-center space-x-2">
              <i class="pi pi-phone text-base"></i>
              <span>+57 305 759 4088</span>
            </li>
            <li class="flex items-center space-x-2">
              <i class="pi pi-map-marker text-base"></i>
              <span>Bogotá, Colombia</span>
            </li>
          </ul>

          <!-- Social Media -->
          <div class="flex space-x-4 mt-4">
            <a href="#" class="text-gray-300 hover:text-primary transition">
              <i class="pi pi-facebook text-2xl"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-primary transition">
              <i class="pi pi-instagram text-2xl"></i>
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
              <i class="pi pi-github text-2xl"></i>
              <span>Ver Mi Portafolio</span>
              <i class="pi pi-external-link text-xl"></i>
            </a>

            <!-- Tech Stack Icons -->
            <div class="mt-6 flex justify-center items-center gap-6 text-gray-400 text-sm">
              <div class="flex items-center gap-2">
                <i class="pi pi-bolt text-base"></i>
                <span>Vue.js</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-bolt text-base"></i>
                <span>Laravel</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-palette text-base"></i>
                <span>Tailwind CSS</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-mobile text-base"></i>
                <span>Responsive</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
        <p>&copy; {{ currentYear }} Petunia Play. Todos los derechos reservados.</p>
        <p class="mt-2 flex items-center justify-center gap-1">Hecho con <i class="pi pi-heart-fill text-red-500"></i> para las mascotas</p>
        <p class="mt-3 text-xs">
          Desarrollado por
          <a
            href="https://robocop3pk.github.io/Portafolio/"
            target="_blank"
            rel="noopener noreferrer"
            class="text-primary hover:text-primary-dark font-semibold transition"
          >
            David Gonzalez
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
