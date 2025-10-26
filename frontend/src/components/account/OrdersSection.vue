<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-600">Cargando pedidos...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="orders.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">📦</div>
      <h3 class="text-2xl font-bold mb-2">No tienes pedidos</h3>
      <p class="text-gray-600 mb-6">
        Comienza a explorar nuestros productos y realiza tu primer pedido
      </p>
      <router-link to="/products" class="btn-primary inline-block">
        Explorar Productos
      </router-link>
    </div>

    <!-- Orders List -->
    <div v-else class="space-y-6">
      <div
        v-for="order in orders"
        :key="order.id"
        class="border rounded-lg p-6 hover:shadow-md transition"
      >
        <!-- Order Header -->
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-bold">Pedido #{{ order.order_number }}</h3>
            <p class="text-sm text-gray-600">
              {{ formatDate(order.created_at) }}
            </p>
          </div>
          <div class="text-right">
            <span
              :class="statusBadgeClass(order.status)"
              class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
            >
              {{ statusLabel(order.status) }}
            </span>
            <p class="text-sm text-gray-600 mt-1">
              {{ paymentStatusLabel(order.payment_status) }}
            </p>
          </div>
        </div>

        <!-- Order Items -->
        <div class="space-y-3 mb-4">
          <div
            v-for="item in order.items"
            :key="item.id"
            class="flex items-center gap-4 bg-gray-50 p-3 rounded"
          >
            <div class="flex-1">
              <p class="font-semibold">{{ item.product_name }}</p>
              <p class="text-sm text-gray-600">
                Cantidad: {{ item.quantity }} x ${{ formatPrice(item.price) }}
              </p>
            </div>
            <div class="text-right">
              <p class="font-bold text-primary">${{ formatPrice(item.subtotal) }}</p>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="border-t pt-4">
          <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Subtotal:</span>
            <span>${{ formatPrice(order.subtotal) }}</span>
          </div>
          <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">IVA:</span>
            <span>${{ formatPrice(order.tax) }}</span>
          </div>
          <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Envío:</span>
            <span>
              {{ order.shipping_cost === 0 ? '¡Gratis!' : '$' + formatPrice(order.shipping_cost) }}
            </span>
          </div>
          <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t">
            <span>Total:</span>
            <span class="text-primary">${{ formatPrice(order.total) }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-4 flex gap-3">
          <button
            @click="viewOrderDetails(order)"
            class="btn-outline flex-1"
          >
            Ver Detalles
          </button>
          <button
            v-if="order.status === 'pending'"
            @click="cancelOrder(order.id)"
            class="btn-secondary"
          >
            Cancelar Pedido
          </button>
        </div>
      </div>
    </div>

    <!-- Order Details Modal -->
    <div
      v-if="selectedOrder"
      @click="selectedOrder = null"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div
        @click.stop
        class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8"
      >
        <div class="flex justify-between items-start mb-6">
          <div>
            <h2 class="text-2xl font-bold">Pedido #{{ selectedOrder.order_number }}</h2>
            <p class="text-gray-600">{{ formatDate(selectedOrder.created_at) }}</p>
          </div>
          <button @click="selectedOrder = null" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>

        <!-- Shipping Address -->
        <div class="mb-6">
          <h3 class="font-bold mb-2">Dirección de Envío</h3>
          <div class="bg-gray-50 p-4 rounded">
            <p>{{ selectedOrder.shipping_address?.full_name }}</p>
            <p>{{ selectedOrder.shipping_address?.address_line_1 }}</p>
            <p>{{ selectedOrder.shipping_address?.city }}, {{ selectedOrder.shipping_address?.state }}</p>
            <p>{{ selectedOrder.shipping_address?.postal_code }}</p>
          </div>
        </div>

        <!-- Items -->
        <div class="mb-6">
          <h3 class="font-bold mb-2">Productos</h3>
          <div class="space-y-2">
            <div
              v-for="item in selectedOrder.items"
              :key="item.id"
              class="flex justify-between bg-gray-50 p-3 rounded"
            >
              <div>
                <p class="font-semibold">{{ item.product_name }}</p>
                <p class="text-sm text-gray-600">{{ item.quantity }} x ${{ formatPrice(item.price) }}</p>
              </div>
              <p class="font-bold">${{ formatPrice(item.subtotal) }}</p>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="selectedOrder.notes" class="mb-6">
          <h3 class="font-bold mb-2">Notas</h3>
          <p class="bg-gray-50 p-4 rounded">{{ selectedOrder.notes }}</p>
        </div>

        <button @click="selectedOrder = null" class="btn-primary w-full">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import orderService from '../../services/orderService'
import { useToast } from 'vue-toastification'

const toast = useToast()
const loading = ref(false)
const orders = ref([])
const selectedOrder = ref(null)

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const statusLabel = (status) => {
  const labels = {
    pending: 'Pendiente',
    processing: 'En Proceso',
    shipped: 'Enviado',
    delivered: 'Entregado',
    cancelled: 'Cancelado'
  }
  return labels[status] || status
}

const statusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const paymentStatusLabel = (status) => {
  const labels = {
    pending: 'Pago Pendiente',
    paid: 'Pagado',
    failed: 'Pago Fallido',
    refunded: 'Reembolsado'
  }
  return labels[status] || status
}

const loadOrders = async () => {
  loading.value = true
  try {
    const response = await orderService.getMyOrders()
    orders.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading orders:', error)
    toast.error('Error al cargar los pedidos')
  } finally {
    loading.value = false
  }
}

const viewOrderDetails = (order) => {
  selectedOrder.value = order
}

const cancelOrder = async (orderId) => {
  if (!confirm('¿Estás seguro de cancelar este pedido?')) return

  try {
    await orderService.cancel(orderId)
    toast.success('Pedido cancelado exitosamente')
    await loadOrders() // Recargar lista
  } catch (error) {
    toast.error('Error al cancelar el pedido')
  }
}

onMounted(() => {
  loadOrders()
})
</script>
