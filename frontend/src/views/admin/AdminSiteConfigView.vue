<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Configuración del Sitio</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra la configuración general del sitio web</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
      </div>

      <!-- Configuration Form -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form @submit.prevent="saveConfig" class="space-y-6">
          <!-- WhatsApp Section -->
          <div class="border-b dark:border-gray-700 pb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
              Botón de WhatsApp
            </h2>

            <!-- Enable/Disable Toggle -->
            <div class="mb-4">
              <label class="flex items-center cursor-pointer">
                <input
                  v-model="config.whatsapp_enabled"
                  type="checkbox"
                  class="w-5 h-5 text-primary border-gray-300 dark:border-gray-600 rounded focus:ring-primary"
                />
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                  Habilitar botón flotante de WhatsApp
                </span>
              </label>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 ml-8">
                Muestra un botón flotante en todas las páginas para que los clientes puedan contactarte fácilmente
              </p>
            </div>

            <!-- WhatsApp Configuration -->
            <div v-if="config.whatsapp_enabled" class="space-y-4">
              <!-- Phone Number -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  Número de WhatsApp *
                </label>
                <input
                  v-model="config.whatsapp_number"
                  type="text"
                  required
                  placeholder="573057594088"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  Formato internacional: código de país + número (sin +, espacios ni guiones)
                </p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                  Ejemplo: 573057594088 para Colombia (57) + número (3057594088)
                </p>
              </div>

              <!-- Default Message -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  Mensaje Predeterminado *
                </label>
                <textarea
                  v-model="config.whatsapp_message"
                  rows="3"
                  required
                  maxlength="500"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                  placeholder="Buen día Tengo una duda con un producto"
                ></textarea>
                <div class="flex justify-between items-center mt-1">
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    Este mensaje se pre-escribirá cuando el cliente haga clic en el botón
                  </p>
                  <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ config.whatsapp_message?.length || 0 }}/500
                  </p>
                </div>
              </div>

              <!-- Preview -->
              <div class="mt-4 p-4 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border border-green-200 dark:border-green-700 rounded-lg">
                <p class="text-sm font-semibold text-green-800 dark:text-green-300 mb-2">Vista Previa del Enlace:</p>
                <p class="text-xs text-green-700 dark:text-green-400 break-all font-mono">
                  https://wa.me/{{ config.whatsapp_number }}?text={{ encodeURIComponent(config.whatsapp_message) }}
                </p>
                <a
                  :href="`https://wa.me/${config.whatsapp_number}?text=${encodeURIComponent(config.whatsapp_message)}`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="mt-2 inline-flex items-center gap-2 text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-semibold"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                  Probar enlace
                </a>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-4 pt-4">
            <button
              type="button"
              @click="loadConfig"
              :disabled="saving"
              class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50"
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
import siteConfigService from '../../services/siteConfigService'

const { notifySuccess, notifyError } = useNotification()

const loading = ref(true)
const saving = ref(false)

const config = ref({
  whatsapp_number: '573057594088',
  whatsapp_enabled: true,
  whatsapp_message: 'Buen día Tengo una duda con un producto'
})

// Load configuration
const loadConfig = async () => {
  try {
    loading.value = true
    const response = await siteConfigService.getConfig()

    if (response.data.success) {
      config.value = response.data.data
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

    const response = await siteConfigService.updateConfig(config.value)

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

onMounted(() => {
  loadConfig()
})
</script>
