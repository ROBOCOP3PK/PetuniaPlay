<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    <div class="container mx-auto px-4 max-w-7xl">
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

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
          <i class="pi pi-gift text-primary dark:text-primary-light"></i>
          Programa de Lealtad
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
          Acumula compras y canjea increíbles recompensas
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
      </div>

      <!-- Content -->
      <div v-else>
        <!-- Program Inactive Message -->
        <div
          v-if="!programActive"
          class="bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center"
        >
          <i class="pi pi-info-circle text-4xl text-yellow-600 dark:text-yellow-400 mb-3"></i>
          <h3 class="text-xl font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
            Programa de Lealtad Temporalmente Inactivo
          </h3>
          <p class="text-yellow-700 dark:text-yellow-400">
            Nuestro programa de lealtad no está disponible en este momento. Vuelve pronto.
          </p>
        </div>

        <div v-else>
          <!-- Program Info Card -->
          <div
            class="bg-gradient-to-r from-primary to-primary-dark dark:from-primary-dark dark:to-primary text-white rounded-2xl p-8 mb-8 shadow-lg"
          >
            <div class="flex items-center justify-between flex-wrap gap-4">
              <div>
                <h2 class="text-2xl font-bold mb-2">{{ program.name }}</h2>
                <p class="text-white text-opacity-90">{{ program.description }}</p>
              </div>
              <div class="text-center bg-white bg-opacity-20 rounded-xl px-8 py-4">
                <p class="text-sm text-white text-opacity-80 mb-1">Mis Compras Completadas</p>
                <p class="text-4xl font-bold">{{ userOrders }}</p>
              </div>
            </div>
          </div>

          <!-- Rewards Section -->
          <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
              <i class="pi pi-star text-primary dark:text-primary-light"></i>
              Recompensas Disponibles
            </h2>

            <!-- Empty State -->
            <div
              v-if="rewards.length === 0"
              class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center"
            >
              <i class="pi pi-inbox text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
              <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                No hay recompensas disponibles
              </h3>
              <p class="text-gray-500 dark:text-gray-400">
                Pronto estarán disponibles nuevas recompensas para ti.
              </p>
            </div>

            <!-- Rewards Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="reward in rewards"
                :key="reward.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden"
              >
                <!-- Reward Header -->
                <div class="bg-gradient-to-r from-primary to-primary-dark p-4">
                  <h3 class="text-xl font-bold text-white mb-1">{{ reward.name }}</h3>
                  <span
                    class="inline-block px-3 py-1 bg-white bg-opacity-20 text-white text-xs rounded-full"
                  >
                    {{ reward.type === 'permanent' ? 'Permanente' : 'Campaña' }}
                  </span>
                </div>

                <!-- Reward Body -->
                <div class="p-6">
                  <p class="text-gray-600 dark:text-gray-400 mb-4">{{ reward.description }}</p>

                  <!-- Product Info -->
                  <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Recibirás:</p>
                    <p class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                      <i class="pi pi-box text-primary dark:text-primary-light"></i>
                      {{ reward.product?.name || 'Producto especial' }}
                    </p>
                  </div>

                  <!-- Progress Bar -->
                  <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                      <span class="text-gray-600 dark:text-gray-400">Progreso</span>
                      <span class="font-semibold text-gray-900 dark:text-white">
                        {{ userOrders }} / {{ reward.orders_required }}
                      </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                      <div
                        class="bg-gradient-to-r from-primary to-primary-dark h-3 rounded-full transition-all duration-500"
                        :style="{ width: getProgressPercentage(reward) + '%' }"
                      ></div>
                    </div>
                  </div>

                  <!-- Campaign Dates -->
                  <div
                    v-if="reward.type === 'campaign' && reward.start_date"
                    class="text-xs text-gray-500 dark:text-gray-400 mb-4"
                  >
                    <i class="pi pi-calendar"></i>
                    {{ formatDate(reward.start_date) }} - {{ formatDate(reward.end_date) }}
                  </div>

                  <!-- Status Badge -->
                  <div class="mb-4">
                    <span
                      v-if="reward.already_redeemed"
                      class="inline-flex items-center gap-2 px-3 py-2 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-700 dark:text-green-400 rounded-lg text-sm font-semibold"
                    >
                      <i class="pi pi-check-circle"></i>
                      Ya Canjeada
                    </span>
                    <span
                      v-else-if="canRedeem(reward)"
                      class="inline-flex items-center gap-2 px-3 py-2 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-700 dark:text-green-400 rounded-lg text-sm font-semibold"
                    >
                      <i class="pi pi-check-circle"></i>
                      Disponible para canjear
                    </span>
                    <span
                      v-else
                      class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm font-semibold"
                    >
                      <i class="pi pi-clock"></i>
                      Proximamente
                    </span>
                  </div>

                  <!-- Redeem Button -->
                  <button
                    v-if="!reward.already_redeemed && canRedeem(reward)"
                    @click="handleRedeem(reward)"
                    :disabled="redeeming"
                    class="w-full bg-primary hover:bg-primary-dark dark:bg-primary-light dark:hover:bg-primary text-white font-semibold py-3 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                  >
                    <i class="pi" :class="redeeming ? 'pi-spin pi-spinner' : 'pi-gift'"></i>
                    {{ redeeming ? 'Canjeando...' : 'Canjear Ahora' }}
                  </button>

                  <button
                    v-else-if="!reward.already_redeemed"
                    disabled
                    class="w-full bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 font-semibold py-3 rounded-lg cursor-not-allowed"
                  >
                    Completa más pedidos
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Redemption History -->
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
              <i class="pi pi-history text-primary dark:text-primary-light"></i>
              Historial de Canjes
            </h2>

            <!-- Empty State -->
            <div
              v-if="redemptions.length === 0"
              class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center"
            >
              <i class="pi pi-inbox text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
              <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                No tienes canjes aún
              </h3>
              <p class="text-gray-500 dark:text-gray-400">
                Cuando canjees recompensas, aparecerán aquí.
              </p>
            </div>

            <!-- Redemptions Table -->
            <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                      >
                        Fecha
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                      >
                        Recompensa
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                      >
                        Compras
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                      >
                        Estado
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                      v-for="redemption in redemptions"
                      :key="redemption.id"
                      class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
                    >
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                        {{ formatDateTime(redemption.created_at) }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                        {{ redemption.reward?.name || 'N/A' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                        {{ redemption.orders_at_redemption }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold"
                          :class="getStatusClass(redemption.status)"
                        >
                          <i class="pi" :class="getStatusIcon(redemption.status)"></i>
                          {{ getStatusText(redemption.status) }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import ConfirmDialog from '../components/ConfirmDialog.vue'
import loyaltyService from '../services/loyaltyService'

const toast = useToast()

// Confirm Dialog State
const confirmDialog = ref({
  isOpen: false,
  title: '',
  message: '',
  type: 'info',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  onConfirm: () => {},
  onCancel: () => {}
})

// State
const loading = ref(true)
const redeeming = ref(false)
const program = ref({})
const programActive = ref(true)
const userOrders = ref(0)
const rewards = ref([])
const redemptions = ref([])

// Methods
const loadData = async () => {
  loading.value = true
  try {
    // Load rewards and redemptions in parallel
    const [rewardsResponse, redemptionsResponse] = await Promise.all([
      loyaltyService.getMyRewards(),
      loyaltyService.getMyRedemptions()
    ])

    // Extract program info from rewards response
    if (rewardsResponse.data.program) {
      program.value = rewardsResponse.data.program
      programActive.value = rewardsResponse.data.program.is_active
    }

    userOrders.value = rewardsResponse.data.user_orders || 0
    rewards.value = rewardsResponse.data.rewards || []
    redemptions.value = redemptionsResponse.data.data || []
  } catch (error) {
    console.error('Error loading loyalty data:', error)
    toast.error('Error al cargar el programa de lealtad')
  } finally {
    loading.value = false
  }
}

const getProgressPercentage = (reward) => {
  if (!reward.orders_required) return 0
  const percentage = (userOrders.value / reward.orders_required) * 100
  return Math.min(percentage, 100)
}

const canRedeem = (reward) => {
  return userOrders.value >= reward.orders_required
}

const handleRedeem = async (reward) => {
  confirmDialog.value = {
    isOpen: true,
    title: '¿Canjear recompensa?',
    message: `¿Deseas canjear la recompensa "${reward.name}"?`,
    type: 'success',
    confirmText: 'Sí, canjear',
    cancelText: 'Cancelar',
    onConfirm: async () => {
      redeeming.value = true
      try {
        await loyaltyService.redeemReward(reward.id)
        toast.success('Recompensa canjeada exitosamente')
        await loadData() // Reload data
      } catch (error) {
        console.error('Error redeeming reward:', error)
        const message = error.response?.data?.message || 'Error al canjear la recompensa'
        toast.error(message)
      } finally {
        redeeming.value = false
      }
    },
    onCancel: () => {}
  }
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatDateTime = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 text-yellow-700 dark:text-yellow-400',
    processing: 'bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-700 dark:text-blue-400',
    completed: 'bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-700 dark:text-green-400',
    cancelled: 'bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-700 dark:text-red-400'
  }
  return classes[status] || classes.pending
}

const getStatusIcon = (status) => {
  const icons = {
    pending: 'pi-clock',
    processing: 'pi-spin pi-spinner',
    completed: 'pi-check-circle',
    cancelled: 'pi-times-circle'
  }
  return icons[status] || 'pi-info-circle'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Pendiente',
    processing: 'Procesando',
    completed: 'Completado',
    cancelled: 'Cancelado'
  }
  return texts[status] || status
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>
