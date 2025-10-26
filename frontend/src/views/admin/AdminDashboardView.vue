<template>
  <AdminLayout>
    <div>
      <h1 class="text-3xl font-bold text-dark mb-8">Dashboard</h1>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando estadísticas...</p>
      </div>

      <div v-else>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Revenue -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Ingresos Totales</p>
                <p class="text-3xl font-bold text-primary mt-2">
                  ${{ formatPrice(stats.total_revenue) }}
                </p>
              </div>
              <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">
              Mes actual: ${{ formatPrice(stats.monthly_revenue) }}
            </p>
          </div>

          <!-- Total Orders -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Pedidos</p>
                <p class="text-3xl font-bold text-dark mt-2">{{ stats.total_orders }}</p>
              </div>
              <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">
              Pendientes: {{ stats.pending_orders }} | Procesando: {{ stats.processing_orders }}
            </p>
          </div>

          <!-- Total Products -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Productos</p>
                <p class="text-3xl font-bold text-dark mt-2">{{ stats.total_products }}</p>
              </div>
              <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">
              Bajo stock: {{ stats.products_low_stock }} | Sin stock: {{ stats.products_out_of_stock }}
            </p>
          </div>

          <!-- Total Users -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Usuarios</p>
                <p class="text-3xl font-bold text-dark mt-2">{{ stats.total_users }}</p>
              </div>
              <div class="bg-yellow-100 rounded-full p-3">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">
              Clientes registrados
            </p>
          </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Recent Orders -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Últimos Pedidos</h2>
            <div class="space-y-3">
              <div
                v-for="order in recentOrders"
                :key="order.id"
                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
              >
                <div class="flex-1">
                  <p class="font-semibold text-dark">#{{ order.order_number }}</p>
                  <p class="text-sm text-gray-600">{{ order.user?.name || 'Invitado' }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-primary">${{ formatPrice(order.total) }}</p>
                  <span
                    class="text-xs px-2 py-1 rounded-full"
                    :class="getStatusClass(order.status)"
                  >
                    {{ getStatusLabel(order.status) }}
                  </span>
                </div>
              </div>
              <router-link
                to="/admin/orders"
                class="block text-center text-primary font-semibold hover:underline mt-4"
              >
                Ver todos los pedidos →
              </router-link>
            </div>
          </div>

          <!-- Low Stock Products -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Productos con Bajo Stock</h2>
            <div class="space-y-3">
              <div
                v-for="product in lowStockProducts"
                :key="product.id"
                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
              >
                <div class="flex-1">
                  <p class="font-semibold text-dark">{{ product.name }}</p>
                  <p class="text-sm text-gray-600">SKU: {{ product.sku }}</p>
                </div>
                <div class="text-right">
                  <span
                    class="text-lg font-bold"
                    :class="product.stock <= 5 ? 'text-red-600' : 'text-yellow-600'"
                  >
                    {{ product.stock }} unidades
                  </span>
                </div>
              </div>
              <router-link
                to="/admin/products"
                class="block text-center text-primary font-semibold hover:underline mt-4"
              >
                Ver todos los productos →
              </router-link>
            </div>
          </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold mb-4">Productos Más Vendidos (Último Mes)</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Producto</th>
                  <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Vendidos</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="product in topProducts" :key="product.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <p class="font-semibold text-dark">{{ product.name }}</p>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <span class="font-bold text-primary">{{ product.total_sold }} unidades</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { adminService } from '../../services/adminService'
import { useToast } from 'vue-toastification'

const toast = useToast()

const loading = ref(false)
const stats = ref({})
const recentOrders = ref([])
const lowStockProducts = ref([])
const topProducts = ref([])

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
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

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Pendiente',
    processing: 'Procesando',
    shipped: 'Enviado',
    delivered: 'Entregado',
    cancelled: 'Cancelado'
  }
  return labels[status] || status
}

const loadDashboard = async () => {
  loading.value = true
  try {
    const response = await adminService.getDashboard()
    stats.value = response.data.stats
    recentOrders.value = response.data.recent_orders
    lowStockProducts.value = response.data.low_stock_products
    topProducts.value = response.data.top_products
  } catch (error) {
    toast.error('Error al cargar estadísticas')
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadDashboard()
})
</script>
