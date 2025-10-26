<template>
  <AdminLayout>
    <div>
      <h1 class="text-3xl font-bold text-dark mb-8">Gestión de Pedidos</h1>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por número de pedido o cliente..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          />
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
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
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando pedidos...</p>
      </div>

      <!-- Orders Table -->
      <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Fecha</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="order in filteredOrders"
                :key="order.id"
                class="hover:bg-gray-50 transition"
              >
                <td class="px-6 py-4">
                  <div>
                    <p class="font-bold text-dark">#{{ order.order_number }}</p>
                    <p class="text-sm text-gray-600">{{ order.items?.length || 0 }} productos</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-semibold text-dark">{{ order.customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ order.customer_email }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700">{{ formatDate(order.created_at) }}</p>
                </td>
                <td class="px-6 py-4 text-right">
                  <p class="font-bold text-primary text-lg">${{ formatPrice(order.total) }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                  <select
                    v-model="order.status"
                    @change="updateStatus(order)"
                    class="px-3 py-1 text-xs rounded-full font-semibold border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
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
          <p class="text-gray-600">No se encontraron pedidos</p>
        </div>
      </div>

      <!-- Order Details Modal -->
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="selectedOrder = null"
      >
        <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b sticky top-0 bg-white">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold">Pedido #{{ selectedOrder.order_number }}</h2>
              <button @click="selectedOrder = null" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <div class="p-6 space-y-6">
            <!-- Customer Info -->
            <div>
              <h3 class="font-bold text-lg mb-3">Información del Cliente</h3>
              <div class="bg-gray-50 p-4 rounded-lg">
                <p><strong>Nombre:</strong> {{ selectedOrder.customer_name }}</p>
                <p><strong>Email:</strong> {{ selectedOrder.customer_email }}</p>
                <p><strong>Teléfono:</strong> {{ selectedOrder.customer_phone }}</p>
              </div>
            </div>

            <!-- Shipping Address -->
            <div>
              <h3 class="font-bold text-lg mb-3">Dirección de Envío</h3>
              <div class="bg-gray-50 p-4 rounded-lg">
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

            <!-- Order Items -->
            <div>
              <h3 class="font-bold text-lg mb-3">Productos</h3>
              <div class="space-y-3">
                <div
                  v-for="item in selectedOrder.items"
                  :key="item.id"
                  class="flex justify-between items-center bg-gray-50 p-4 rounded-lg"
                >
                  <div class="flex-1">
                    <p class="font-semibold">{{ item.product?.name || item.product_name }}</p>
                    <p class="text-sm text-gray-600">Cantidad: {{ item.quantity }}</p>
                  </div>
                  <p class="font-bold text-primary">${{ formatPrice(item.price * item.quantity) }}</p>
                </div>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t pt-4">
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span>Subtotal:</span>
                  <span class="font-semibold">${{ formatPrice(selectedOrder.subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Envío:</span>
                  <span class="font-semibold">${{ formatPrice(selectedOrder.shipping_cost) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                  <span>Total:</span>
                  <span class="text-primary">${{ formatPrice(selectedOrder.total) }}</span>
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
import { useToast } from 'vue-toastification'

const toast = useToast()

const loading = ref(false)
const orders = ref([])
const search = ref('')
const filterStatus = ref('')
const filterDate = ref('')
const selectedOrder = ref(null)

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

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

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
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const updateStatus = async (order) => {
  try {
    await adminService.updateOrderStatus(order.id, order.status)
    toast.success('Estado del pedido actualizado')
  } catch (error) {
    toast.error('Error al actualizar estado')
    loadOrders() // Recargar para restaurar el estado anterior
  }
}

const viewOrder = (order) => {
  selectedOrder.value = order
}

const loadOrders = async () => {
  loading.value = true
  try {
    const response = await adminService.getAllOrders()
    orders.value = response.data
  } catch (error) {
    toast.error('Error al cargar pedidos')
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadOrders()
})
</script>
