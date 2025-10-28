<template>
  <div>
    <!-- Filters -->
    <div class="flex gap-4 mb-6">
      <!-- Status Filter -->
      <select
        v-model="filters.status"
        @change="loadRedemptions"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
      >
        <option value="">Todos los estados</option>
        <option value="pending">Pendiente</option>
        <option value="processing">Procesando</option>
        <option value="completed">Completado</option>
        <option value="cancelled">Cancelado</option>
      </select>

      <!-- User Filter -->
      <input
        v-model="filters.user"
        @input="debouncedSearch"
        type="text"
        placeholder="Buscar por usuario..."
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
      />

      <!-- Date Range -->
      <input
        v-model="filters.start_date"
        @change="loadRedemptions"
        type="date"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
      />
      <input
        v-model="filters.end_date"
        @change="loadRedemptions"
        type="date"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
      />

      <button
        @click="clearFilters"
        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
      >
        <i class="pi pi-times mr-2"></i>
        Limpiar
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="redemptions.length === 0"
      class="text-center py-20 bg-gray-50 dark:bg-gray-700 rounded-lg"
    >
      <i class="pi pi-inbox text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
      <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No hay canjes</h3>
      <p class="text-gray-500 dark:text-gray-400">
        Los canjes realizados por los usuarios aparecerán aquí
      </p>
    </div>

    <!-- Redemptions Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              ID
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Usuario
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
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Fecha
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Acciones
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr
            v-for="redemption in redemptions"
            :key="redemption.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
              #{{ redemption.id }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
              <div>
                <div class="font-medium">{{ redemption.user?.name || 'N/A' }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  {{ redemption.user?.email || '' }}
                </div>
              </div>
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
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
              {{ formatDateTime(redemption.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <div class="flex items-center gap-2">
                <button
                  @click="viewDetail(redemption)"
                  class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                  title="Ver detalle"
                >
                  <i class="pi pi-eye text-lg"></i>
                </button>
                <button
                  v-if="redemption.status === 'pending'"
                  @click="processRedemption(redemption)"
                  class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                  title="Procesar"
                >
                  <i class="pi pi-check text-lg"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Detail Modal -->
    <div
      v-if="showDetailModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeDetailModal"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
      >
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            Detalle del Canje #{{ selectedRedemption?.id }}
          </h3>
          <button
            @click="closeDetailModal"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <i class="pi pi-times text-2xl"></i>
          </button>
        </div>

        <!-- Modal Body -->
        <div v-if="loadingDetail" class="flex justify-center items-center py-20">
          <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
        </div>

        <div v-else-if="redemptionDetail" class="p-6 space-y-6">
          <!-- User Info -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Usuario</h4>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
              <p class="text-gray-900 dark:text-white font-medium">
                {{ redemptionDetail.user?.name }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ redemptionDetail.user?.email }}
              </p>
            </div>
          </div>

          <!-- Reward Info -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recompensa</h4>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
              <p class="text-gray-900 dark:text-white font-medium">
                {{ redemptionDetail.reward?.name }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ redemptionDetail.reward?.description }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                <strong>Producto:</strong> {{ redemptionDetail.reward?.product?.name }}
              </p>
            </div>
          </div>

          <!-- Redemption Info -->
          <div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Información del Canje
            </h4>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Compras completadas:</span>
                <span class="text-gray-900 dark:text-white font-medium">
                  {{ redemptionDetail.orders_at_redemption }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Estado:</span>
                <span
                  class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold"
                  :class="getStatusClass(redemptionDetail.status)"
                >
                  <i class="pi" :class="getStatusIcon(redemptionDetail.status)"></i>
                  {{ getStatusText(redemptionDetail.status) }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Fecha de canje:</span>
                <span class="text-gray-900 dark:text-white font-medium">
                  {{ formatDateTime(redemptionDetail.created_at) }}
                </span>
              </div>
              <div v-if="redemptionDetail.processed_at" class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Fecha de procesado:</span>
                <span class="text-gray-900 dark:text-white font-medium">
                  {{ formatDateTime(redemptionDetail.processed_at) }}
                </span>
              </div>
              <div v-if="redemptionDetail.reward_order_id" class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">ID de Orden:</span>
                <span class="text-gray-900 dark:text-white font-medium">
                  #{{ redemptionDetail.reward_order_id }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
          <button
            v-if="redemptionDetail?.status === 'pending'"
            @click="processRedemption(redemptionDetail)"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
          >
            <i class="pi pi-check mr-2"></i>
            Procesar Canje
          </button>
          <button
            @click="closeDetailModal"
            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-semibold transition-colors"
          >
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import loyaltyService from '../../services/loyaltyService'

const toast = useToast()
const emit = defineEmits(['refresh'])

// State
const loading = ref(true)
const loadingDetail = ref(false)
const redemptions = ref([])
const showDetailModal = ref(false)
const selectedRedemption = ref(null)
const redemptionDetail = ref(null)
const filters = ref({
  status: '',
  user: '',
  start_date: '',
  end_date: ''
})

let searchTimeout = null

// Methods
const loadRedemptions = async () => {
  loading.value = true
  try {
    const response = await loyaltyService.getRedemptions(filters.value)
    redemptions.value = response.data.data || []
  } catch (error) {
    console.error('Error loading redemptions:', error)
    toast.error('Error al cargar los canjes')
  } finally {
    loading.value = false
  }
}

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadRedemptions()
  }, 500)
}

const clearFilters = () => {
  filters.value = {
    status: '',
    user: '',
    start_date: '',
    end_date: ''
  }
  loadRedemptions()
}

const viewDetail = async (redemption) => {
  selectedRedemption.value = redemption
  showDetailModal.value = true
  loadingDetail.value = true

  try {
    const response = await loyaltyService.getRedemptionDetail(redemption.id)
    redemptionDetail.value = response.data
  } catch (error) {
    console.error('Error loading redemption detail:', error)
    toast.error('Error al cargar el detalle del canje')
  } finally {
    loadingDetail.value = false
  }
}

const closeDetailModal = () => {
  showDetailModal.value = false
  selectedRedemption.value = null
  redemptionDetail.value = null
}

const processRedemption = async (redemption) => {
  if (!confirm(`¿Deseas procesar el canje #${redemption.id}?`)) {
    return
  }

  try {
    await loyaltyService.processRedemption(redemption.id)
    toast.success('Canje procesado exitosamente')
    closeDetailModal()
    await loadRedemptions()
    emit('refresh')
  } catch (error) {
    console.error('Error processing redemption:', error)
    const message = error.response?.data?.message || 'Error al procesar el canje'
    toast.error(message)
  }
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

// Lifecycle
onMounted(() => {
  loadRedemptions()
})
</script>
