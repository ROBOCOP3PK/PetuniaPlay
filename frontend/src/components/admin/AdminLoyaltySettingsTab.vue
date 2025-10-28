<template>
  <div>
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Program Settings Form -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
          <i class="pi pi-cog text-primary dark:text-primary-light"></i>
          Configuración del Programa
        </h3>

        <form @submit.prevent="saveProgram" class="space-y-6">
          <!-- Program Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Nombre del Programa
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Ej: Programa de Recompensas VIP"
            />
          </div>

          <!-- Program Description -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Descripción
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
              placeholder="Describe tu programa de lealtad..."
            ></textarea>
          </div>

          <!-- Save Button -->
          <button
            type="submit"
            :disabled="saving"
            class="w-full bg-primary hover:bg-primary-dark dark:bg-primary-light dark:hover:bg-primary text-white font-semibold py-3 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <i class="pi" :class="saving ? 'pi-spin pi-spinner' : 'pi-save'"></i>
            {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
          </button>
        </form>
      </div>

      <!-- Statistics Card -->
      <div class="space-y-6">
        <!-- Program Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="pi pi-chart-bar text-primary dark:text-primary-light"></i>
            Estadísticas del Programa
          </h3>

          <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg">
                  <i class="pi pi-users text-2xl text-primary"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Usuarios en el Programa</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ program.total_users || 0 }}
                  </p>
                </div>
              </div>
            </div>

            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="bg-green-100 dark:bg-green-900 dark:bg-opacity-30 p-3 rounded-lg">
                  <i class="pi pi-star text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Total de Recompensas</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ program.total_rewards || 0 }}
                  </p>
                </div>
              </div>
            </div>

            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 p-3 rounded-lg">
                  <i class="pi pi-gift text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Total de Canjes</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ program.total_redemptions || 0 }}
                  </p>
                </div>
              </div>
            </div>

            <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center gap-3">
                <div class="bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 p-3 rounded-lg">
                  <i class="pi pi-percentage text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Tasa de Canje</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ program.redemption_rate || '0.0' }}%
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Program Info -->
        <div class="bg-gradient-to-r from-primary to-primary-dark dark:from-primary-dark dark:to-primary text-white rounded-lg shadow-md p-6">
          <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
            <i class="pi pi-info-circle"></i>
            Información
          </h3>
          <p class="text-white text-opacity-90 text-sm leading-relaxed">
            El programa de lealtad permite a tus clientes acumular compras y canjear recompensas
            especiales. Configura recompensas atractivas para incentivar compras recurrentes y
            construir lealtad de marca.
          </p>
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
const saving = ref(false)
const program = ref({})
const form = ref({
  name: '',
  description: ''
})

// Methods
const loadProgram = async () => {
  loading.value = true
  try {
    const response = await loyaltyService.getProgram()
    program.value = response.data
    form.value = {
      name: response.data.name || '',
      description: response.data.description || ''
    }
  } catch (error) {
    console.error('Error loading program:', error)
    toast.error('Error al cargar la configuración del programa')
  } finally {
    loading.value = false
  }
}

const saveProgram = async () => {
  saving.value = true
  try {
    await loyaltyService.updateProgram(form.value)
    toast.success('Configuración guardada exitosamente')
    await loadProgram()
    emit('refresh')
  } catch (error) {
    console.error('Error saving program:', error)
    const message = error.response?.data?.message || 'Error al guardar la configuración'
    toast.error(message)
  } finally {
    saving.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadProgram()
})
</script>
