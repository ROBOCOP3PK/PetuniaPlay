<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Confirm Dialog -->
    <ConfirmDialog
      v-model:isOpen="confirmDialog.isOpen"
      :title="confirmDialog.title"
      :message="confirmDialog.message"
      :type="confirmDialog.type"
      :confirm-text="confirmDialog.confirmText"
      :cancel-text="confirmDialog.cancelText"
      @confirm="confirmDialog.onConfirm"
      @cancel="confirmDialog.onCancel"
    />

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
            <i class="pi pi-times text-2xl"></i>
          </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          <router-link
            to="/admin"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-home text-xl"></i>
            <span class="font-semibold">Dashboard</span>
          </router-link>

          <router-link
            to="/admin/products"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-box text-xl"></i>
            <span class="font-semibold">Productos</span>
          </router-link>

          <router-link
            to="/admin/categories"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-tag text-xl"></i>
            <span class="font-semibold">Categorías</span>
          </router-link>

          <router-link
            to="/admin/orders"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-clipboard text-xl"></i>
            <span class="font-semibold">Pedidos</span>
          </router-link>

          <router-link
            to="/admin/coupons"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-ticket text-xl"></i>
            <span class="font-semibold">Cupones</span>
          </router-link>

          <router-link
            to="/admin/shipments"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-truck text-xl"></i>
            <span class="font-semibold">Envíos</span>
          </router-link>

          <router-link
            to="/admin/loyalty"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-gift text-xl"></i>
            <span class="font-semibold">Programa de Lealtad</span>
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin/users"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
            active-class="bg-primary"
          >
            <i class="pi pi-users text-xl"></i>
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
            <i class="pi pi-bars text-2xl"></i>
          </button>

          <div class="flex items-center space-x-4">
            <ThemeToggle />
            <router-link
              to="/"
              class="text-gray-600 hover:text-primary transition"
              title="Ver tienda"
            >
              <i class="pi pi-home text-2xl"></i>
            </router-link>
            <button
              @click="handleLogout"
              class="text-gray-600 hover:text-red-600 transition"
              title="Cerrar sesión"
            >
              <i class="pi pi-sign-out text-2xl"></i>
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
import ConfirmDialog from '../components/ConfirmDialog.vue'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const sidebarOpen = ref(window.innerWidth >= 1024)

// Confirm Dialog State
const confirmDialog = ref({
  isOpen: false,
  title: '',
  message: '',
  type: 'danger',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  onConfirm: () => {},
  onCancel: () => {}
})

const handleLogout = async () => {
  confirmDialog.value = {
    isOpen: true,
    title: '¿Cerrar sesión?',
    message: '¿Estás seguro de que deseas cerrar sesión?',
    type: 'warning',
    confirmText: 'Sí, cerrar sesión',
    cancelText: 'Cancelar',
    onConfirm: async () => {
      await authStore.logout()
      toast.info('Sesión cerrada exitosamente')
      router.push('/login')
    },
    onCancel: () => {}
  }
}
</script>
