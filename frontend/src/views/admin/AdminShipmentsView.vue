<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Confirm Dialog -->
      <ConfirmDialog
        v-model:isOpen="confirmDialog.isOpen"
        :title="confirmDialog.title"
        :message="confirmDialog.message"
        :type="confirmDialog.type"
        :confirm-text="confirmDialog.confirmText"
        :cancel-text="confirmDialog.cancelText"
        @confirm="confirmDialog.onConfirm"
        @cancel="confirmDialog.onCancel"
      />

      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Envíos</h1>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900 dark:to-orange-800 rounded-lg shadow-md p-6 border-l-4 border-orange-500">
          <p class="text-orange-800 dark:text-orange-200 text-sm font-semibold">⏳ Por Despachar</p>
          <p class="text-3xl font-bold text-orange-900 dark:text-white">{{ shippingStats.pending_shipment || 0 }}</p>
          <p class="text-xs text-orange-700 dark:text-orange-300 mt-1">Órdenes pagadas sin envío</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-100 to-yellow-50 dark:from-yellow-900 dark:to-yellow-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
          <p class="text-yellow-800 dark:text-yellow-200 text-sm font-semibold">📦 Listas</p>
          <p class="text-3xl font-bold text-yellow-900 dark:text-white">{{ shippingStats.ready_to_ship || 0 }}</p>
          <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Status: Processing</p>
        </div>
        <div class="bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
          <p class="text-blue-800 dark:text-blue-200 text-sm font-semibold">🚚 En Tránsito</p>
          <p class="text-3xl font-bold text-blue-900 dark:text-white">{{ shippingStats.in_transit || 0 }}</p>
          <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Envíos en camino</p>
        </div>
        <div class="bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900 dark:to-green-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
          <p class="text-green-800 dark:text-green-200 text-sm font-semibold">✅ Entregados</p>
          <p class="text-3xl font-bold text-green-900 dark:text-white">{{ shippingStats.delivered || 0 }}</p>
          <p class="text-xs text-green-700 dark:text-green-300 mt-1">Completados</p>
        </div>
        <div class="bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900 dark:to-purple-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
          <p class="text-purple-800 dark:text-purple-200 text-sm font-semibold">📊 Total Despachados</p>
          <p class="text-3xl font-bold text-purple-900 dark:text-white">{{ shippingStats.shipped || 0 }}</p>
          <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">Histórico</p>
        </div>
      </div>

      <!-- Urgent Orders Alert -->
      <div v-if="shippingStats.oldest_pending && shippingStats.oldest_pending.length > 0" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg shadow-md p-6">
        <div class="flex items-start">
          <div class="text-3xl mr-4">⚠️</div>
          <div class="flex-1">
            <h3 class="text-lg font-bold text-red-900 dark:text-red-200 mb-2">
              ¡Órdenes Urgentes! - {{ shippingStats.oldest_pending.length }} órdenes esperando despacho
            </h3>
            <div class="space-y-2">
              <div v-for="order in shippingStats.oldest_pending" :key="order.order_number" class="bg-white dark:bg-gray-800 rounded p-3 flex items-center justify-between">
                <div>
                  <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ order.order_number }}</span>
                  <span class="text-gray-600 dark:text-gray-400 ml-3">{{ order.customer }}</span>
                  <span class="text-sm text-gray-500 dark:text-gray-500 ml-3">{{ formatPrice(order.total) }}</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-sm font-bold">
                    {{ order.days_waiting }} días
                  </span>
                  <button
                    @click="createShipmentForOrder(order)"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition font-semibold text-sm"
                  >
                    📦 Crear Envío
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <nav class="flex -mb-px">
            <button
              @click="activeTab = 'pending'"
              :class="[
                'px-6 py-4 text-sm font-medium border-b-2 transition',
                activeTab === 'pending'
                  ? 'border-primary text-primary'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
              ]"
            >
              ⏳ Pendientes de Despacho ({{ shippingStats.pending_shipment || 0 }})
            </button>
            <button
              @click="activeTab = 'shipped'"
              :class="[
                'px-6 py-4 text-sm font-medium border-b-2 transition',
                activeTab === 'shipped'
                  ? 'border-primary text-primary'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
              ]"
            >
              📦 Ya Despachadas ({{ shippingStats.shipped || 0 }})
            </button>
            <button
              @click="activeTab = 'all'"
              :class="[
                'px-6 py-4 text-sm font-medium border-b-2 transition',
                activeTab === 'all'
                  ? 'border-primary text-primary'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
              ]"
            >
              📋 Todos los Envíos
            </button>
          </nav>
        </div>
      </div>

      <!-- Filters & Search (only for 'all' tab) -->
      <div v-if="activeTab === 'all'" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
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

      <!-- Pending Orders Table (activeTab === 'pending') -->
      <div v-if="activeTab === 'pending'" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pedido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dirección</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Días</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-if="loadingPending">
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  Cargando órdenes pendientes...
                </td>
              </tr>
              <tr v-else-if="pendingOrders.length === 0">
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  ¡No hay órdenes pendientes de despacho!
                </td>
              </tr>
              <tr v-for="order in pendingOrders" :key="order.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="font-mono font-bold text-gray-900 dark:text-white">{{ order.order_number }}</span>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ order.status }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ order.user?.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ order.user?.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                  {{ formatPrice(order.total) }}
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ order.shipping_address?.city }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ order.shipping_address?.address_line_1 }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(order.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getDaysWaitingClass(order)">
                    {{ calculateDaysWaiting(order.created_at) }} días
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button
                    @click="openCreateShipmentModal(order)"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition font-semibold text-sm"
                  >
                    📦 Crear Envío
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Shipments Table (activeTab === 'shipped' or 'all') -->
      <div v-if="activeTab === 'shipped' || activeTab === 'all'" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
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
              {{ modalMode === 'view' ? 'Detalles del Envío' : modalMode === 'create' ? 'Crear Envío' : 'Editar Envío' }}
            </h2>
          </div>

          <div class="p-6 space-y-4">
            <!-- Order Info -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Información del Pedido</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Pedido:</strong> {{ modalMode === 'create' ? selectedOrder?.order_number : selectedShipment?.order_number }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Cliente:</strong> {{ modalMode === 'create' ? selectedOrder?.user?.name : selectedShipment?.order?.user?.name }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ modalMode === 'create' ? selectedOrder?.user?.email : selectedShipment?.order?.user?.email }}</p>
              <p v-if="modalMode === 'create'" class="text-sm text-gray-600 dark:text-gray-400"><strong>Total:</strong> {{ formatPrice(selectedOrder?.total) }}</p>
            </div>

            <div v-if="modalMode === 'edit' || modalMode === 'create'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número de Tracking *</label>
              <input
                v-model="shipmentForm.tracking_number"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
                placeholder="SERV-ABC123456"
                required
              />
            </div>
            <div v-else>
              <p class="text-sm"><strong>Tracking:</strong> <span class="font-mono">{{ selectedShipment?.tracking_number }}</span></p>
            </div>

            <div v-if="modalMode === 'edit' || modalMode === 'create'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transportadora *</label>
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

            <div v-if="modalMode === 'edit' || modalMode === 'create'">
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

            <div v-if="modalMode === 'edit' || modalMode === 'create'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas (Opcional)</label>
              <textarea
                v-model="shipmentForm.notes"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary"
                placeholder="Información adicional sobre el envío..."
              ></textarea>
            </div>
            <div v-else-if="selectedShipment?.notes">
              <p class="text-sm"><strong>Notas:</strong> {{ selectedShipment?.notes }}</p>
            </div>

            <!-- Shipping Address -->
            <div v-if="(modalMode === 'create' && selectedOrder?.shipping_address) || selectedShipment?.order?.shipping_address" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Dirección de Envío</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                <template v-if="modalMode === 'create'">
                  {{ selectedOrder.shipping_address.full_name }}<br>
                  {{ selectedOrder.shipping_address.address_line_1 }}<br>
                  {{ selectedOrder.shipping_address.city }}, {{ selectedOrder.shipping_address.state }}<br>
                  {{ selectedOrder.shipping_address.country }}
                </template>
                <template v-else>
                  {{ selectedShipment.order.shipping_address.full_name }}<br>
                  {{ selectedShipment.order.shipping_address.address_line_1 }}<br>
                  {{ selectedShipment.order.shipping_address.city }}, {{ selectedShipment.order.shipping_address.state }}<br>
                  {{ selectedShipment.order.shipping_address.country }}
                </template>
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
              v-if="modalMode === 'edit' || modalMode === 'create'"
              @click="saveShipment"
              :disabled="saving"
              class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition disabled:opacity-50"
            >
              {{ saving ? 'Guardando...' : modalMode === 'create' ? 'Crear Envío' : 'Guardar Cambios' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import { shipmentService } from '../../services/shipmentService'
import api from '../../services/api'
import { useNotification } from '@/composables/useNotification'
import { useFormat } from '@/composables/useFormat'
import { useConfirm } from '@/composables/useConfirm'

const { notifySuccess, notifyError } = useNotification()
const { formatPrice } = useFormat()
const { confirmDialog, showConfirm } = useConfirm()

// Tab management
const activeTab = ref('pending') // 'pending', 'shipped', 'all'

// Data refs
const shipments = ref([])
const pendingOrders = ref([])
const shippedOrders = ref([])
const stats = ref({})
const shippingStats = ref({})

// Loading states
const loading = ref(false)
const loadingPending = ref(false)
const loadingShipped = ref(false)
const saving = ref(false)

// Modal states
const showModal = ref(false)
const modalMode = ref('view') // 'view', 'edit', or 'create'
const selectedShipment = ref(null)
const selectedOrder = ref(null)

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
    notifyError('Error al cargar los envíos')
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
    if (modalMode.value === 'create') {
      // Create new shipment
      await shipmentService.create({
        order_id: selectedOrder.value.id,
        ...shipmentForm.value
      })
      notifySuccess('Envío creado exitosamente')
      closeModal()
      loadPendingOrders()
      loadShippedOrders()
      loadShippingStats()
    } else {
      // Update existing shipment
      await shipmentService.update(selectedShipment.value.id, shipmentForm.value)
      notifySuccess('Envío actualizado exitosamente')
      closeModal()
      loadShipments(pagination.value.current_page)
      loadShippedOrders()
      loadShippingStats()
      loadStats()
    }
  } catch (error) {
    console.error('Error saving shipment:', error)
    notifyError(error.response?.data?.message || 'Error al guardar el envío')
  } finally {
    saving.value = false
  }
}

const confirmDelete = async (shipment) => {
  const confirmed = await showConfirm({
    title: '¿Eliminar envío?',
    message: `¿Estás seguro de eliminar el envío ${shipment.tracking_number}?`,
    type: 'danger',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    try {
      await shipmentService.delete(shipment.id)
      notifySuccess('Envío eliminado exitosamente')
      loadShipments(pagination.value.current_page)
      loadStats()
    } catch (error) {
      console.error('Error deleting shipment:', error)
      notifyError(error.response?.data?.message || 'Error al eliminar el envío')
    }
  }
}

const closeModal = () => {
  showModal.value = false
  selectedShipment.value = null
  selectedOrder.value = null
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

const calculateDaysWaiting = (createdAt) => {
  const created = new Date(createdAt)
  const now = new Date()
  const diffTime = Math.abs(now - created)
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return diffDays
}

const getDaysWaitingClass = (order) => {
  const days = calculateDaysWaiting(order.created_at)
  if (days >= 3) {
    return 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-sm font-bold'
  } else if (days >= 2) {
    return 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-bold'
  } else {
    return 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-bold'
  }
}

// Load pending orders (orders without shipment)
const loadPendingOrders = async () => {
  loadingPending.value = true
  try {
    const response = await api.get('/admin/orders/pending-shipment')
    pendingOrders.value = response.data.data
  } catch (error) {
    console.error('Error loading pending orders:', error)
    notifyError('Error al cargar órdenes pendientes')
  } finally {
    loadingPending.value = false
  }
}

// Load shipped orders (orders with shipment)
const loadShippedOrders = async () => {
  loadingShipped.value = true
  try {
    const response = await api.get('/admin/orders/shipped')
    shippedOrders.value = response.data.data
  } catch (error) {
    console.error('Error loading shipped orders:', error)
    notifyError('Error al cargar órdenes despachadas')
  } finally {
    loadingShipped.value = false
  }
}

// Load shipping statistics
const loadShippingStats = async () => {
  try {
    const response = await api.get('/admin/orders/shipping-stats')
    shippingStats.value = response.data
  } catch (error) {
    console.error('Error loading shipping stats:', error)
  }
}

// Open create shipment modal for an order
const openCreateShipmentModal = (order) => {
  selectedOrder.value = order
  shipmentForm.value = {
    tracking_number: '',
    carrier: 'Servientrega',
    status: 'pending',
    notes: ''
  }
  modalMode.value = 'create'
  showModal.value = true
}

// Create shipment for order from urgent alerts
const createShipmentForOrder = (order) => {
  // Find full order data from pendingOrders
  const fullOrder = pendingOrders.value.find(o => o.order_number === order.order_number)
  if (fullOrder) {
    openCreateShipmentModal(fullOrder)
  } else {
    notifyError('No se pudo encontrar la orden')
  }
}


// Watch for tab changes
watch(activeTab, (newTab) => {
  if (newTab === 'pending') {
    loadPendingOrders()
  } else if (newTab === 'shipped') {
    loadShippedOrders()
  } else if (newTab === 'all') {
    loadShipments()
  }
})

onMounted(() => {
  loadShippingStats()
  loadPendingOrders() // Load pending by default
  loadStats() // Load shipment stats for 'all' tab
})
</script>
