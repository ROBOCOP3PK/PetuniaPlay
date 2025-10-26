<template>
  <header class="bg-white shadow-md sticky top-0 z-50">
    <!-- Top bar -->
    <div class="bg-primary text-white py-2">
      <div class="container mx-auto px-4 flex justify-between items-center text-sm">
        <div class="flex items-center space-x-4">
          <span>petuniaplay@gmail.com</span>
          <span>+57 305 759 40 88</span>
        </div>
        <div class="flex items-center space-x-4">
          <router-link to="/login" class="hover:text-beige transition">Iniciar Sesión</router-link>
          <router-link to="/register" class="hover:text-beige transition">Registrarse</router-link>
        </div>
      </div>
    </div>

    <!-- Main header -->
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl">
            PP
          </div>
          <span class="text-2xl font-bold text-primary">Petunia Play</span>
        </router-link>

        <!-- Search bar -->
        <div class="flex-1 max-w-2xl mx-8">
          <div class="relative">
            <input
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              type="text"
              placeholder="Buscar productos..."
              class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
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

        <!-- Cart & User -->
        <div class="flex items-center space-x-6">
          <router-link to="/cart" class="relative hover:text-primary transition">
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
    <nav class="bg-beige">
      <div class="container mx-auto px-4">
        <ul class="flex items-center space-x-8 py-3">
          <li>
            <router-link to="/" class="text-dark hover:text-primary font-semibold transition">
              Inicio
            </router-link>
          </li>
          <li>
            <router-link to="/products" class="text-dark hover:text-primary font-semibold transition">
              Productos
            </router-link>
          </li>
          <li v-for="category in parentCategories" :key="category.id">
            <router-link
              :to="`/category/${category.slug}`"
              class="text-dark hover:text-primary font-semibold transition"
            >
              {{ category.name }}
            </router-link>
          </li>
          <li>
            <router-link to="/contact" class="text-dark hover:text-primary font-semibold transition">
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

const router = useRouter()
const categoryStore = useCategoryStore()
const cartStore = useCartStore()

const searchQuery = ref('')

const parentCategories = computed(() => categoryStore.parentCategories)

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/products', query: { search: searchQuery.value } })
  }
}

onMounted(() => {
  categoryStore.fetchCategories()
})
</script>
