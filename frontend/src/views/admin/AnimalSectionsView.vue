<template>
  <AdminLayout>
    <div>
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

      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-dark dark:text-white">Gestión de Secciones de Animales</h1>
        <button
          @click="openCreateModal"
          class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition"
        >
          + Nueva Sección
        </button>
      </div>

      <!-- Search and Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por nombre o slug..."
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
          />
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
          >
            <option value="">Todos los estados</option>
            <option value="active">Activas</option>
            <option value="inactive">Inactivas</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando secciones...</p>
      </div>

      <!-- Sections Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Icono</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Slug</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Orden</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="section in filteredSections"
                :key="section.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
              >
                <td class="px-6 py-4">
                  <span class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ section.id }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center w-12 h-12 bg-cream dark:bg-gray-700 rounded-lg">
                    <span class="text-3xl">{{ section.icon || '📦' }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-semibold text-dark dark:text-white">{{ section.name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ truncate(section.description, 40) || 'Sin descripción' }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <code class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded">{{ section.slug }}</code>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-accent bg-opacity-20 font-bold text-dark dark:text-white">
                    {{ section.order }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <button
                    @click="toggleStatus(section)"
                    class="px-3 py-1 text-xs rounded-full font-semibold transition"
                    :class="section.is_active
                      ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800'
                      : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800'
                    "
                  >
                    {{ section.is_active ? 'Activa' : 'Inactiva' }}
                  </button>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end space-x-2">
                    <button
                      @click="editSection(section)"
                      class="text-blue-600 hover:text-blue-700 p-2"
                      title="Editar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteSection(section)"
                      class="text-red-600 hover:text-red-700 p-2"
                      title="Eliminar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredSections.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400">No se encontraron secciones</p>
        </div>
      </div>

      <!-- Section Modal (Create/Edit) -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 p-4"
        @click.self="closeModal"
      >
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ editingSectionData ? 'Editar Sección' : 'Nueva Sección' }}
              </h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <form @submit.prevent="saveSectionForm" class="p-6 space-y-6">
            <!-- Basic Information -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Información Básica</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                  <input
                    v-model="sectionForm.name"
                    type="text"
                    required
                    @input="autoGenerateSlug"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    placeholder="Ej: Perros, Gatos, Aves"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Slug</label>
                  <input
                    v-model="sectionForm.slug"
                    type="text"
                    placeholder="Se genera automáticamente si se deja vacío"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">URL amigable (ej: perros, gatos, aves)</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Icono (Emoji)</label>
                  <div class="flex gap-3">
                    <input
                      v-model="sectionForm.icon"
                      type="text"
                      maxlength="2"
                      class="w-24 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-center text-3xl"
                      placeholder="🐶"
                    />
                    <div class="flex-1">
                      <div class="grid grid-cols-8 gap-2">
                        <button
                          v-for="emoji in suggestedEmojis"
                          :key="emoji"
                          type="button"
                          @click="sectionForm.icon = emoji"
                          class="w-10 h-10 flex items-center justify-center text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"
                        >
                          {{ emoji }}
                        </button>
                      </div>
                    </div>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selecciona un emoji o ingresa uno personalizado</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Imagen de Sección</label>

                  <!-- Vista previa de imagen actual -->
                  <div v-if="currentImageUrl" class="mb-3">
                    <div class="relative inline-block">
                      <img
                        :src="currentImageUrl"
                        alt="Imagen actual"
                        class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600"
                        @error="handleImageError"
                      />
                      <button
                        type="button"
                        @click="removeCurrentImage"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition"
                        title="Quitar imagen"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Imagen actual de la sección</p>
                  </div>

                  <!-- Input para nueva imagen -->
                  <div class="flex items-center gap-3">
                    <input
                      ref="imageInput"
                      type="file"
                      accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml"
                      @change="handleImageChange"
                      class="hidden"
                    />
                    <button
                      type="button"
                      @click="$refs.imageInput.click()"
                      class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >
                      <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      {{ selectedImageFile ? 'Cambiar imagen' : 'Seleccionar imagen' }}
                    </button>
                    <span v-if="selectedImageFile" class="text-sm text-gray-600 dark:text-gray-400">
                      {{ selectedImageFile.name }}
                    </span>
                  </div>

                  <!-- Vista previa de nueva imagen -->
                  <div v-if="imagePreview" class="mt-3">
                    <img :src="imagePreview" alt="Vista previa" class="w-32 h-32 object-cover rounded-lg border-2 border-primary" />
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Nueva imagen seleccionada</p>
                  </div>

                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Imagen grande para los cuadros de secciones (máx. 5MB)</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                  <textarea
                    v-model="sectionForm.description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    placeholder="Descripción de la sección de animales"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Order & Status -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Orden y Estado</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Orden</label>
                  <input
                    v-model.number="sectionForm.order"
                    type="number"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menor número = mayor prioridad en el listado</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                  <label class="flex items-center gap-3 cursor-pointer px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <input
                      v-model="sectionForm.is_active"
                      type="checkbox"
                      class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary"
                    />
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Sección activa</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
              <button
                type="button"
                @click="closeModal"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition text-gray-700 dark:text-gray-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="savingSection"
                class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-opacity-90 transition disabled:opacity-50"
              >
                {{ savingSection ? 'Guardando...' : (editingSectionData ? 'Actualizar' : 'Crear Sección') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import { useNotification } from '@/composables/useNotification'
import { useConfirm } from '@/composables/useConfirm'
import animalSectionService from '../../services/animalSectionService'

const { notifySuccess, notifyError, notifyWarning } = useNotification()
const { confirmDialog, showConfirm } = useConfirm()

const loading = ref(false)
const search = ref('')
const filterStatus = ref('')
const sections = ref([])

// Modal state
const showModal = ref(false)
const editingSectionData = ref(null)
const savingSection = ref(false)

// Suggested emojis for animal sections
const suggestedEmojis = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔']

// Section form
const emptyForm = {
  name: '',
  slug: '',
  description: '',
  icon: '',
  order: 0,
  is_active: true
}

const sectionForm = ref({ ...emptyForm })

// Image handling
const selectedImageFile = ref(null)
const imagePreview = ref(null)
const currentImageUrl = ref(null)
const imageInput = ref(null)

const filteredSections = computed(() => {
  let filtered = sections.value

  // Search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(s =>
      s.name.toLowerCase().includes(searchLower) ||
      s.slug.toLowerCase().includes(searchLower)
    )
  }

  // Status filter
  if (filterStatus.value === 'active') {
    filtered = filtered.filter(s => s.is_active)
  } else if (filterStatus.value === 'inactive') {
    filtered = filtered.filter(s => !s.is_active)
  }

  // Sort by order
  return filtered.sort((a, b) => a.order - b.order)
})

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

