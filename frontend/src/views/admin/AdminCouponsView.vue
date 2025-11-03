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
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Cupones</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">Administra códigos de descuento y promociones</p>
        </div>
        <button
          @click="openModal()"
          class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Nuevo Cupón
        </button>
      </div>

      <!-- Stats Cards -->
      <div v-if="stats" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Cupones</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_coupons }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 p-3 rounded-lg">
              <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cupones Activos</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.active_coupons }}</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900 dark:bg-opacity-30 p-3 rounded-lg">
              <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Usos Totales</p>
              <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ stats.total_usage }}</p>
            </div>
            <div class="bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 p-3 rounded-lg">
              <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Más Usado</p>
              <p class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ stats.most_used[0]?.code || 'N/A' }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ stats.most_used[0]?.usage_count || 0 }} usos</p>
            </div>
            <div class="bg-orange-100 dark:bg-orange-900 dark:bg-opacity-30 p-3 rounded-lg">
              <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por código..."
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          />
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          >
            <option value="">Todos los estados</option>
            <option value="active">Activos</option>
            <option value="expired">Expirados/Inactivos</option>
          </select>
          <button
            @click="loadCoupons"
            class="bg-gray-800 dark:bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors"
          >
            Buscar
          </button>
        </div>
      </div>

      <!-- Coupons Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>

        <div v-else-if="coupons.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
          </svg>
          <p class="mt-2 text-gray-600 dark:text-gray-400">No se encontraron cupones</p>
        </div>

        <table v-else class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mín. Compra</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usos</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vigencia</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="coupon in coupons" :key="coupon.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-3 py-1 font-mono font-bold text-sm bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900 dark:bg-opacity-30 text-purple-800 dark:text-purple-200 rounded-md border border-purple-200 dark:border-purple-700">{{ coupon.code }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="coupon.type === 'percentage' ? 'bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300' : 'bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300'">
                  {{ coupon.type === 'percentage' ? 'Porcentaje' : 'Monto Fijo' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                {{ coupon.type === 'percentage' ? `${coupon.value}%` : `$${formatPrice(coupon.value)}` }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ coupon.min_purchase_amount ? `$${formatPrice(coupon.min_purchase_amount)}` : 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">
                  <span class="font-semibold">{{ coupon.usage_count }}</span>
                  <span v-if="coupon.usage_limit" class="text-gray-500 dark:text-gray-400"> / {{ coupon.usage_limit }}</span>
                  <span v-else class="text-gray-500 dark:text-gray-400"> / ∞</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                <div v-if="coupon.valid_from || coupon.valid_until">
                  <div v-if="coupon.valid_from">Desde: {{ formatDate(coupon.valid_from) }}</div>
                  <div v-if="coupon.valid_until">Hasta: {{ formatDate(coupon.valid_until) }}</div>
                </div>
                <span v-else>Sin límite</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  @click="toggleStatus(coupon)"
                  class="px-3 py-1 text-xs font-semibold rounded-full transition-colors"
                  :class="coupon.is_active ? 'bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900 dark:hover:bg-opacity-50' : 'bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900 dark:hover:bg-opacity-50'"
                >
                  {{ coupon.is_active ? 'Activo' : 'Inactivo' }}
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex items-center gap-2">
                  <button
                    @click="openModal(coupon)"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                    title="Editar"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete(coupon)"
                    :disabled="coupon.usage_count > 0"
                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :title="coupon.usage_count > 0 ? 'No se puede eliminar un cupón que ya fue usado' : 'Eliminar'"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t dark:border-gray-600 flex items-center justify-between">
          <div class="text-sm text-gray-700 dark:text-gray-300">
            Mostrando {{ (pagination.current_page - 1) * pagination.per_page + 1 }} -
            {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
            de {{ pagination.total }} cupones
          </div>
          <div class="flex gap-2">
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Anterior
            </button>
            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Siguiente
            </button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
          <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80" @click="closeModal"></div>

          <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ editingCoupon ? 'Editar Cupón' : 'Nuevo Cupón' }}
              </h3>
              <button @click="closeModal" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="saveCoupon" class="px-6 py-4">
              <div class="space-y-4">
                <!-- Code -->
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Código del Cupón *</label>
                  <input
                    v-model="couponForm.code"
                    type="text"
                    required
                    maxlength="50"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent uppercase"
                    placeholder="DESCUENTO20"
                    @input="couponForm.code = couponForm.code.toUpperCase()"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Se convertirá automáticamente a mayúsculas</p>
                </div>

                <!-- Type and Value -->
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Tipo *</label>
                    <select
                      v-model="couponForm.type"
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                      <option value="percentage">Porcentaje</option>
                      <option value="fixed">Monto Fijo</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
                      Valor * {{ couponForm.type === 'percentage' ? '(%)' : '($)' }}
                    </label>
                    <input
                      v-model.number="couponForm.value"
                      type="number"
                      required
                      min="0"
                      :max="couponForm.type === 'percentage' ? 100 : undefined"
                      step="0.01"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                      placeholder="20"
                    />
                  </div>
                </div>

                <!-- Min Purchase Amount -->
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Monto Mínimo de Compra ($)</label>
                  <input
                    v-model.number="couponForm.min_purchase_amount"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="100000"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Deja vacío si no hay monto mínimo</p>
                </div>

                <!-- Usage Limit -->
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Límite de Usos</label>
                  <input
                    v-model.number="couponForm.usage_limit"
                    type="number"
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="100"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Deja vacío para usos ilimitados</p>
                </div>

                <!-- Valid Dates -->
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Válido Desde</label>
                    <input
                      v-model="couponForm.valid_from"
                      type="datetime-local"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Válido Hasta</label>
                    <input
                      v-model="couponForm.valid_until"
                      type="datetime-local"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                  </div>
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                  <input
                    v-model="couponForm.is_active"
                    type="checkbox"
                    id="is_active"
                    class="mr-2"
                  />
                  <label for="is_active" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Cupón Activo</label>
                </div>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  :disabled="saving"
                  class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark disabled:opacity-50"
                >
                  {{ saving ? 'Guardando...' : (editingCoupon ? 'Actualizar' : 'Crear') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useNotification } from '@/composables/useNotification'
import { useFormat } from '@/composables/useFormat'
import { useConfirm } from '@/composables/useConfirm'
import AdminLayout from '../../layouts/AdminLayout.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import couponService from '../../services/couponService'

const { notifySuccess, notifyError } = useNotification()
const { formatPrice } = useFormat()
const { confirmDialog, showConfirm } = useConfirm()

// State
const coupons = ref([])
const stats = ref(null)
const loading = ref(false)
const saving = ref(false)
const showModal = ref(false)
const editingCoupon = ref(null)

const search = ref('')
const filterStatus = ref('')
const pagination = ref(null)

const emptyForm = {
  code: '',
  type: 'percentage',
  value: 0,
  min_purchase_amount: null,
  usage_limit: null,
  valid_from: null,
  valid_until: null,
  is_active: true
}

const couponForm = reactive({ ...emptyForm })

// Methods
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('es-CO', { year: 'numeric', month: 'short', day: 'numeric' })
}

const loadCoupons = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: 15,
      search: search.value,
      status: filterStatus.value
    }

    const response = await couponService.getAll(params)
    coupons.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      per_page: response.data.per_page,
      total: response.data.total
    }
  } catch (error) {
    notifyError('Error al cargar cupones')
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    const response = await couponService.getStats()
    stats.value = response.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const openModal = (coupon = null) => {
  if (coupon) {
    editingCoupon.value = coupon
    Object.assign(couponForm, {
      code: coupon.code,
      type: coupon.type,
      value: coupon.value,
      min_purchase_amount: coupon.min_purchase_amount,
      usage_limit: coupon.usage_limit,
      valid_from: coupon.valid_from ? coupon.valid_from.replace(' ', 'T').substring(0, 16) : null,
      valid_until: coupon.valid_until ? coupon.valid_until.replace(' ', 'T').substring(0, 16) : null,
      is_active: coupon.is_active
    })
  } else {
    editingCoupon.value = null
    Object.assign(couponForm, emptyForm)
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingCoupon.value = null
  Object.assign(couponForm, emptyForm)
}

const saveCoupon = async () => {
  // Validate
  if (!couponForm.code || !couponForm.type || couponForm.value <= 0) {
    notifyError('Por favor completa todos los campos obligatorios')
    return
  }

  if (couponForm.type === 'percentage' && couponForm.value > 100) {
    notifyError('El porcentaje no puede ser mayor a 100')
    return
  }

  saving.value = true

  try {
    const data = {
      code: couponForm.code.toUpperCase(),
      type: couponForm.type,
      value: couponForm.value,
      min_purchase_amount: couponForm.min_purchase_amount || null,
      usage_limit: couponForm.usage_limit || null,
      valid_from: couponForm.valid_from || null,
      valid_until: couponForm.valid_until || null,
      is_active: couponForm.is_active
    }

    if (editingCoupon.value) {
      await couponService.update(editingCoupon.value.id, data)
      notifySuccess('Cupón actualizado exitosamente')
    } else {
      await couponService.create(data)
      notifySuccess('Cupón creado exitosamente')
    }

    closeModal()
    loadCoupons()
    loadStats()
  } catch (error) {
    const message = error.response?.data?.message || 'Error al guardar cupón'
    notifyError(message)
  } finally {
    saving.value = false
  }
}

const toggleStatus = async (coupon) => {
  try {
    await couponService.toggleStatus(coupon.id)
    notifySuccess(coupon.is_active ? 'Cupón desactivado' : 'Cupón activado')
    loadCoupons()
    loadStats()
  } catch (error) {
    notifyError('Error al cambiar estado del cupón')
  }
}

const confirmDelete = async (coupon) => {
  const confirmed = await showConfirm({
    title: '¿Eliminar cupón?',
    message: `¿Estás seguro de que deseas eliminar el cupón "${coupon.code}"?`,
    type: 'danger',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    try {
      await couponService.delete(coupon.id)
      notifySuccess('Cupón eliminado exitosamente')
      loadCoupons()
      loadStats()
    } catch (error) {
      const message = error.response?.data?.message || 'Error al eliminar cupón'
      notifyError(message)
    }
  }
}

const changePage = (page) => {
  loadCoupons(page)
}

// Initialize
onMounted(() => {
  loadCoupons()
  loadStats()
})
</script>
