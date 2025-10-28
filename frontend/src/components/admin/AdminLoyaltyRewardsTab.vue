<template>
  <div>
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex gap-4">
        <!-- Type Filter -->
        <select
          v-model="filters.type"
          @change="loadRewards"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
        >
          <option value="">Todos los tipos</option>
          <option value="permanent">Permanente</option>
          <option value="campaign">Campaña</option>
        </select>

        <!-- Status Filter -->
        <select
          v-model="filters.status"
          @change="loadRewards"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
        >
          <option value="">Todos los estados</option>
          <option value="active">Activas</option>
          <option value="inactive">Inactivas</option>
        </select>
      </div>

      <button
        @click="openModal()"
        class="bg-primary hover:bg-primary-dark dark:bg-primary-light dark:hover:bg-primary text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2"
      >
        <i class="pi pi-plus"></i>
        Nueva Recompensa
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <i class="pi pi-spin pi-spinner text-4xl text-primary dark:text-primary-light"></i>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="rewards.length === 0"
      class="text-center py-20 bg-gray-50 dark:bg-gray-700 rounded-lg"
    >
      <i class="pi pi-inbox text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
      <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
        No hay recompensas
      </h3>
      <p class="text-gray-500 dark:text-gray-400 mb-4">
        Crea tu primera recompensa para comenzar
      </p>
      <button
        @click="openModal()"
        class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-semibold transition-colors"
      >
        <i class="pi pi-plus mr-2"></i>
        Crear Recompensa
      </button>
    </div>

    <!-- Rewards Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Nombre
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Tipo
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Audiencia
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Compras Req.
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Producto
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Fechas
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Estado
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
            >
              Acciones
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr
            v-for="reward in rewards"
            :key="reward.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
          >
            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
              {{ reward.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                :class="
                  reward.type === 'permanent'
                    ? 'bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-700 dark:text-blue-400'
                    : 'bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 text-purple-700 dark:text-purple-400'
                "
              >
                {{ reward.type === 'permanent' ? 'Permanente' : 'Campaña' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
              >
                {{ getAudienceText(reward.target_audience) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
              {{ reward.orders_required }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
              {{ reward.product?.name || 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
              <div v-if="reward.type === 'campaign'">
                <div class="text-xs">{{ formatDate(reward.start_date) }}</div>
                <div class="text-xs">{{ formatDate(reward.end_date) }}</div>
              </div>
              <span v-else class="text-gray-500 dark:text-gray-400">-</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  :checked="reward.is_active"
                  @change="toggleReward(reward)"
                  class="sr-only peer"
                />
                <div
                  class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary peer-focus:ring-opacity-30 dark:peer-focus:ring-primary-dark rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"
                ></div>
              </label>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <div class="flex items-center gap-2">
                <button
                  @click="openModal(reward)"
                  class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                  title="Editar"
                >
                  <i class="pi pi-pencil text-lg"></i>
                </button>
                <button
                  @click="deleteReward(reward)"
                  class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                  title="Eliminar"
                >
                  <i class="pi pi-trash text-lg"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <LoyaltyRewardModal
      v-if="showModal"
      :reward="selectedReward"
      @close="closeModal"
      @saved="handleSaved"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import loyaltyService from '../../services/loyaltyService'
import LoyaltyRewardModal from './LoyaltyRewardModal.vue'

const toast = useToast()
const emit = defineEmits(['refresh'])

// State
const loading = ref(true)
const rewards = ref([])
const showModal = ref(false)
const selectedReward = ref(null)
const filters = ref({
  type: '',
  status: ''
})

// Methods
const loadRewards = async () => {
  loading.value = true
  try {
    const response = await loyaltyService.getRewards(filters.value)
    rewards.value = response.data.data || []
  } catch (error) {
    console.error('Error loading rewards:', error)
    toast.error('Error al cargar las recompensas')
  } finally {
    loading.value = false
  }
}

const openModal = (reward = null) => {
  selectedReward.value = reward
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedReward.value = null
}

const handleSaved = () => {
  closeModal()
  loadRewards()
  emit('refresh')
}

const toggleReward = async (reward) => {
  try {
    await loyaltyService.toggleReward(reward.id)
    toast.success(`Recompensa ${reward.is_active ? 'desactivada' : 'activada'} exitosamente`)
    await loadRewards()
    emit('refresh')
  } catch (error) {
    console.error('Error toggling reward:', error)
    toast.error('Error al cambiar el estado de la recompensa')
  }
}

const deleteReward = async (reward) => {
  if (!confirm(`¿Estás seguro de que deseas eliminar la recompensa "${reward.name}"?`)) {
    return
  }

  try {
    await loyaltyService.deleteReward(reward.id)
    toast.success('Recompensa eliminada exitosamente')
    await loadRewards()
    emit('refresh')
  } catch (error) {
    console.error('Error deleting reward:', error)
    toast.error('Error al eliminar la recompensa')
  }
}

const getAudienceText = (audience) => {
  const texts = {
    all: 'Todos',
    new: 'Nuevos',
    returning: 'Recurrentes'
  }
  return texts[audience] || audience
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Lifecycle
onMounted(() => {
  loadRewards()
})
</script>
