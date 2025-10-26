<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Envíos</h1>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <p class="text-gray-600 dark:text-gray-400 text-sm">Total Envíos</p>
          <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_shipments || 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <p class="text-gray-600 dark:text-gray-400 text-sm">Pendientes</p>
          <p class="text-3xl font-bold text-yellow-600">{{ stats.pending || 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <p class="text-gray-600 dark:text-gray-400 text-sm">En Tránsito</p>
          <p class="text-3xl font-bold text-blue-600">{{ stats.in_transit || 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <p class="text-gray-600 dark:text-gray-400 text-sm">Entregados</p>
          <p class="text-3xl font-bold text-green-600">{{ stats.delivered || 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <p class="text-gray-600 dark:text-gray-400 text-sm">Tiempo Promedio</p>
          <p class="text-3xl font-bold text-purple-600">{{ stats.avg_delivery_days || '-' }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">días</p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Buscar por tracking o pedido..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
              @input="debouncedSearch"
            />
          </div>
          <div>
            <select
              v-model="filters.status"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
              @change="loadShipments"
            >
              <option value="">Todos los estados</option>
              <option value="pending">Pendiente</option>
              <option value="in_transit">En Tránsito</option>
              <option value="delivered">Entregado</option>
              <option value="failed">Fallido</option>
              <option value="returned">Devuelto</option>
            </select>
          </div>
          <div>
            <select
              v-model="filters.carrier"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
              @change="loadShipments"
            >
              <option value="">Todas las transportadoras</option>
              <option value="Servientrega">Servientrega</option>
              <option value="Coordinadora">Coordinadora</option>
              <option value="Deprisa">Deprisa</option>
              <option value="TCC">TCC</option>
              <option value="Envía">Envía</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Shipments Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tracking</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transportadora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Enviado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Días</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-if="loading">
                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  Cargando envíos...
                </td>
              </tr>
              <tr v-else-if="shipments.length === 0">
                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  No se encontraron envíos
                </td>
              </tr>
              <tr v-for="shipment in shipments" :key="shipment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="font-mono font-bold text-gray-900 dark:text-white">{{ shipment.tracking_number }}</span>
                  <span v-if="shipment.is_delayed" class="ml-2 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded">
                    ⚠️ Retrasado
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-gray-900 dark:text-white font-semibold">{{ shipment.order_number }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ shipment.order?.user?.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ shipment.order?.user?.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ shipment.carrier }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(shipment.status)">
                    {{ shipment.status_label }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ shipment.shipped_at ? formatDate(shipment.shipped_at) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ shipment.days_in_transit || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <button
                    @click="viewShipment(shipment)"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                    title="Ver detalles"
                  >
                    👁️
                  </button>
                  <button
                    @click="editShipment(shipment)"
                    class="text-primary dark:text-primary-light hover:text-primary-dark"
                    title="Editar"
                  >
                    ✏️
                  </button>
                  <button
                    v-if="!shipment.shipped_at"
                    @click="confirmDelete(shipment)"
                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                    title="Eliminar"
                  >
                    🗑️
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.total > pagination.per_page" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
              Mostrando {{ pagination.from }} - {{ pagination.to }} de {{ pagination.total }} envíos
            </div>
            <div class="flex space-x-2">
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-4 py-2 rounded-lg transition',
                  page === pagination.current_page
                    ? 'bg-primary text-white'
                    : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'
                ]"
              >
                {{ page }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit/View Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ modalMode === 'view' ? 'Detalles del Envío' : 'Editar Envío' }}
            </h2>
          </div>

          <div class="p-6 space-y-4">
            <!-- Order Info -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Información del Pedido</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Pedido:</strong> {{ selectedShipment?.order_number }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Cliente:</strong> {{ selectedShipment?.order?.user?.name }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ selectedShipment?.order?.user?.email }}</p>
            </div>

            <div v-if="modalMode === 'edit'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número de Tracking</label>
              <input
                v-model="shipmentForm.tracking_number"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
                required
              />
            </div>
            <div v-else>
              <p class="text-sm"><strong>Tracking:</strong> <span class="font-mono">{{ selectedShipment?.tracking_number }}</span></p>
            </div>

            <div v-if="modalMode === 'edit'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transportadora</label>
              <select
                v-model="shipmentForm.carrier"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
                required
              >
                <option value="Servientrega">Servientrega</option>
                <option value="Coordinadora">Coordinadora</option>
                <option value="Deprisa">Deprisa</option>
                <option value="TCC">TCC</option>
                <option value="Envía">Envía</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
            <div v-else>
              <p class="text-sm"><strong>Transportadora:</strong> {{ selectedShipment?.carrier }}</p>
            </div>

            <div v-if="modalMode === 'edit'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
              <select
                v-model="shipmentForm.status"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
                required
              >
                <option value="pending">Pendiente</option>
                <option value="in_transit">En Tránsito</option>
                <option value="delivered">Entregado</option>
                <option value="failed">Fallido</option>
                <option value="returned">Devuelto</option>
              </select>
            </div>
            <div v-else>
              <p class="text-sm">
                <strong>Estado:</strong>
                <span :class="getStatusClass(selectedShipment?.status)">{{ selectedShipment?.status_label }}</span>
              </p>
            </div>

            <div v-if="modalMode === 'edit'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas</label>
              <textarea
                v-model="shipmentForm.notes"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
              ></textarea>
            </div>
            <div v-else-if="selectedShipment?.notes">
              <p class="text-sm"><strong>Notas:</strong> {{ selectedShipment?.notes }}</p>
            </div>

            <!-- Shipping Address -->
            <div v-if="selectedShipment?.order?.shipping_address" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Dirección de Envío</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ selectedShipment.order.shipping_address.full_name }}<br>
                {{ selectedShipment.order.shipping_address.address_line_1 }}<br>
                {{ selectedShipment.order.shipping_address.city }}, {{ selectedShipment.order.shipping_address.state }}<br>
                {{ selectedShipment.order.shipping_address.country }}
              </p>
            </div>
          </div>

          <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
            <button
              @click="closeModal"
              class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition"
            >
              {{ modalMode === 'view' ? 'Cerrar' : 'Cancelar' }}
            </button>
            <button
              v-if="modalMode === 'edit'"
              @click="saveShipment"
              :disabled="saving"
              class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition disabled:opacity-50"
            >
              {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { shipmentService } from '../../services/shipmentService'
import { useToast } from 'vue-toastification'

const toast = useToast()

const shipments = ref([])
const stats = ref({})
const loading = ref(false)
const saving = ref(false)
const showModal = ref(false)
const modalMode = ref('view') // 'view' or 'edit'
const selectedShipment = ref(null)

const filters = ref({
  search: '',
  status: '',
  carrier: ''
})

const shipmentForm = ref({
  tracking_number: '',
  carrier: '',
  status: '',
  notes: ''
})

const pagination = ref({
  current_page: 1,
  per_page: 20,
  total: 0,
  last_page: 1,
  from: 0,
  to: 0
})

const visiblePages = computed(() => {
  const pages = []
  const current = pagination.value.current_page
  const last = pagination.value.last_page

  let start = Math.max(1, current - 2)
  let end = Math.min(last, current + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

let searchTimeout = null
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadShipments()
  }, 500)
}

const loadShipments = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: pagination.value.per_page,
      ...filters.value
    }

    const response = await shipmentService.getAll(params)

    shipments.value = response.data.data
    pagination.value = {
      current_page: response.data.meta.current_page,
      per_page: response.data.meta.per_page,
      total: response.data.meta.total,
      last_page: response.data.meta.last_page,
      from: response.data.meta.from || 0,
      to: response.data.meta.to || 0
    }
  } catch (error) {
    console.error('Error loading shipments:', error)
    toast.error('Error al cargar los envíos')
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    const response = await shipmentService.getStats()
    stats.value = response.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const viewShipment = (shipment) => {
  selectedShipment.value = shipment
  modalMode.value = 'view'
  showModal.value = true
}

const editShipment = (shipment) => {
  selectedShipment.value = shipment
  shipmentForm.value = {
    tracking_number: shipment.tracking_number,
    carrier: shipment.carrier,
    status: shipment.status,
    notes: shipment.notes || ''
  }
  modalMode.value = 'edit'
  showModal.value = true
}

const saveShipment = async () => {
  saving.value = true
  try {
    await shipmentService.update(selectedShipment.value.id, shipmentForm.value)
    toast.success('Envío actualizado exitosamente')
    closeModal()
    loadShipments(pagination.value.current_page)
    loadStats()
  } catch (error) {
    console.error('Error updating shipment:', error)
    toast.error(error.response?.data?.message || 'Error al actualizar el envío')
  } finally {
    saving.value = false
  }
}

const confirmDelete = async (shipment) => {
  if (confirm(`¿Estás seguro de eliminar el envío ${shipment.tracking_number}?`)) {
    try {
      await shipmentService.delete(shipment.id)
      toast.success('Envío eliminado exitosamente')
      loadShipments(pagination.value.current_page)
      loadStats()
    } catch (error) {
      console.error('Error deleting shipment:', error)
      toast.error(error.response?.data?.message || 'Error al eliminar el envío')
    }
  }
}

const closeModal = () => {
  showModal.value = false
  selectedShipment.value = null
  shipmentForm.value = {
    tracking_number: '',
    carrier: '',
    status: '',
    notes: ''
  }
}

const goToPage = (page) => {
  loadShipments(page)
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
    in_transit: 'px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
    delivered: 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
    failed: 'px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
    returned: 'px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200'
  }
  return classes[status] || classes.pending
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadShipments()
  loadStats()
})
</script>
