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
      <svg
        class="mx-auto h-12 w-12 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
        />
      </svg>
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
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"
                    />
                  </svg>
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
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
            </button>
            <button
              @click="$emit('delete-review', review)"
              class="text-gray-400 hover:text-red-600 transition-colors"
              title="Eliminar reseña"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
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
import { useAuthStore } from '../stores/auth'

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
