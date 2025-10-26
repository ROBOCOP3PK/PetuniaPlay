<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
          Rastreo de Envío
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
          Ingresa tu número de seguimiento para ver el estado de tu pedido
        </p>
      </div>

      <!-- Search Form -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <label for="tracking" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Número de Seguimiento
            </label>
            <input
              v-model="trackingNumber"
              id="tracking"
              type="text"
              required
              placeholder="Ej: SHIP-20240126-ABCD1234"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
            />
          </div>
          <div class="flex items-end">
            <button
              type="submit"
              :disabled="loading || !trackingNumber.trim()"
              class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Buscando...' : 'Rastrear' }}</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-8">
        <div class="flex items-start">
          <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
          </svg>
          <p class="text-sm text-red-800 dark:text-red-200">{{ error }}</p>
        </div>
      </div>

      <!-- Shipment Information -->
      <div v-if="shipment" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Status Banner -->
        <div :class="getStatusBannerClass(shipment.status)" class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-white mb-2">
                {{ getStatusTitle(shipment.status) }}
              </h2>
              <p class="text-white text-opacity-90">
                Número de seguimiento: <span class="font-mono font-semibold">{{ shipment.tracking_number }}</span>
              </p>
            </div>
            <div class="text-6xl">
              {{ getStatusEmoji(shipment.status) }}
            </div>
          </div>
        </div>

        <!-- Shipment Details -->
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Order Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Información del Pedido
              </h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Número de Orden:</span>
                  <span class="font-semibold text-gray-900 dark:text-white">{{ shipment.order?.order_number || 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total:</span>
                  <span class="font-semibold text-gray-900 dark:text-white">
                    ${{ shipment.order?.total_amount?.toLocaleString() || 'N/A' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Carrier Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                Información de Transporte
              </h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Transportadora:</span>
                  <span class="font-semibold text-gray-900 dark:text-white">{{ shipment.carrier }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Estado:</span>
                  <span :class="getStatusClass(shipment.status)" class="font-semibold px-2 py-1 rounded">
                    {{ shipment.status_label }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Delivery Address -->
          <div v-if="shipment.order?.shipping_address" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Dirección de Entrega
            </h3>
            <p class="text-sm text-gray-700 dark:text-gray-300">
              {{ shipment.order.shipping_address }}
            </p>
          </div>

          <!-- Timeline -->
          <div class="mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Estado del Envío
            </h3>
            <div class="space-y-4">
              <!-- Timeline Item - Delivered -->
              <div class="flex items-start gap-4">
                <div :class="shipment.status === 'delivered' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'" class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0"></div>
                <div class="flex-1">
                  <p :class="shipment.status === 'delivered' ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400'">
                    Entregado
                  </p>
                  <p v-if="shipment.status === 'delivered' && shipment.delivered_at" class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatDate(shipment.delivered_at) }}
                  </p>
                </div>
              </div>

              <!-- Timeline Item - In Transit -->
              <div class="flex items-start gap-4">
                <div :class="['in_transit', 'delivered'].includes(shipment.status) ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600'" class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0"></div>
                <div class="flex-1">
                  <p :class="['in_transit', 'delivered'].includes(shipment.status) ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400'">
                    En tránsito
                  </p>
                  <p v-if="['in_transit', 'delivered'].includes(shipment.status) && shipment.shipped_at" class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatDate(shipment.shipped_at) }}
                  </p>
                </div>
              </div>

              <!-- Timeline Item - Pending -->
              <div class="flex items-start gap-4">
                <div class="bg-yellow-500 w-3 h-3 rounded-full mt-1.5 flex-shrink-0"></div>
                <div class="flex-1">
                  <p class="text-gray-900 dark:text-white font-semibold">
                    Procesando
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatDate(shipment.created_at) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Notes -->
          <div v-if="shipment.notes" class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 dark:text-blue-200 mb-2 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Notas Adicionales
            </h3>
            <p class="text-sm text-blue-800 dark:text-blue-200">{{ shipment.notes }}</p>
          </div>

          <!-- Warning for Delays -->
          <div v-if="shipment.is_delayed" class="bg-orange-50 dark:bg-orange-900 border border-orange-200 dark:border-orange-700 rounded-lg p-4 mt-4">
            <p class="text-sm text-orange-800 dark:text-orange-200 flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              Este envío está experimentando retrasos. El tiempo estimado de entrega puede ser mayor.
            </p>
          </div>
        </div>
      </div>

      <!-- Help Section -->
      <div class="mt-8 text-center">
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          ¿Necesitas ayuda con tu pedido?
        </p>
        <router-link
          to="/contact"
          class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          Contáctanos
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { shipmentService } from '../services/shipmentService'
import { useToast } from 'vue-toastification'

const route = useRoute()
const toast = useToast()

const trackingNumber = ref(route.query.tracking || '')
const loading = ref(false)
const error = ref('')
const shipment = ref(null)

// Auto-search if tracking number is provided in URL
if (trackingNumber.value) {
  handleSearch()
}

async function handleSearch() {
  if (!trackingNumber.value.trim()) {
    return
  }

  loading.value = true
  error.value = ''
  shipment.value = null

  try {
    const response = await shipmentService.trackByNumber(trackingNumber.value.trim())
    shipment.value = response.data.data
  } catch (err) {
    if (err.response?.status === 404) {
      error.value = 'No se encontró ningún envío con ese número de seguimiento. Por favor verifica que sea correcto.'
    } else {
      error.value = err.response?.data?.message || 'Error al buscar el envío. Por favor intenta nuevamente.'
    }
    toast.error(error.value)
  } finally {
    loading.value = false
  }
}

function getStatusClass(status) {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    in_transit: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    delivered: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    returned: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
  }
  return classes[status] || classes.pending
}

function getStatusBannerClass(status) {
  const classes = {
    pending: 'bg-gradient-to-r from-yellow-500 to-yellow-600',
    in_transit: 'bg-gradient-to-r from-blue-500 to-blue-600',
    delivered: 'bg-gradient-to-r from-green-500 to-green-600',
    failed: 'bg-gradient-to-r from-red-500 to-red-600',
    returned: 'bg-gradient-to-r from-gray-500 to-gray-600'
  }
  return classes[status] || classes.pending
}

function getStatusTitle(status) {
  const titles = {
    pending: 'Pendiente de Envío',
    in_transit: 'En Camino',
    delivered: '¡Entregado!',
    failed: 'Entrega Fallida',
    returned: 'Devuelto'
  }
  return titles[status] || 'Estado Desconocido'
}

function getStatusEmoji(status) {
  const emojis = {
    pending: '📦',
    in_transit: '🚚',
    delivered: '✅',
    failed: '❌',
    returned: '↩️'
  }
  return emojis[status] || '📦'
}

function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}
</script>
