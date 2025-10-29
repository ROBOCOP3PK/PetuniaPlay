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
          <h1 class="text-3xl font-bold text-gray-900">Preguntas de Productos</h1>
          <p class="text-gray-600 mt-1">Gestiona las preguntas de los clientes sobre productos</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div v-if="stats" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Preguntas</p>
              <p class="text-3xl font-bold text-gray-900">{{ stats.total }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Pendientes</p>
              <p class="text-3xl font-bold text-orange-600">{{ stats.pending }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Respondidas</p>
              <p class="text-3xl font-bold text-green-600">{{ stats.answered }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <select
            v-model="filterStatus"
            @change="loadQuestions"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          >
            <option value="">Todas las preguntas</option>
            <option value="pending">Pendientes</option>
            <option value="answered">Respondidas</option>
          </select>
          <button
            @click="loadQuestions"
            class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors"
          >
            Actualizar
          </button>
        </div>
      </div>

      <!-- Questions List -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>

        <div v-else-if="questions.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="mt-2 text-gray-600">No se encontraron preguntas</p>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <div
            v-for="question in questions"
            :key="question.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- Question Header -->
            <div class="p-6 cursor-pointer" @click="toggleExpand(question.id)">
              <div class="flex items-start gap-4">
                <!-- Product Image -->
                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                  <img
                    v-if="question.product?.images?.[0]"
                    :src="question.product.images[0].image_url"
                    :alt="question.product.name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>

                <!-- Question Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900 mb-1">
                        {{ question.product?.name }}
                      </h3>
                      <p class="text-gray-700 line-clamp-2">{{ question.question }}</p>
                      <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>Por {{ question.user?.name }}</span>
                        <span>•</span>
                        <span>{{ formatDate(question.created_at) }}</span>
                      </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex-shrink-0">
                      <span
                        v-if="question.is_public"
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                      >
                        Respondida
                      </span>
                      <span
                        v-else
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800"
                      >
                        Pendiente
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Expand Icon -->
                <div class="flex-shrink-0">
                  <svg
                    class="w-6 h-6 text-gray-400 transition-transform"
                    :class="{ 'transform rotate-180': expandedQuestions.includes(question.id) }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Expanded Details -->
            <div
              v-if="expandedQuestions.includes(question.id)"
              class="px-6 pb-6 bg-gray-50 border-t"
            >
              <!-- Full Question -->
              <div class="mb-4">
                <h4 class="font-semibold text-gray-900 mb-2">Pregunta completa:</h4>
                <p class="text-gray-700">{{ question.question }}</p>
              </div>

              <!-- Answer Section -->
              <div v-if="question.answer" class="mb-4 bg-white p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-2">Respuesta:</h4>
                <p class="text-gray-700 mb-2">{{ question.answer }}</p>
                <p class="text-sm text-gray-500">
                  Respondido por {{ question.answered_by?.name }} el {{ formatDate(question.answered_at) }}
                </p>
              </div>

              <!-- Answer Form -->
              <div v-else class="mb-4">
                <h4 class="font-semibold text-gray-900 mb-2">Responder:</h4>
                <textarea
                  v-model="answerText[question.id]"
                  rows="3"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                  placeholder="Escribe tu respuesta aquí (mínimo 10 caracteres)"
                ></textarea>
                <p class="text-sm text-gray-500 mt-1">
                  {{ (answerText[question.id] || '').length }} / 1000 caracteres
                </p>
              </div>

              <!-- Actions -->
              <div class="flex justify-end gap-3">
                <button
                  v-if="!question.answer"
                  @click="submitAnswer(question)"
                  :disabled="!answerText[question.id] || answerText[question.id].length < 10"
                  class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Enviar Respuesta
                </button>
                <button
                  @click="confirmDelete(question)"
                  class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
                >
                  Eliminar Pregunta
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="bg-gray-50 px-6 py-4 border-t flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Mostrando {{ pagination.from }} - {{ pagination.to }} de {{ pagination.total }} preguntas
          </div>
          <div class="flex gap-2">
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Anterior
            </button>
            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Siguiente
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import AdminLayout from '../../layouts/AdminLayout.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import productQuestionService from '../../services/productQuestionService'

const toast = useToast()

// Confirm Dialog State
const confirmDialog = ref({
  isOpen: false,
  title: '',
  message: '',
  type: 'danger',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  onConfirm: () => {},
  onCancel: () => {}
})

// State
const questions = ref([])
const stats = ref(null)
const loading = ref(false)
const filterStatus = ref('')
const pagination = ref(null)
const expandedQuestions = ref([])
const answerText = ref({})

// Load questions
const loadQuestions = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: 10
    }

    if (filterStatus.value) {
      params.status = filterStatus.value
    }

    const response = await productQuestionService.adminGetAll(params)
    questions.value = response.data.data
    pagination.value = response.data.meta
  } catch (error) {
    console.error('Error loading questions:', error)
    toast.error('Error al cargar las preguntas')
  } finally {
    loading.value = false
  }
}

// Load stats
const loadStats = async () => {
  try {
    const response = await productQuestionService.getStats()
    stats.value = response.data.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

// Toggle expand question
const toggleExpand = (questionId) => {
  const index = expandedQuestions.value.indexOf(questionId)
  if (index > -1) {
    expandedQuestions.value.splice(index, 1)
  } else {
    expandedQuestions.value.push(questionId)
  }
}

// Submit answer
const submitAnswer = async (question) => {
  const answer = answerText.value[question.id]

  if (!answer || answer.trim().length < 10) {
    toast.error('La respuesta debe tener al menos 10 caracteres')
    return
  }

  try {
    await productQuestionService.answer(question.id, { answer })
    toast.success('Pregunta respondida exitosamente')

    // Reload questions and stats
    await loadQuestions(pagination.value?.current_page || 1)
    await loadStats()

    // Clear answer text
    delete answerText.value[question.id]
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'Error al responder la pregunta'
    toast.error(errorMessage)
  }
}

// Confirm delete
const confirmDelete = (question) => {
  confirmDialog.value = {
    isOpen: true,
    title: '¿Eliminar pregunta?',
    message: '¿Estás seguro de que deseas eliminar esta pregunta? Esta acción no se puede deshacer.',
    type: 'danger',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar',
    onConfirm: async () => {
      try {
        await productQuestionService.delete(question.id)
        toast.success('Pregunta eliminada exitosamente')

        // Reload questions and stats
        await loadQuestions(pagination.value?.current_page || 1)
        await loadStats()
      } catch (error) {
        toast.error('Error al eliminar la pregunta')
      }
    },
    onCancel: () => {}
  }
}

// Change page
const changePage = (page) => {
  loadQuestions(page)
}

// Format date
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Load initial data
onMounted(() => {
  loadQuestions()
  loadStats()
})
</script>