const slugify = (text) => {
  return text
    .toString()
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '') // Remove accents
    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
    .trim()
    .replace(/\s+/g, '-') // Replace spaces with -
    .replace(/-+/g, '-') // Replace multiple - with single -
}

const autoGenerateSlug = () => {
  if (!editingSectionData.value && sectionForm.value.name) {
    sectionForm.value.slug = slugify(sectionForm.value.name)
  }
}

// Image handling functions
const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      notifyError('La imagen no debe superar los 5MB')
      return
    }

    selectedImageFile.value = file

    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeCurrentImage = () => {
  currentImageUrl.value = null
  // If we're editing, we should mark for deletion (you might want to implement this)
}

const handleImageError = (event) => {
  console.error('Error al cargar la imagen:', currentImageUrl.value)
  notifyError('No se pudo cargar la imagen actual')
  currentImageUrl.value = null
}

// Modal functions
const openCreateModal = () => {
  editingSectionData.value = null
  sectionForm.value = { ...emptyForm }
  selectedImageFile.value = null
  imagePreview.value = null
  currentImageUrl.value = null
  showModal.value = true
}

const editSection = (section) => {
  editingSectionData.value = section
  sectionForm.value = {
    name: section.name,
    slug: section.slug || '',
    description: section.description || '',
    icon: section.icon || '',
    order: section.order || 0,
    is_active: section.is_active
  }

  // Load current image if exists
  if (section.image_url) {
    // Si la URL no es completa, agregar la base URL del backend
    const baseURL = import.meta.env.VITE_API_URL?.replace('/api/v1', '') || 'http://127.0.0.1:8000'
    currentImageUrl.value = section.image_url.startsWith('http')
      ? section.image_url
      : `${baseURL}${section.image_url}`
  } else {
    currentImageUrl.value = null
  }

  selectedImageFile.value = null
  imagePreview.value = null

  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingSectionData.value = null
  sectionForm.value = { ...emptyForm }
  selectedImageFile.value = null
  imagePreview.value = null
  currentImageUrl.value = null
}

