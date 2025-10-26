<template>
  <div class="min-h-screen py-8 bg-gray-50">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Mi Cuenta</h1>
        <p class="text-gray-600">Bienvenido, {{ authStore.userName }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md p-6">
            <nav class="space-y-2">
              <button
                @click="activeTab = 'profile'"
                :class="tabClass('profile')"
                class="w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3"
              >
                <span>👤</span>
                <span>Mi Perfil</span>
              </button>
              <button
                @click="activeTab = 'orders'"
                :class="tabClass('orders')"
                class="w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3"
              >
                <span>📦</span>
                <span>Mis Pedidos</span>
              </button>
              <button
                @click="activeTab = 'addresses'"
                :class="tabClass('addresses')"
                class="w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3"
              >
                <span>📍</span>
                <span>Mis Direcciones</span>
              </button>
              <button
                @click="handleLogout"
                class="w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3 text-red-600 hover:bg-red-50"
              >
                <span>🚪</span>
                <span>Cerrar Sesión</span>
              </button>
            </nav>
          </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Profile Tab -->
            <div v-show="activeTab === 'profile'">
              <h2 class="text-2xl font-bold mb-6">Información Personal</h2>
              <ProfileSection />
            </div>

            <!-- Orders Tab -->
            <div v-show="activeTab === 'orders'">
              <h2 class="text-2xl font-bold mb-6">Historial de Pedidos</h2>
              <OrdersSection />
            </div>

            <!-- Addresses Tab -->
            <div v-show="activeTab === 'addresses'">
              <h2 class="text-2xl font-bold mb-6">Direcciones de Envío</h2>
              <AddressesSection />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'
import ProfileSection from '../components/account/ProfileSection.vue'
import OrdersSection from '../components/account/OrdersSection.vue'
import AddressesSection from '../components/account/AddressesSection.vue'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const activeTab = ref('profile')

const tabClass = (tab) => {
  return activeTab.value === tab
    ? 'bg-primary text-white font-semibold'
    : 'hover:bg-gray-100'
}

const handleLogout = async () => {
  if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
    await authStore.logout()
    toast.info('Sesión cerrada exitosamente')
    router.push('/')
  }
}
</script>
