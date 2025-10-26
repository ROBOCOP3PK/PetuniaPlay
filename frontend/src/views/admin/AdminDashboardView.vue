<template>
  <AdminLayout>
    <div>
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-dark">Dashboard</h1>
      </div>

      <!-- Sales Report Export Section -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-bold text-dark mb-4">📊 Exportar Reporte de Ventas</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
            <input
              v-model="reportStartDate"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
            <input
              v-model="reportEndDate"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Agrupar Por</label>
            <select
              v-model="reportGroupBy"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            >
              <option value="day">Día</option>
              <option value="week">Semana</option>
              <option value="month">Mes</option>
            </select>
          </div>
          <button
            @click="exportSalesReport"
            :disabled="exportingReport"
            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50 font-semibold flex items-center justify-center space-x-2"
          >
            <span>📊</span>
            <span>{{ exportingReport ? 'Exportando...' : 'Exportar Excel' }}</span>
          </button>
        </div>
      </div>

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

        <!-- Alert Card for Low Stock -->
        <div
          v-if="stats.products_low_stock > 0"
          class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mb-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="ml-3 flex-1">
              <h3 class="text-lg font-semibold text-yellow-800">
                ⚠️ Alerta de Inventario
              </h3>
              <p class="text-sm text-yellow-700 mt-1">
                Tienes <strong>{{ stats.products_low_stock }}</strong> producto(s) con stock bajo
                <span v-if="stats.products_out_of_stock > 0">
                  y <strong>{{ stats.products_out_of_stock }}</strong> producto(s) agotado(s)
                </span>
              </p>
            </div>
            <router-link
              to="/admin/products?filter=low_stock"
              class="ml-3 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition"
            >
              Revisar Inventario
            </router-link>
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
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold">Productos con Bajo Stock</h2>
              <span
                v-if="stats.products_low_stock > 0"
                class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-semibold"
              >
                {{ stats.products_low_stock }} alertas
              </span>
            </div>
            <div v-if="lowStockProducts.length === 0" class="text-center py-8 text-gray-500">
              ✅ Todos los productos tienen stock adecuado
            </div>
            <div v-else class="space-y-3">
              <div
                v-for="product in lowStockProducts"
                :key="product.id"
                class="flex items-center justify-between p-3 rounded-lg"
                :class="product.is_out_of_stock ? 'bg-red-50' : 'bg-yellow-50'"
              >
                <div class="flex-1">
                  <div class="flex items-center space-x-2">
                    <p class="font-semibold text-dark">{{ product.name }}</p>
                    <span
                      v-if="product.is_out_of_stock"
                      class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-800 font-semibold"
                    >
                      ❌ Agotado
                    </span>
                    <span
                      v-else-if="product.is_low_stock"
                      class="px-2 py-1 text-xs rounded-full bg-yellow-200 text-yellow-800 font-semibold"
                    >
                      ⚠️ Bajo
                    </span>
                  </div>
                  <p class="text-sm text-gray-600">
                    SKU: {{ product.sku }} | Umbral: {{ product.low_stock_threshold }}
                  </p>
                </div>
                <div class="text-right">
                  <span
                    class="text-lg font-bold"
                    :class="{
                      'text-red-600': product.is_out_of_stock,
                      'text-yellow-600': product.is_low_stock && !product.is_out_of_stock
                    }"
                  >
                    {{ product.stock }} unidades
                  </span>
                </div>
              </div>
              <router-link
                to="/admin/products?filter=low_stock"
                class="block text-center text-primary font-semibold hover:underline mt-4"
              >
                Ver todos los productos con stock bajo →
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

// Sales report export state
const reportStartDate = ref('')
const reportEndDate = ref('')
const reportGroupBy = ref('day')
const exportingReport = ref(false)

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

const exportSalesReport = async () => {
  exportingReport.value = true
  try {
    const params = new URLSearchParams()
    if (reportStartDate.value) params.append('start_date', reportStartDate.value)
    if (reportEndDate.value) params.append('end_date', reportEndDate.value)
    params.append('group_by', reportGroupBy.value)

    const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
    const token = localStorage.getItem('auth_token')

    const url = `${API_URL}/api/v1/admin/export/sales-report?${params.toString()}`

    const response = await fetch(url, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (!response.ok) throw new Error('Error al exportar')

    const blob = await response.blob()
    const downloadUrl = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = downloadUrl
    link.download = `reporte_ventas_${new Date().toISOString().split('T')[0]}.xlsx`
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(downloadUrl)

    toast.success('Reporte exportado exitosamente')
  } catch (error) {
    console.error('Error exporting:', error)
    toast.error('Error al exportar reporte')
  } finally {
    exportingReport.value = false
  }
}

onMounted(() => {
  loadDashboard()
})
</script>