const saveSectionForm = async () => {
  savingSection.value = true
  try {
    const sectionData = {
      name: sectionForm.value.name,
      slug: sectionForm.value.slug || null,
      description: sectionForm.value.description || null,
      icon: sectionForm.value.icon || null,
      order: sectionForm.value.order,
      is_active: sectionForm.value.is_active
    }

    let savedSection = null

    if (editingSectionData.value) {
      // Update existing section
      const response = await animalSectionService.update(editingSectionData.value.id, sectionData)
      savedSection = response.data.data || response.data
      notifySuccess('Sección actualizada exitosamente')
    } else {
      // Create new section
      const response = await animalSectionService.create(sectionData)
      savedSection = response.data.data || response.data
      notifySuccess('Sección creada exitosamente')
    }

    // Upload image if selected
    if (selectedImageFile.value && savedSection) {
      try {
        await animalSectionService.uploadImage(savedSection.id, selectedImageFile.value)
        notifySuccess('Imagen subida exitosamente')
      } catch (imageError) {
        notifyError('Error al subir imagen: ' + (imageError.response?.data?.message || imageError.message))
      }
    }

    closeModal()
    await loadSections()
  } catch (error) {
    const errorMsg = error.response?.data?.message || error.response?.data?.errors
    if (typeof errorMsg === 'object') {
      const firstError = Object.values(errorMsg)[0]
      notifyError(Array.isArray(firstError) ? firstError[0] : firstError)
    } else {
      notifyError(errorMsg || 'Error al guardar sección')
    }
  } finally {
    savingSection.value = false
  }
}

const toggleStatus = async (section) => {
  const confirmed = await showConfirm({
    title: `¿${section.is_active ? 'Desactivar' : 'Activar'} sección?`,
    message: `¿Estás seguro de que deseas ${section.is_active ? 'desactivar' : 'activar'} la sección "${section.name}"?`,
    type: 'warning',
    confirmText: 'Sí, continuar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    try {
      await animalSectionService.toggleStatus(section.id)
      section.is_active = !section.is_active
      notifySuccess(`Sección ${section.is_active ? 'activada' : 'desactivada'} exitosamente`)
    } catch (error) {
      notifyError(error.response?.data?.message || 'Error al cambiar estado')
    }
  }
}

const deleteSection = async (section) => {
  const confirmed = await showConfirm({
    title: '¿Eliminar sección?',
    message: `¿Estás seguro de que deseas eliminar la sección "${section.name}"? Esta acción no se puede deshacer.`,
    type: 'danger',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    try {
      await animalSectionService.delete(section.id)
      await loadSections()
      notifySuccess('Sección eliminada exitosamente')
    } catch (error) {
      const errorMsg = error.response?.data?.message || 'Error al eliminar sección'
      if (errorMsg.includes('categorías asociadas') || errorMsg.includes('cannot be deleted')) {
        notifyWarning('No puedes eliminar una sección que tiene categorías asociadas')
      } else {
        notifyError(errorMsg)
      }
    }
  }
}

const loadSections = async () => {
  loading.value = true
  try {
    const response = await animalSectionService.getAll()
    sections.value = response.data.data || response.data || []
  } catch (error) {
    notifyError('Error al cargar secciones')
    sections.value = []
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadSections()
})
</script>
