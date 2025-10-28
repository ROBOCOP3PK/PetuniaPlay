<template>
  <div class="space-y-6">
    <!-- Reviews Header -->
    <div class="flex items-center justify-between">
      <h3 class="text-xl font-bold text-gray-900">
        Reseñas de clientes
      </h3>
      <button
        v-if="canWriteReview"
        @click="$emit('write-review')"
        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors"
      >
        Escribir reseña
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="!loading && reviews.length === 0"
      class="text-center py-12 bg-gray-50 rounded-lg"
    >
      <i class="pi pi-comments text-6xl text-gray-400"></i>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Sin reseñas</h3>
      <p class="mt-1 text-sm text-gray-500">
        Sé el primero en escribir una reseña para este producto
      </p>
    </div>

    <!-- Reviews List -->
    <div v-else class="space-y-6">
      <div
        v-for="review in reviews"
        :key="review.id"
        class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow"
      >
        <!-- Review Header -->
        <div class="flex items-start justify-between mb-3">
          <div class="flex items-center gap-3">
            <!-- User Avatar -->
            <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
              {{ getInitials(review.user?.name) }}
            </div>

            <!-- User Info -->
            <div>
              <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-900">
                  {{ review.user?.name || 'Usuario' }}
                </span>
                <span
                  v-if="review.is_verified_purchase"
                  class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"
                >
                  <i class="pi pi-verified text-xs mr-1"></i>
                  Compra verificada
                </span>
              </div>
              <p class="text-sm text-gray-500">{{ review.created_at_human }}</p>
            </div>
          </div>

          <!-- Action Menu (for own reviews) -->
          <div v-if="isOwnReview(review)" class="flex items-center gap-2">
            <button
              @click="$emit('edit-review', review)"
              class="text-gray-400 hover:text-primary transition-colors"
              title="Editar reseña"
            >
              <i class="pi pi-pencil text-xl"></i>
            </button>
            <button
              @click="$emit('delete-review', review)"
              class="text-gray-400 hover:text-red-600 transition-colors"
              title="Eliminar reseña"
            >
              <i class="pi pi-trash text-xl"></i>
            </button>
          </div>
        </div>

        <!-- Rating -->
        <div class="mb-3">
          <StarRating :model-value="review.rating" :show-rating="false" />
        </div>

        <!-- Review Title -->
        <h4 v-if="review.title" class="font-semibold text-gray-900 mb-2">
          {{ review.title }}
        </h4>

        <!-- Review Comment -->
        <p class="text-gray-700 leading-relaxed">
          {{ review.comment }}
        </p>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button
          @click="$emit('page-change', pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Anterior
        </button>

        <span class="px-4 py-2 text-gray-700">
          Página {{ pagination.current_page }} de {{ pagination.last_page }}
        </span>

        <button
          @click="$emit('page-change', pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Siguiente
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import StarRating from './StarRating.vue'
import { useAuthStore } from '../stores/authStore'

const props = defineProps({
  reviews: {
    type: Array,
    default: () => []
  },
  pagination: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  },
  canWriteReview: {
    type: Boolean,
    default: false
  }
})

defineEmits(['write-review', 'edit-review', 'delete-review', 'page-change'])

const authStore = useAuthStore()

const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

const isOwnReview = (review) => {
  return authStore.isAuthenticated && authStore.user?.id === review.user_id
}
</script>
