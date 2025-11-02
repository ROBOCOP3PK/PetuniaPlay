<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Configuración de Envíos</h1>
        <p class="text-gray-600 mt-1">Administra los horarios y costos de envío</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
      </div>

      <!-- Configuration Form -->
      <div v-else class="bg-white rounded-lg shadow-md p-6">
        <form @submit.prevent="saveConfig" class="space-y-6">
          <!-- Night Delivery Section -->
          <div class="border-b pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Entrega Nocturna</h2>

            <!-- Enable/Disable Toggle -->
            <div class="mb-4">
              <label class="flex items-center cursor-pointer">
                <input
                  v-model="config.night_delivery_enabled"
                  type="checkbox"
                  class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary"
                />
                <span class="ml-3 text-sm font-medium text-gray-900">
                  Habilitar entrega nocturna con envío gratis
                </span>
              </label>
              <p class="text-sm text-gray-600 mt-1 ml-8">
                Los clientes en Bogotá podrán elegir entrega nocturna sin costo adicional
              </p>
            </div>

            <!-- Time Range -->
            <div v-if="config.night_delivery_enabled" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Hora de Inicio
                </label>
                <input
                  v-model="config.night_delivery_start_time"
                  type="time"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <p class="text-xs text-gray-500 mt-1">Formato 24 horas (ej: 21:00)</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Hora de Fin
                </label>
                <input
                  v-model="config.night_delivery_end_time"
                  type="time"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <p class="text-xs text-gray-500 mt-1">Formato 24 horas (ej: 02:00)</p>
              </div>
            </div>

            <!-- Preview -->
            <div v-if="config.night_delivery_enabled" class="mt-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
              <p class="text-sm text-purple-800">
                <strong>Vista previa:</strong> Los clientes verán "Entrega nocturna de
                <strong>{{ formatTime(config.night_delivery_start_time) }}</strong> a
                <strong>{{ formatTime(config.night_delivery_end_time) }}</strong> - ¡ENVÍO GRATIS!"
              </p>
            </div>
          </div>

          <!-- Standard Shipping Section -->
          <div class="border-b pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Envío Estándar</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Costo de Envío Estándar (COP)
                </label>
                <input
                  v-model.number="config.standard_shipping_cost"
                  type="number"
                  min="0"
                  step="100"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <p class="text-xs text-gray-500 mt-1">Costo aplicado cuando no califican para envío gratis</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Mínimo de Artículos para Envío Gratis
                </label>
                <input
                  v-model.number="config.free_shipping_min_items"
                  type="number"
                  min="1"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <p class="text-xs text-gray-500 mt-1">En Bogotá: envío gratis al comprar este número de artículos</p>
              </div>
            </div>

            <!-- Info Box -->
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
              <p class="text-sm text-blue-800">
                <strong>ℹ️ Nota:</strong> El envío gratis está disponible solo en Bogotá.
                Para otras ciudades, siempre se aplica el costo de envío estándar.
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-4 pt-4">
            <button
              type="button"
              @click="loadConfig"
              :disabled="saving"
              class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-50 flex items-center gap-2"
            >
              <svg v-if="saving" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useNotification } from '@/composables/useNotification'
import AdminLayout from '../../layouts/AdminLayout.vue'
import shippingConfigService from '../../services/shippingConfigService'

const { notifySuccess, notifyError } = useNotification()

const loading = ref(true)
const saving = ref(false)

const config = ref({
  night_delivery_enabled: true,
  night_delivery_start_time: '21:00',
  night_delivery_end_time: '02:00',
  free_shipping_min_items: 4,
  standard_shipping_cost: 10000
})

// Load configuration
const loadConfig = async () => {
  try {
    loading.value = true
    const response = await shippingConfigService.getConfig()

    if (response.data.success) {
      const data = response.data.data

      // Format times to HH:MM
      config.value = {
        night_delivery_enabled: data.night_delivery_enabled,
        night_delivery_start_time: data.night_delivery_start_time.substring(0, 5),
        night_delivery_end_time: data.night_delivery_end_time.substring(0, 5),
        free_shipping_min_items: data.free_shipping_min_items,
        standard_shipping_cost: parseFloat(data.standard_shipping_cost)
      }
    }
  } catch (error) {
    console.error('Error loading config:', error)
    notifyError('Error al cargar la configuración')
  } finally {
    loading.value = false
  }
}

// Save configuration
const saveConfig = async () => {
  try {
    saving.value = true

    const response = await shippingConfigService.updateConfig(config.value)

    if (response.data.success) {
      notifySuccess('Configuración actualizada correctamente')
      await loadConfig()
    } else {
      notifyError(response.data.message || 'Error al actualizar la configuración')
    }
  } catch (error) {
    console.error('Error saving config:', error)
    const errorMessage = error.response?.data?.message || 'Error al guardar la configuración'
    notifyError(errorMessage)
  } finally {
    saving.value = false
  }
}

// Format time for display (HH:MM to human readable)
const formatTime = (time) => {
  if (!time) return ''

  const [hours, minutes] = time.split(':')
  const hour = parseInt(hours)

  if (hour === 0) {
    return `12:${minutes} AM`
  } else if (hour < 12) {
    return `${hour}:${minutes} AM`
  } else if (hour === 12) {
    return `12:${minutes} PM`
  } else {
    return `${hour - 12}:${minutes} PM`
  }
}

onMounted(() => {
  loadConfig()
})
</script>
