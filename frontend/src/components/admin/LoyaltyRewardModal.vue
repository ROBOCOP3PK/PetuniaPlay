<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="close"
  >
    <div
      class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
    >
      <!-- Modal Header -->
      <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
          {{ isEdit ? 'Editar Recompensa' : 'Nueva Recompensa' }}
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          <i class="pi pi-times text-2xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <form @submit.prevent="save" class="p-6 space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Nombre de la Recompensa *
          </label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            placeholder="Ej: Producto Gratis en tu 5ta compra"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Descripción *
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            required
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
            placeholder="Describe la recompensa..."
          ></textarea>
        </div>

        <!-- Type and Audience -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Type -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Tipo *
            </label>
            <select
              v-model="form.type"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            >
              <option value="permanent">Permanente</option>
              <option value="campaign">Campaña</option>
            </select>
          </div>

          <!-- Audience -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Audiencia Objetivo *
            </label>
            <select
              v-model="form.target_audience"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            >
              <option value="all">Todos los usuarios</option>
              <option value="new">Solo nuevos usuarios</option>
              <option value="returning">Solo usuarios recurrentes</option>
            </select>
          </div>
        </div>

        <!-- Orders Required and Priority -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Orders Required -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Compras Requeridas *
            </label>
            <input
              v-model.number="form.orders_required"
              type="number"
              min="1"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Ej: 5"
            />
          </div>

          <!-- Priority -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Prioridad
            </label>
            <input
              v-model.number="form.priority"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="0 (mayor número = mayor prioridad)"
            />
          </div>
        </div>

        <!-- Product Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
            Producto de Recompensa *
          </label>
          <div class="relative">
            <select
              v-model.number="form.product_id"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent appearance-none"
            >
              <option value="" disabled>Selecciona un producto</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.name }} - ${{ product.price }}
              </option>
            </select>
            <i
              class="pi pi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
            ></i>
          </div>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            El usuario recibirá este producto al canjear la recompensa
          </p>
        </div>

        <!-- Campaign Dates (only if type is campaign) -->
        <div v-if="form.type === 'campaign'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Start Date -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Fecha de Inicio *
            </label>
            <input
              v-model="form.start_date"
              type="date"
              :required="form.type === 'campaign'"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>

          <!-- End Date -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              Fecha de Fin *
            </label>
            <input
              v-model="form.end_date"
              type="date"
              :required="form.type === 'campaign'"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>
        </div>

        <!-- Active Status (only when editing) -->
        <div v-if="isEdit" class="flex items-center gap-3">
          <label class="relative inline-flex items-center cursor-pointer">
            <input v-model="form.is_active" type="checkbox" class="sr-only peer" />
            <div
              class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary peer-focus:ring-opacity-30 dark:peer-focus:ring-primary-dark rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"
            ></div>
          </label>
          <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Recompensa Activa
          </span>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <button
            type="button"
            @click="close"
            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-semibold transition-colors"
          >
            Cancelar
          </button>
          <button
            type="submit"
            :disabled="saving || loadingProducts"
            class="px-6 py-2 bg-primary hover:bg-primary-dark dark:bg-primary-light dark:hover:bg-primary text-white rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <i class="pi" :class="saving ? 'pi-spin pi-spinner' : 'pi-save'"></i>
            {{ saving ? 'Guardando...' : 'Guardar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import loyaltyService from '../../services/loyaltyService'
import productService from '../../services/productService'

const props = defineProps({
  reward: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])
const toast = useToast()

// State
const saving = ref(false)
const loadingProducts = ref(true)
const products = ref([])
const form = ref({
  name: '',
  description: '',
  type: 'permanent',
  target_audience: 'all',
  orders_required: 1,
  product_id: '',
  priority: 0,
  start_date: '',
  end_date: '',
  is_active: true
})

// Computed
const isEdit = computed(() => !!props.reward)

// Methods
const loadProducts = async () => {
  loadingProducts.value = true
  try {
    const response = await productService.getAll({ limit: 1000 })
    products.value = response.data.data || []
  } catch (error) {
    console.error('Error loading products:', error)
    toast.error('Error al cargar los productos')
  } finally {
    loadingProducts.value = false
  }
}

const initForm = () => {
  if (props.reward) {
    form.value = {
      name: props.reward.name || '',
      description: props.reward.description || '',
      type: props.reward.type || 'permanent',
      target_audience: props.reward.target_audience || 'all',
      orders_required: props.reward.orders_required || 1,
      product_id: props.reward.product_id || '',
      priority: props.reward.priority || 0,
      start_date: props.reward.start_date || '',
      end_date: props.reward.end_date || '',
      is_active: props.reward.is_active !== undefined ? props.reward.is_active : true
    }
  }
}

const save = async () => {
  // Validate campaign dates
  if (form.value.type === 'campaign') {
    if (!form.value.start_date || !form.value.end_date) {
      toast.error('Las fechas son requeridas para campañas')
      return
    }
    if (new Date(form.value.start_date) >= new Date(form.value.end_date)) {
      toast.error('La fecha de fin debe ser posterior a la fecha de inicio')
      return
    }
  }

  saving.value = true
  try {
    if (isEdit.value) {
      await loyaltyService.updateReward(props.reward.id, form.value)
      toast.success('Recompensa actualizada exitosamente')
    } else {
      await loyaltyService.createReward(form.value)
      toast.success('Recompensa creada exitosamente')
    }
    emit('saved')
  } catch (error) {
    console.error('Error saving reward:', error)
    const message = error.response?.data?.message || 'Error al guardar la recompensa'
    toast.error(message)
  } finally {
    saving.value = false
  }
}

const close = () => {
  emit('close')
}

// Lifecycle
onMounted(() => {
  loadProducts()
  initForm()
})
</script>
