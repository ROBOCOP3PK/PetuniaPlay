<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mensajes de Contacto</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">Gestiona los mensajes recibidos desde el formulario de contacto</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div v-if="stats" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Mensajes</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 p-3 rounded-lg">
              <i class="pi pi-envelope text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pendientes</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ stats.pending }}</p>
            </div>
            <div class="bg-orange-100 dark:bg-orange-900 dark:bg-opacity-30 p-3 rounded-lg">
              <i class="pi pi-clock text-2xl text-orange-600 dark:text-orange-400"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">En Progreso</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.in_progress }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 p-3 rounded-lg">
              <i class="pi pi-spin pi-spinner text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Resueltos</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.resolved }}</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900 dark:bg-opacity-30 p-3 rounded-lg">
              <i class="pi pi-check-circle text-2xl text-green-600 dark:text-green-400"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <select
            v-model="filterStatus"
            @change="loadMessages"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          >
            <option value="">Todos los mensajes</option>
            <option value="pending">Pendientes</option>
            <option value="in_progress">En Progreso</option>
            <option value="resolved">Resueltos</option>
          </select>
          <button
            @click="loadMessages"
            class="bg-gray-800 dark:bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors"
          >
            Actualizar
          </button>
        </div>
      </div>

      <!-- Messages List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>

        <div v-else-if="messages.length === 0" class="text-center py-12">
          <i class="pi pi-inbox text-6xl text-gray-400 dark:text-gray-500"></i>
          <p class="mt-2 text-gray-600 dark:text-gray-400">No se encontraron mensajes</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Contacto
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Asunto
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Mensaje
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Estado
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Fecha
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="message in messages"
                :key="message.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="text-sm">
                    <div class="font-medium text-gray-900 dark:text-white">{{ message.name }}</div>
                    <div class="text-gray-500 dark:text-gray-400">{{ message.email }}</div>
                    <div v-if="message.phone" class="text-gray-500 dark:text-gray-400 text-xs">{{ message.phone }}</div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm text-gray-900 dark:text-white">{{ formatSubject(message.subject) }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate" :title="message.message">
                    {{ message.message }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="px-3 py-1 text-xs font-semibold rounded-full"
                    :class="getStatusClass(message.status)"
                  >
                    {{ getStatusLabel(message.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(message.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                  <button
                    @click="viewMessage(message)"
                    class="text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-primary"
                    title="Ver detalles"
                  >
                    <i class="pi pi-eye text-lg"></i>
                  </button>
                  <button
                    v-if="message.status !== 'resolved'"
                    @click="updateMessageStatus(message.id, 'in_progress')"
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                    title="Marcar en progreso"
                  >
                    <i class="pi pi-arrow-right text-lg"></i>
                  </button>
                  <button
                    v-if="message.status !== 'resolved'"
                    @click="updateMessageStatus(message.id, 'resolved')"
                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                    title="Marcar como resuelto"
                  >
                    <i class="pi pi-check text-lg"></i>
                  </button>
                  <button
                    @click="deleteMessage(message.id)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                    title="Eliminar"
                  >
                    <i class="pi pi-trash text-lg"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="meta && meta.total > 0" class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-600">
          <div class="text-sm text-gray-700 dark:text-gray-300">
            Mostrando {{ meta.from }} a {{ meta.to }} de {{ meta.total }} mensajes
          </div>
          <div class="flex space-x-2">
            <button
              v-for="page in displayPages"
              :key="page"
              @click="changePage(page)"
              :disabled="page === meta.current_page"
              class="px-3 py-1 rounded-md text-sm"
              :class="page === meta.current_page
                ? 'bg-primary text-white cursor-default'
                : 'bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-500'"
            >
              {{ page }}
            </button>
          </div>
        </div>
      </div>

      <!-- Message Detail Modal -->
      <div
        v-if="selectedMessage"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        @click.self="selectedMessage = null"
      >
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detalle del Mensaje</h2>
              <button
                @click="selectedMessage = null"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
              >
                <i class="pi pi-times text-2xl"></i>
              </button>
            </div>

            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                <p class="text-gray-900 dark:text-white">{{ selectedMessage.name }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <p class="text-gray-900 dark:text-white">{{ selectedMessage.email }}</p>
              </div>

              <div v-if="selectedMessage.phone">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                <p class="text-gray-900 dark:text-white">{{ selectedMessage.phone }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Asunto</label>
                <p class="text-gray-900 dark:text-white">{{ formatSubject(selectedMessage.subject) }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Mensaje</label>
                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ selectedMessage.message }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <span
                  class="px-3 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClass(selectedMessage.status)"
                >
                  {{ getStatusLabel(selectedMessage.status) }}
                </span>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fecha de Envío</label>
                <p class="text-gray-900 dark:text-white">{{ formatDate(selectedMessage.created_at) }}</p>
              </div>

              <div v-if="selectedMessage.resolved_by && selectedMessage.resolved_at">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Resuelto por</label>
                <p class="text-gray-900 dark:text-white">
                  {{ selectedMessage.resolved_by?.name || 'Usuario desconocido' }} - {{ formatDate(selectedMessage.resolved_at) }}
                </p>
              </div>

              <div v-if="selectedMessage.admin_notes">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notas del Admin</label>
                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ selectedMessage.admin_notes }}</p>
              </div>

              <div class="flex gap-2 mt-6">
                <button
                  v-if="selectedMessage.status !== 'resolved'"
                  @click="updateMessageStatusWithNotes(selectedMessage.id, 'resolved')"
                  class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition"
                >
                  Marcar como Resuelto
                </button>
                <button
                  @click="selectedMessage = null"
                  class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition"
                >
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vue-toastification'
import AdminLayout from '../../layouts/AdminLayout.vue'
import contactMessageService from '../../services/contactMessageService'

const toast = useToast()
const loading = ref(false)
const messages = ref([])
const stats = ref(null)
const filterStatus = ref('')
const selectedMessage = ref(null)
const meta = ref(null)

// Format helpers
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatSubject = (subject) => {
  const subjects = {
    'product': 'Consulta sobre producto',
    'order': 'Estado de pedido',
    'shipping': 'Información de envío',
    'return': 'Devoluciones y cambios',
    'other': 'Otro'
  }
  return subjects[subject] || subject
}

const getStatusLabel = (status) => {
  const labels = {
    'pending': 'Pendiente',
    'in_progress': 'En Progreso',
    'resolved': 'Resuelto'
  }
  return labels[status] || status
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
    'in_progress': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'resolved': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
  }
  return classes[status] || ''
}

// Load messages
const loadMessages = async (page = 1) => {
  loading.value = true
  try {
    const params = { page, per_page: 20 }
    if (filterStatus.value) {
      params.status = filterStatus.value
    }

    const response = await contactMessageService.getAll(params)
    messages.value = response.data.data
    meta.value = response.data.meta
  } catch (error) {
    toast.error('Error al cargar los mensajes')
    console.error('Error loading messages:', error)
  } finally {
    loading.value = false
  }
}

// Load stats
const loadStats = async () => {
  try {
    const response = await contactMessageService.getStats()
    stats.value = response.data.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

// View message detail
const viewMessage = (message) => {
  selectedMessage.value = message
}

// Update message status
const updateMessageStatus = async (id, status) => {
  try {
    await contactMessageService.updateStatus(id, status)
    toast.success('Estado actualizado correctamente')
    await loadMessages()
    await loadStats()
  } catch (error) {
    toast.error('Error al actualizar el estado')
    console.error('Error updating status:', error)
  }
}

// Update message status with notes (from modal)
const updateMessageStatusWithNotes = async (id, status) => {
  const notes = prompt('Notas adicionales (opcional):')
  try {
    await contactMessageService.updateStatus(id, status, notes)
    toast.success('Mensaje marcado como resuelto')
    selectedMessage.value = null
    await loadMessages()
    await loadStats()
  } catch (error) {
    toast.error('Error al actualizar el estado')
    console.error('Error updating status:', error)
  }
}

// Delete message
const deleteMessage = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este mensaje?')) return

  try {
    await contactMessageService.delete(id)
    toast.success('Mensaje eliminado correctamente')
    await loadMessages()
    await loadStats()
  } catch (error) {
    toast.error('Error al eliminar el mensaje')
    console.error('Error deleting message:', error)
  }
}

// Pagination
const changePage = (page) => {
  loadMessages(page)
}

const displayPages = computed(() => {
  if (!meta.value) return []
  const pages = []
  for (let i = 1; i <= meta.value.last_page; i++) {
    pages.push(i)
  }
  return pages
})

// Init
onMounted(() => {
  loadMessages()
  loadStats()
})
</script>
