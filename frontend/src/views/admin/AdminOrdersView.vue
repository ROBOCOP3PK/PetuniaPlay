<template>
  <AdminLayout>
    <div>
      <h1 class="text-3xl font-bold text-dark dark:text-white mb-8">Gestión de Pedidos</h1>

      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por número de pedido o cliente..."
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary"
          />
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Todos los estados</option>
            <option value="pending">Pendiente</option>
            <option value="processing">Procesando</option>
            <option value="shipped">Enviado</option>
            <option value="delivered">Entregado</option>
            <option value="cancelled">Cancelado</option>
          </select>
          <input
            v-model="filterDate"
            type="date"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>

        <!-- Export Buttons -->
        <div class="flex flex-wrap gap-2 pt-4 border-t dark:border-gray-700">
          <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2 flex items-center">Exportar:</label>
          <button
            @click="exportToExcel"
            :disabled="exportingExcel || filteredOrders.length === 0"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm font-semibold flex items-center space-x-2"
            :title="filteredOrders.length === 0 ? 'No hay pedidos para exportar' : 'Exportar a Excel'"
          >
            <span>📊</span>
            <span>{{ exportingExcel ? 'Exportando...' : 'Excel' }}</span>
          </button>
          <button
            @click="exportToPdf"
            :disabled="exportingPdf || filteredOrders.length === 0"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm font-semibold flex items-center space-x-2"
            :title="filteredOrders.length === 0 ? 'No hay pedidos para exportar' : 'Exportar a PDF'"
          >
            <span>📄</span>
            <span>{{ exportingPdf ? 'Exportando...' : 'PDF' }}</span>
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando pedidos...</p>
      </div>

      <!-- Orders Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Fecha</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Total</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="order in filteredOrders"
                :key="order.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
              >
                <td class="px-6 py-4">
                  <div>
                    <p class="font-bold text-dark dark:text-white">#{{ order.order_number }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.items?.length || 0 }} productos</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-semibold text-dark dark:text-white">{{ order.customer_name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.customer_email }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(order.created_at) }}</p>
                </td>
                <td class="px-6 py-4 text-right">
                  <p class="font-bold text-admin-price-light dark:text-admin-price-dark text-lg">{{ formatPrice(order.total) }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                  <select
                    v-model="order.status"
                    @change="updateStatus(order)"
                    class="px-3 py-1 text-xs rounded-full font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary border-2 border-transparent shadow-sm hover:shadow-md hover:border-gray-300 dark:hover:border-gray-500 transition-all"
                    :class="getStatusClass(order.status)"
                  >
                    <option value="pending">Pendiente</option>
                    <option value="processing">Procesando</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregado</option>
                    <option value="cancelled">Cancelado</option>
                  </select>
                </td>
                <td class="px-6 py-4 text-right">
                  <button
                    @click="viewOrder(order)"
                    class="text-blue-600 hover:text-blue-700 font-semibold text-sm"
                  >
                    Ver detalles
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredOrders.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400">No se encontraron pedidos</p>
        </div>
      </div>

      <!-- Order Details Modal -->
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 p-4"
        @click.self="selectedOrder = null"
      >
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pedido #{{ selectedOrder.order_number }}</h2>
              <button @click="selectedOrder = null" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <div class="p-6 space-y-6">
            <!-- Customer Info -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Información del Cliente</h3>
              <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-900 dark:text-gray-200">
                <p><strong>Nombre:</strong> {{ selectedOrder.customer_name }}</p>
                <p><strong>Email:</strong> {{ selectedOrder.customer_email }}</p>
                <p><strong>Teléfono:</strong> {{ selectedOrder.customer_phone }}</p>
              </div>
            </div>

            <!-- Shipping Address -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Dirección de Envío</h3>
              <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-900 dark:text-gray-200">
                <p>{{ selectedOrder.shipping_address?.address_line_1 }}</p>
                <p v-if="selectedOrder.shipping_address?.address_line_2">
                  {{ selectedOrder.shipping_address.address_line_2 }}
                </p>
                <p>
                  {{ selectedOrder.shipping_address?.city }},
                  {{ selectedOrder.shipping_address?.state }}
                  {{ selectedOrder.shipping_address?.postal_code }}
                </p>
              </div>
            </div>

            <!-- Shipment Info -->
            <div>
              <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Información de Envío</h3>
                <button
                  v-if="!orderShipment && !showShipmentForm"
                  @click="showShipmentForm = true"
                  class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition"
                >
                  📦 Crear Envío
                </button>
              </div>

              <!-- Existing Shipment -->
              <div v-if="orderShipment" class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-3 text-gray-900 dark:text-gray-100">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tracking Number</p>
                    <p class="font-mono font-bold">{{ orderShipment.tracking_number }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Transportadora</p>
                    <p class="font-semibold">{{ orderShipment.carrier }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estado</p>
                    <span :class="getShipmentStatusClass(orderShipment.status)">
                      {{ orderShipment.status_label }}
                    </span>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Días en tránsito</p>
                    <p>{{ orderShipment.days_in_transit || '-' }}</p>
                  </div>
                </div>
                <div v-if="orderShipment.notes" class="mt-3 pt-3 border-t dark:border-blue-700">
                  <p class="text-sm text-gray-600 dark:text-gray-400">Notas:</p>
                  <p class="text-sm text-gray-900 dark:text-gray-200">{{ orderShipment.notes }}</p>
                </div>
              </div>

              <!-- Create Shipment Form -->
              <div v-if="showShipmentForm && !orderShipment" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Tracking *</label>
                  <input
                    v-model="shipmentForm.tracking_number"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary"
                    placeholder="Ej: TRK-123456789"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transportadora *</label>
                  <select
                    v-model="shipmentForm.carrier"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                    required
                  >
                    <option value="">Seleccionar...</option>
                    <option value="Servientrega">Servientrega</option>
                    <option value="Coordinadora">Coordinadora</option>
                    <option value="Deprisa">Deprisa</option>
                    <option value="TCC">TCC</option>
                    <option value="Envía">Envía</option>
                    <option value="Otro">Otro</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas (opcional)</label>
                  <textarea
                    v-model="shipmentForm.notes"
                    rows="2"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary"
                    placeholder="Notas adicionales sobre el envío..."
                  ></textarea>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="createShipment"
                    :disabled="creatingShipment"
                    class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition disabled:opacity-50"
                  >
                    {{ creatingShipment ? 'Creando...' : 'Crear Envío' }}
                  </button>
                  <button
                    @click="cancelShipmentForm"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition"
                  >
                    Cancelar
                  </button>
                </div>
              </div>
            </div>

            <!-- Order Items -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Productos</h3>
              <div class="space-y-3">
                <div
                  v-for="item in selectedOrder.items"
                  :key="item.id"
                  class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-4 rounded-lg"
                >
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ item.product?.name || item.product_name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Cantidad: {{ item.quantity }}</p>
                  </div>
                  <p class="font-bold text-admin-price-light dark:text-admin-price-dark">{{ formatPrice(item.price * item.quantity) }}</p>
                </div>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t dark:border-gray-700 pt-4 text-gray-900 dark:text-gray-200">
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span>Subtotal:</span>
                  <span class="font-semibold">{{ formatPrice(selectedOrder.subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Envío:</span>
                  <span class="font-semibold">{{ formatPrice(selectedOrder.shipping_cost) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t dark:border-gray-700 pt-2">
                  <span>Total:</span>
                  <span class="text-admin-price-light dark:text-admin-price-dark">{{ formatPrice(selectedOrder.total) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { adminService } from '../../services/adminService'
import { shipmentService } from '../../services/shipmentService'
import { useNotification } from '@/composables/useNotification'
import { useFormat } from '@/composables/useFormat'

const { notifySuccess, notifyError } = useNotification()
const { formatPrice } = useFormat()

const loading = ref(false)
const orders = ref([])
const search = ref('')
const filterStatus = ref('')
const filterDate = ref('')
const selectedOrder = ref(null)
const exportingExcel = ref(false)
const exportingPdf = ref(false)

// Shipment state
const orderShipment = ref(null)
const showShipmentForm = ref(false)
const creatingShipment = ref(false)
const shipmentForm = ref({
  tracking_number: '',
  carrier: '',
  notes: ''
})

const filteredOrders = computed(() => {
  let filtered = orders.value

  // Search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(o =>
      o.order_number.toLowerCase().includes(searchLower) ||
      o.customer_name.toLowerCase().includes(searchLower) ||
      o.customer_email.toLowerCase().includes(searchLower)
    )
  }

  // Status filter
  if (filterStatus.value) {
    filtered = filtered.filter(o => o.status === filterStatus.value)
  }

  // Date filter
  if (filterDate.value) {
    filtered = filtered.filter(o =>
      o.created_at.startsWith(filterDate.value)
    )
  }

  return filtered
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
    processing: 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
    shipped: 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
    delivered: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
    cancelled: 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
  }
  return classes[status] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
}

const updateStatus = async (order) => {
  try {
    await adminService.updateOrderStatus(order.id, order.status)
    notifySuccess('Estado del pedido actualizado')
  } catch (error) {
    notifyError('Error al actualizar estado')
    loadOrders() // Recargar para restaurar el estado anterior
  }
}

const viewOrder = async (order) => {
  selectedOrder.value = order
  orderShipment.value = null
  showShipmentForm.value = false

  // Load shipment for this order if exists
  await loadOrderShipment(order.id)
}

const loadOrderShipment = async (orderId) => {
  try {
    const response = await shipmentService.getAll({ order_id: orderId })
    if (response.data.data && response.data.data.length > 0) {
      orderShipment.value = response.data.data[0]
    }
  } catch (error) {
    console.error('Error loading shipment:', error)
  }
}

const createShipment = async () => {
  if (!shipmentForm.value.tracking_number || !shipmentForm.value.carrier) {
    notifyError('Por favor completa los campos requeridos')
    return
  }

  creatingShipment.value = true
  try {
    const response = await shipmentService.create({
      order_id: selectedOrder.value.id,
      tracking_number: shipmentForm.value.tracking_number,
      carrier: shipmentForm.value.carrier,
      notes: shipmentForm.value.notes
    })

    orderShipment.value = response.data.data
    showShipmentForm.value = false
    shipmentForm.value = {
      tracking_number: '',
      carrier: '',
      notes: ''
    }

    notifySuccess('Envío creado exitosamente')

    // Recargar pedidos para reflejar el cambio de estado
    loadOrders()
  } catch (error) {
    console.error('Error creating shipment:', error)
    notifyError(error.response?.data?.message || 'Error al crear el envío')
  } finally {
    creatingShipment.value = false
  }
}

const cancelShipmentForm = () => {
  showShipmentForm.value = false
  shipmentForm.value = {
    tracking_number: '',
    carrier: '',
    notes: ''
  }
}

const getShipmentStatusClass = (status) => {
  const classes = {
    pending: 'px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
    in_transit: 'px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
    delivered: 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
    failed: 'px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
    returned: 'px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200'
  }
  return classes[status] || classes.pending
}

const loadOrders = async () => {
  loading.value = true
  try {
    const response = await adminService.getAllOrders()
    orders.value = response.data
  } catch (error) {
    notifyError('Error al cargar pedidos')
    console.error(error)
  } finally {
    loading.value = false
  }
}

const exportToExcel = async () => {
  // Validar que haya pedidos para exportar
  if (filteredOrders.value.length === 0) {
    notifyError('No hay pedidos para exportar con los filtros actuales')
    return
  }

  exportingExcel.value = true
  try {
    const params = new URLSearchParams()
    if (filterStatus.value) params.append('status', filterStatus.value)
    if (filterDate.value) {
      params.append('start_date', filterDate.value)
      params.append('end_date', filterDate.value)
    }

    const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'
    const token = localStorage.getItem('auth_token')

    const url = `${API_URL}/admin/export/orders/excel?${params.toString()}`

    console.log('Exportando desde:', url)

    const response = await fetch(url, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    console.log('Response status:', response.status)
    console.log('Response headers:', Object.fromEntries(response.headers.entries()))

    if (!response.ok) {
      // Intentar leer como JSON primero
      const contentType = response.headers.get('content-type')
      let errorMessage = `Error al exportar (${response.status})`

      if (contentType && contentType.includes('application/json')) {
        try {
          const errorData = await response.json()
          console.error('Error del servidor:', errorData)
          errorMessage = errorData.message || errorMessage
        } catch (e) {
          console.error('No se pudo parsear el error como JSON:', e)
        }
      } else {
        const textError = await response.text()
        console.error('Error del servidor (texto):', textError)
        errorMessage = textError || errorMessage
      }

      throw new Error(errorMessage)
    }

    const blob = await response.blob()
    console.log('Blob recibido, tamaño:', blob.size, 'tipo:', blob.type)

    // Validar que el blob no esté vacío
    if (blob.size === 0) {
      throw new Error('El archivo generado está vacío')
    }

    const downloadUrl = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = downloadUrl
    link.download = `ordenes_${new Date().toISOString().split('T')[0]}.xlsx`
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(downloadUrl)

    notifySuccess('Órdenes exportadas exitosamente')
  } catch (error) {
    console.error('Error completo:', error)
    notifyError(error.message || 'Error al exportar órdenes')
  } finally {
    exportingExcel.value = false
  }
}

const exportToPdf = async () => {
  // Validar que haya pedidos para exportar
  if (filteredOrders.value.length === 0) {
    notifyError('No hay pedidos para exportar con los filtros actuales')
    return
  }

  exportingPdf.value = true
  try {
    const params = new URLSearchParams()
    if (filterStatus.value) params.append('status', filterStatus.value)
    if (filterDate.value) {
      params.append('start_date', filterDate.value)
      params.append('end_date', filterDate.value)
    }

    const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'
    const token = localStorage.getItem('auth_token')

    const url = `${API_URL}/admin/export/orders/pdf?${params.toString()}`

    const response = await fetch(url, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}))
      throw new Error(errorData.message || 'Error al exportar')
    }

    const blob = await response.blob()

    // Validar que el blob no esté vacío
    if (blob.size === 0) {
      throw new Error('El archivo generado está vacío')
    }

    const downloadUrl = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = downloadUrl
    link.download = `ordenes_${new Date().toISOString().split('T')[0]}.pdf`
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(downloadUrl)

    notifySuccess('Órdenes exportadas exitosamente')
  } catch (error) {
    console.error('Error exporting:', error)
    notifyError(error.message || 'Error al exportar órdenes')
  } finally {
    exportingPdf.value = false
  }
}

onMounted(() => {
  loadOrders()
})
</script>
