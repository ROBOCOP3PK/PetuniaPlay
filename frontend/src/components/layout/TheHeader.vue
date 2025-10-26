<template>
  <header class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50 transition-colors duration-200">
    <!-- Top bar -->
    <div class="bg-primary dark:bg-primary-dark text-white py-2">
      <div class="container mx-auto px-4 flex justify-between items-center text-sm">
        <div class="flex items-center space-x-4">
          <span>petuniaplay@gmail.com</span>
          <span>+57 305 759 4088</span>
        </div>
        <!-- Auth Menu -->
        <div class="flex items-center space-x-4">
          <!-- If NOT authenticated -->
          <template v-if="!authStore.isAuthenticated">
            <router-link to="/login" class="hover:text-beige transition">Iniciar Sesión</router-link>
            <router-link to="/register" class="hover:text-beige transition">Registrarse</router-link>
          </template>
          <!-- If authenticated -->
          <template v-else>
            <div class="relative">
              <button
                @click="showUserMenu = !showUserMenu"
                class="hover:text-beige transition flex items-center gap-2"
              >
                <span>👤 {{ authStore.userName }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown Menu -->
              <div
                v-if="showUserMenu"
                @click="showUserMenu = false"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50"
              >
                <router-link
                  to="/account"
                  class="block px-4 py-2 text-dark dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                  Mi Cuenta
                </router-link>
                <router-link
                  to="/account"
                  class="block px-4 py-2 text-dark dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                  Mis Pedidos
                </router-link>
                <!-- Admin Panel Link (only for manager and admin) -->
                <router-link
                  v-if="authStore.hasManagerAccess"
                  to="/admin"
                  class="block px-4 py-2 text-primary dark:text-primary-light font-semibold hover:bg-primary hover:bg-opacity-10 dark:hover:bg-opacity-20 transition border-t dark:border-gray-700"
                >
                  📊 Panel Admin
                </router-link>
                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 dark:hover:bg-opacity-20 transition border-t dark:border-gray-700"
                >
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Main header -->
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-12 h-12 bg-primary dark:bg-primary-light rounded-full flex items-center justify-center text-white font-bold text-xl">
            PP
          </div>
          <span class="text-2xl font-bold text-primary dark:text-primary-light">Petunia Play</span>
        </router-link>

        <!-- Search bar -->
        <div class="flex-1 max-w-2xl mx-8">
          <div class="relative">
            <input
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              type="text"
              placeholder="Buscar productos..."
              class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
            />
            <button
              @click="handleSearch"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 text-primary hover:text-primary-dark"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Cart, Wishlist & User -->
        <div class="flex items-center space-x-6">
          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Wishlist Icon (only for authenticated users) -->
          <router-link
            v-if="authStore.isAuthenticated"
            to="/wishlist"
            class="relative hover:text-primary transition"
            title="Lista de deseos"
          >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span
              v-if="wishlistStore.itemCount > 0"
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ wishlistStore.itemCount }}
            </span>
          </router-link>

          <!-- Cart Icon -->
          <router-link to="/cart" class="relative hover:text-primary transition" title="Carrito">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span
              v-if="cartStore.itemCount > 0"
              class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ cartStore.itemCount }}
            </span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-beige dark:bg-gray-800">
      <div class="container mx-auto px-4">
        <ul class="flex items-center space-x-8 py-3">
          <li>
            <router-link to="/" class="text-dark dark:text-gray-200 hover:text-primary dark:hover:text-primary-light font-semibold transition">
              Inicio
            </router-link>
          </li>
          <li>
            <router-link to="/products" class="text-dark dark:text-gray-200 hover:text-primary dark:hover:text-primary-light font-semibold transition">
              Productos
            </router-link>
          </li>
          <li v-for="category in parentCategories" :key="category.id">
            <router-link
              :to="`/category/${category.slug}`"
              class="text-dark dark:text-gray-200 hover:text-primary dark:hover:text-primary-light font-semibold transition"
            >
              {{ category.name }}
            </router-link>
          </li>
          <li>
            <router-link to="/contact" class="text-dark dark:text-gray-200 hover:text-primary dark:hover:text-primary-light font-semibold transition">
              Contacto
            </router-link>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCategoryStore } from '../../stores/categoryStore'
import { useCartStore } from '../../stores/cartStore'
import { useWishlistStore } from '../../stores/wishlistStore'
import { useAuthStore } from '../../stores/authStore'
import { useToast } from 'vue-toastification'
import ThemeToggle from '../ThemeToggle.vue'

const router = useRouter()
const categoryStore = useCategoryStore()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useAuthStore()
const toast = useToast()

const searchQuery = ref('')
const showUserMenu = ref(false)

const parentCategories = computed(() => categoryStore.parentCategories)

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/products', query: { search: searchQuery.value } })
  }
}

const handleLogout = async () => {
  await authStore.logout()
  showUserMenu.value = false
  toast.info('Sesión cerrada exitosamente')
  router.push('/')
}

onMounted(() => {
  categoryStore.fetchCategories()
})
</script>
