<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 w-64 bg-dark dark:bg-gray-950 text-white transform transition-transform duration-300 ease-in-out z-30"
      :class="{ '-translate-x-full': !sidebarOpen }"
    >
      <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
          <router-link to="/" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center font-bold">
              PP
            </div>
            <span class="text-xl font-bold">Admin Panel</span>
          </router-link>
          <button @click="sidebarOpen = false" class="lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          <router-link
            to="/admin"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-semibold">Dashboard</span>
          </router-link>

          <router-link
            to="/admin/products"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="font-semibold">Productos</span>
          </router-link>

          <router-link
            to="/admin/categories"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span class="font-semibold">Categorías</span>
          </router-link>

          <router-link
            to="/admin/orders"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="font-semibold">Pedidos</span>
          </router-link>

          <router-link
            to="/admin/coupons"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span class="font-semibold">Cupones</span>
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin/users"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="font-semibold">Usuarios</span>
          </router-link>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-700">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center font-bold">
              {{ authStore.userName.charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold truncate">{{ authStore.userName }}</p>
              <p class="text-xs text-gray-400 capitalize">{{ authStore.userRole }}</p>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64">
      <!-- Top Bar -->
      <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-20 transition-colors duration-200">
        <div class="flex items-center justify-between px-6 py-4">
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <div class="flex items-center space-x-4">
            <ThemeToggle />
            <router-link
              to="/"
              class="text-gray-600 hover:text-primary transition"
              title="Ver tienda"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </router-link>
            <button
              @click="handleLogout"
              class="text-gray-600 hover:text-red-600 transition"
              title="Cerrar sesión"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-6">
        <slot />
      </main>
    </div>

    <!-- Overlay for mobile -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
    ></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'
import ThemeToggle from '../components/ThemeToggle.vue'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const sidebarOpen = ref(window.innerWidth >= 1024)

const handleLogout = async () => {
  if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
    await authStore.logout()
    toast.info('Sesión cerrada exitosamente')
    router.push('/login')
  }
}
</script>
