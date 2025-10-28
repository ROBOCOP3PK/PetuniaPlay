<template>
  <AdminLayout>
    <div>
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
            <i class="pi pi-gift text-primary dark:text-primary-light"></i>
            Programa de Lealtad
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mt-2">
            Gestiona recompensas, canjes y configuración del programa
          </p>
        </div>

        <!-- Program Toggle -->
        <div class="flex items-center gap-4 bg-white dark:bg-gray-800 px-6 py-4 rounded-lg shadow-md">
          <div class="text-right">
            <p class="text-sm text-gray-600 dark:text-gray-400">Estado del Programa</p>
            <p class="font-semibold text-gray-900 dark:text-white">
              {{ statistics.is_active ? 'Activo' : 'Inactivo' }}
            </p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input
              type="checkbox"
              v-model="statistics.is_active"
              @change="toggleProgram"
              class="sr-only peer"
            />
            <div
              class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary peer-focus:ring-opacity-30 dark:peer-focus:ring-primary-dark rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-primary"
            ></div>
          </label>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
      </div>

      <!-- Content -->
      <div v-else>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Active Rewards -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-primary"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Recompensas Activas</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                  {{ statistics.active_rewards || 0 }}
                </p>
              </div>
              <div class="bg-primary bg-opacity-10 p-3 rounded-lg">
                <i class="pi pi-star text-3xl text-primary"></i>
              </div>
            </div>
          </div>

          <!-- Pending Redemptions -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Canjes Pendientes</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                  {{ statistics.pending_redemptions || 0 }}
                </p>
              </div>
              <div class="bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 p-3 rounded-lg">
                <i class="pi pi-clock text-3xl text-yellow-600 dark:text-yellow-400"></i>
              </div>
            </div>
          </div>

          <!-- Completed Redemptions -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Canjes Completados</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                  {{ statistics.completed_redemptions || 0 }}
                </p>
              </div>
              <div class="bg-green-100 dark:bg-green-900 dark:bg-opacity-30 p-3 rounded-lg">
                <i class="pi pi-check-circle text-3xl text-green-600 dark:text-green-400"></i>
              </div>
            </div>
          </div>

          <!-- Reward Orders -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Órdenes Generadas</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                  {{ statistics.reward_orders || 0 }}
                </p>
              </div>
              <div class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 p-3 rounded-lg">
                <i class="pi pi-shopping-bag text-3xl text-blue-600 dark:text-blue-400"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
          <!-- Tab Headers -->
          <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
              <button
                @click="activeTab = 'rewards'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                :class="
                  activeTab === 'rewards'
                    ? 'border-primary text-primary dark:text-primary-light'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                "
              >
                <i class="pi pi-star mr-2"></i>
                Recompensas
              </button>
              <button
                @click="activeTab = 'redemptions'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                :class="
                  activeTab === 'redemptions'
                    ? 'border-primary text-primary dark:text-primary-light'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                "
              >
                <i class="pi pi-history mr-2"></i>
                Canjes
                <span
                  v-if="statistics.pending_redemptions > 0"
                  class="ml-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full"
                >
                  {{ statistics.pending_redemptions }}
                </span>
              </button>
              <button
                @click="activeTab = 'settings'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                :class="
                  activeTab === 'settings'
                    ? 'border-primary text-primary dark:text-primary-light'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                "
              >
                <i class="pi pi-cog mr-2"></i>
                Configuración
              </button>
            </nav>
          </div>

          <!-- Tab Content -->
          <div class="p-6">
            <AdminLoyaltyRewardsTab
              v-if="activeTab === 'rewards'"
              @refresh="loadStatistics"
            />
            <AdminLoyaltyRedemptionsTab
              v-if="activeTab === 'redemptions'"
              @refresh="loadStatistics"
            />
            <AdminLoyaltySettingsTab
              v-if="activeTab === 'settings'"
              @refresh="loadStatistics"
            />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import AdminLayout from '../../layouts/AdminLayout.vue'
import AdminLoyaltyRewardsTab from '../../components/admin/AdminLoyaltyRewardsTab.vue'
import AdminLoyaltyRedemptionsTab from '../../components/admin/AdminLoyaltyRedemptionsTab.vue'
import AdminLoyaltySettingsTab from '../../components/admin/AdminLoyaltySettingsTab.vue'
import loyaltyService from '../../services/loyaltyService'

const toast = useToast()

// State
const loading = ref(true)
const activeTab = ref('rewards')
const statistics = ref({
  is_active: true,
  active_rewards: 0,
  pending_redemptions: 0,
  completed_redemptions: 0,
  reward_orders: 0
})

// Methods
const loadStatistics = async () => {
  loading.value = true
  try {
    const response = await loyaltyService.getStatistics()
    statistics.value = response.data
  } catch (error) {
    console.error('Error loading statistics:', error)
    toast.error('Error al cargar las estadísticas')
  } finally {
    loading.value = false
  }
}

const toggleProgram = async () => {
  try {
    await loyaltyService.toggleProgram(statistics.value.is_active)
    toast.success(
      `Programa ${statistics.value.is_active ? 'activado' : 'desactivado'} exitosamente`
    )
  } catch (error) {
    console.error('Error toggling program:', error)
    statistics.value.is_active = !statistics.value.is_active // Revert
    toast.error('Error al cambiar el estado del programa')
  }
}

// Lifecycle
onMounted(() => {
  loadStatistics()
})
</script>
