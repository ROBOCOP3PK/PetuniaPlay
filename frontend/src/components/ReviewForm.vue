<template>
  <!-- Modal Overlay -->
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 overflow-y-auto"
    @click.self="closeModal"
  >
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div
        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
        @click="closeModal"
      ></div>

      <!-- Modal Panel -->
      <div
        class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
      >
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
          <h3 class="text-xl font-bold text-gray-900">
            {{ isEditing ? 'Editar reseña' : 'Escribir reseña' }}
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <i class="pi pi-times text-2xl"></i>
          </button>
        </div>

        <!-- Modal Body -->
        <form @submit.prevent="submitReview" class="px-6 py-4">
          <!-- Product Info (if provided) -->
          <div v-if="product" class="mb-6 p-4 bg-gray-50 rounded-lg flex items-center gap-4">
            <img
              v-if="product.primary_image"
              :src="product.primary_image"
              :alt="product.name"
              class="w-16 h-16 object-cover rounded-lg"
            />
            <div>
              <h4 class="font-semibold text-gray-900">{{ product.name }}</h4>
              <p class="text-sm text-gray-500">{{ product.category?.name }}</p>
            </div>
          </div>

          <!-- Rating -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Calificación <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-3">
              <StarRating
                v-model="form.rating"
                :interactive="true"
                size="lg"
              />
              <span class="text-lg font-semibold text-gray-700">
                {{ form.rating > 0 ? form.rating : 'Selecciona una calificación' }}
              </span>
            </div>
            <p v-if="errors.rating" class="mt-1 text-sm text-red-600">
              {{ errors.rating }}
            </p>
          </div>

          <!-- Title -->
          <div class="mb-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
              Título (opcional)
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              placeholder="Resumen de tu experiencia"
              maxlength="255"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            />
            <p class="mt-1 text-xs text-gray-500">
              {{ form.title?.length || 0 }}/255 caracteres
            </p>
          </div>

          <!-- Comment -->
          <div class="mb-6">
            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">
              Comentario <span class="text-red-500">*</span>
            </label>
            <textarea
              id="comment"
              v-model="form.comment"
              rows="5"
              placeholder="Cuéntanos sobre tu experiencia con este producto..."
              maxlength="1000"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
            ></textarea>
            <p class="mt-1 text-xs text-gray-500">
              {{ form.comment?.length || 0 }}/1000 caracteres
            </p>
            <p v-if="errors.comment" class="mt-1 text-sm text-red-600">
              {{ errors.comment }}
            </p>
          </div>

          <!-- Error Message -->
          <div v-if="errors.general" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-800">{{ errors.general }}</p>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeModal"
              :disabled="submitting"
              class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="submitting || !isFormValid"
              class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
            >
              <i v-if="submitting" class="pi pi-spinner pi-spin text-xl"></i>
              <span>{{ submitting ? 'Enviando...' : (isEditing ? 'Actualizar' : 'Publicar') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import StarRating from './StarRating.vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  review: {
    type: Object,
    default: null
  },
  product: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

// Form state
const form = ref({
  rating: 0,
  title: '',
  comment: ''
})

const errors = ref({
  rating: '',
  comment: '',
  general: ''
})

const submitting = ref(false)

// Computed
const isEditing = computed(() => !!props.review)

const isFormValid = computed(() => {
  return form.value.rating > 0 && form.value.comment.trim().length > 0
})

// Watch for review changes (when editing)
watch(() => props.review, (newReview) => {
  if (newReview) {
    form.value = {
      rating: newReview.rating || 0,
      title: newReview.title || '',
      comment: newReview.comment || ''
    }
  } else {
    resetForm()
  }
}, { immediate: true })

// Methods
const closeModal = () => {
  if (!submitting.value) {
    emit('update:modelValue', false)
    setTimeout(resetForm, 300) // Reset after modal animation
  }
}

const resetForm = () => {
  form.value = {
    rating: 0,
    title: '',
    comment: ''
  }
  errors.value = {
    rating: '',
    comment: '',
    general: ''
  }
}

const validateForm = () => {
  errors.value = {
    rating: '',
    comment: '',
    general: ''
  }

  let isValid = true

  if (form.value.rating === 0) {
    errors.value.rating = 'Por favor selecciona una calificación'
    isValid = false
  }

  if (!form.value.comment.trim()) {
    errors.value.comment = 'El comentario es requerido'
    isValid = false
  } else if (form.value.comment.length > 1000) {
    errors.value.comment = 'El comentario no puede exceder 1000 caracteres'
    isValid = false
  }

  return isValid
}

const submitReview = async () => {
  if (!validateForm()) {
    return
  }

  submitting.value = true
  errors.value.general = ''

  try {
    await emit('submit', {
      rating: form.value.rating,
      title: form.value.title.trim() || null,
      comment: form.value.comment.trim()
    })
    closeModal()
  } catch (error) {
    errors.value.general = error.message || 'Error al enviar la reseña. Por favor intenta de nuevo.'
  } finally {
    submitting.value = false
  }
}
</script>
