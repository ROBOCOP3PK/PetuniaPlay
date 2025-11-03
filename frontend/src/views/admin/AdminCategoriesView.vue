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
        <h1 class="text-3xl font-bold text-dark dark:text-white">Gestión de Categorías</h1>
        <button
          @click="openCreateModal"
          class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition"
        >
          + Nueva Categoría
        </button>
      </div>

      <!-- Search and Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por nombre..."
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
        <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando categorías...</p>
      </div>

      <!-- Categories Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Sección</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Padre</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Orden</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Subcategorías</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="category in filteredCategories"
                :key="category.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <img
                      v-if="category.image"
                      :src="category.image"
                      :alt="category.name"
                      class="w-12 h-12 object-cover rounded"
                    />
                    <div class="w-12 h-12 bg-primary bg-opacity-10 rounded flex items-center justify-center" v-else>
                      <span class="text-primary font-bold text-lg">{{ category.name.charAt(0) }}</span>
                    </div>
                    <div>
                      <p class="font-semibold text-dark dark:text-white">{{ category.name }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ truncate(category.description, 40) || 'Sin descripción' }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div v-if="category.animal_section" class="flex items-center space-x-2">
                    <span class="text-2xl">{{ category.animal_section.icon }}</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ category.animal_section.name }}</span>
                  </div>
                  <span v-else class="text-sm text-gray-400 dark:text-gray-500 italic">Sin sección</span>
                </td>
                <td class="px-6 py-4">
                  <code class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded">{{ category.slug }}</code>
                </td>
                <td class="px-6 py-4">
                  <span v-if="category.parent" class="text-sm text-gray-700 dark:text-gray-300">{{ category.parent.name }}</span>
                  <span v-else class="text-sm text-gray-400 dark:text-gray-500 italic">Raíz</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="font-semibold text-gray-700 dark:text-gray-300">{{ category.order }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="text-sm font-semibold text-primary">
                    {{ category.children_count || 0 }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <button
                    @click="toggleStatus(category)"
                    class="px-3 py-1 text-xs rounded-full font-semibold transition"
                    :class="category.is_active
                      ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    "
                  >
                    {{ category.is_active ? 'Activa' : 'Inactiva' }}
                  </button>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end space-x-2">
                    <button
                      @click="editCategory(category)"
                      class="text-blue-600 hover:text-blue-700 p-2"
                      title="Editar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteCategory(category)"
                      class="text-red-600 hover:text-red-700 p-2"
                      title="Eliminar"
                      :disabled="category.children_count > 0"
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
        <div v-if="filteredCategories.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400">No se encontraron categorías</p>
        </div>
      </div>

      <!-- Category Modal (Create/Edit) -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 p-4"
        @click.self="closeModal"
      >
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ editingCategoryData ? 'Editar Categoría' : 'Nueva Categoría' }}
              </h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <form @submit.prevent="saveCategoryForm" class="p-6 space-y-6">
            <!-- Basic Information -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Información Básica</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                  <input
                    v-model="categoryForm.name"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Slug</label>
                  <input
                    v-model="categoryForm.slug"
                    type="text"
                    placeholder="Se genera automáticamente si se deja vacío"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">URL amigable (ej: juguetes-perros)</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                  <textarea
                    v-model="categoryForm.description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Imagen de la Categoría</label>
                  <ImageUpload
                    v-model="categoryForm.imageArray"
                    :multiple="false"
                    type="category"
                  />
                </div>
              </div>
            </div>

            <!-- Animal Section -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Sección de Animal</h3>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sección *</label>
                <Dropdown
                  v-model="categoryForm.animal_section_id"
                  :options="animalSections"
                  optionLabel="name"
                  optionValue="id"
                  placeholder="Selecciona una sección"
                  class="w-full"
                  :class="{'p-invalid': !categoryForm.animal_section_id}"
                >
                  <template #value="slotProps">
                    <div v-if="slotProps.value" class="flex items-center space-x-2">
                      <span class="text-xl">{{ getSelectedSectionIcon(slotProps.value) }}</span>
                      <span>{{ getSelectedSectionName(slotProps.value) }}</span>
                    </div>
                    <span v-else>{{ slotProps.placeholder }}</span>
                  </template>
                  <template #option="slotProps">
                    <div class="flex items-center space-x-2">
                      <span class="text-xl">{{ slotProps.option.icon }}</span>
                      <span>{{ slotProps.option.name }}</span>
                    </div>
                  </template>
                </Dropdown>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selecciona la sección de animal para esta categoría</p>
              </div>
            </div>

            <!-- Hierarchy & Order -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Jerarquía y Orden</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría Padre</label>
                  <select
                    v-model="categoryForm.parent_id"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  >
                    <option :value="null">Ninguna (Categoría Raíz)</option>
                    <option
                      v-for="cat in parentCategoriesOptions"
                      :key="cat.id"
                      :value="cat.id"
                    >
                      {{ cat.name }}
                    </option>
                  </select>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Deja en blanco para categoría principal</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Orden</label>
                  <input
                    v-model.number="categoryForm.order"
                    type="number"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menor número = mayor prioridad</p>
                </div>
              </div>
            </div>

            <!-- Status -->
            <div>
              <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white">Estado</h3>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  v-model="categoryForm.is_active"
                  type="checkbox"
                  class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary"
                />
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Categoría activa</span>
              </label>
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
                :disabled="savingCategory"
                class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition disabled:opacity-50"
              >
                {{ savingCategory ? 'Guardando...' : (editingCategoryData ? 'Actualizar' : 'Crear Categoría') }}
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
import ImageUpload from '../../components/admin/ImageUpload.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import Dropdown from 'primevue/dropdown'
import { useCategoryStore } from '../../stores/categoryStore'
import { useNotification } from '@/composables/useNotification'
import { useConfirm } from '@/composables/useConfirm'
import { adminService } from '../../services/adminService'
import { animalSectionService } from '../../services/animalSectionService'

const categoryStore = useCategoryStore()
const { notifySuccess, notifyError, notifyWarning } = useNotification()
const { confirmDialog, showConfirm } = useConfirm()

const loading = ref(false)
const search = ref('')
const filterStatus = ref('')

// Animal sections
const animalSections = ref([])

// Modal state
const showModal = ref(false)
const editingCategoryData = ref(null)
const savingCategory = ref(false)

// Category form
const emptyForm = {
  name: '',
  slug: '',
  description: '',
  image: '',
  imageArray: [],
  animal_section_id: null,
  parent_id: null,
  order: 0,
  is_active: true
}

const categoryForm = ref({ ...emptyForm })

const filteredCategories = computed(() => {
  let filtered = categoryStore.categories

  // Search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(c =>
      c.name.toLowerCase().includes(searchLower) ||
      c.slug.toLowerCase().includes(searchLower)
    )
  }

  // Status filter
  if (filterStatus.value === 'active') {
    filtered = filtered.filter(c => c.is_active)
  } else if (filterStatus.value === 'inactive') {
    filtered = filtered.filter(c => !c.is_active)
  }

  return filtered
})

const parentCategoriesOptions = computed(() => {
  // Excluir la categoría actual al editar para evitar ciclos
  if (editingCategoryData.value) {
    return categoryStore.categories.filter(c =>
      c.id !== editingCategoryData.value.id && !c.parent_id
    )
  }
  return categoryStore.parentCategories
})

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

// Helper functions for animal sections
const getSelectedSectionIcon = (sectionId) => {
  const section = animalSections.value.find(s => s.id === sectionId)
  return section ? section.icon : ''
}

const getSelectedSectionName = (sectionId) => {
  const section = animalSections.value.find(s => s.id === sectionId)
  return section ? section.name : ''
}

// Modal functions
const openCreateModal = () => {
  editingCategoryData.value = null
  categoryForm.value = { ...emptyForm }
  showModal.value = true
}

const editCategory = (category) => {
  editingCategoryData.value = category
  categoryForm.value = {
    name: category.name,
    slug: category.slug || '',
    description: category.description || '',
    image: category.image || '',
    imageArray: category.image ? [category.image] : [],
    animal_section_id: category.animal_section_id || null,
    parent_id: category.parent_id || null,
    order: category.order || 0,
    is_active: category.is_active
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingCategoryData.value = null
  categoryForm.value = { ...emptyForm }
}

const saveCategoryForm = async () => {
  // Validar que se haya seleccionado una sección
  if (!categoryForm.value.animal_section_id) {
    notifyError('Debes seleccionar una sección de animal')
    return
  }

  savingCategory.value = true
  try {
    // Convertir imageArray a image (tomar la primera URL del array)
    const imageUrl = categoryForm.value.imageArray && categoryForm.value.imageArray.length > 0
      ? categoryForm.value.imageArray[0]
      : null

    const categoryData = {
      name: categoryForm.value.name,
      slug: categoryForm.value.slug || null,
      description: categoryForm.value.description || null,
      image: imageUrl,
      animal_section_id: categoryForm.value.animal_section_id,
      parent_id: categoryForm.value.parent_id || null,
      order: categoryForm.value.order,
      is_active: categoryForm.value.is_active
    }

    if (editingCategoryData.value) {
      // Update existing category
      await adminService.updateCategory(editingCategoryData.value.id, categoryData)
      notifySuccess('Categoría actualizada exitosamente')
    } else {
      // Create new category
      await adminService.createCategory(categoryData)
      notifySuccess('Categoría creada exitosamente')
    }

    closeModal()
    await categoryStore.fetchCategories()
  } catch (error) {
    const errorMsg = error.response?.data?.message || error.response?.data?.errors
    if (typeof errorMsg === 'object') {
      const firstError = Object.values(errorMsg)[0]
      notifyError(Array.isArray(firstError) ? firstError[0] : firstError)
    } else {
      notifyError(errorMsg || 'Error al guardar categoría')
    }
  } finally {
    savingCategory.value = false
  }
}

const toggleStatus = async (category) => {
  try {
    const updatedData = { is_active: !category.is_active }
    await adminService.updateCategory(category.id, updatedData)
    category.is_active = !category.is_active
    notifySuccess(`Categoría ${category.is_active ? 'activada' : 'desactivada'}`)
  } catch (error) {
    notifyError(error.response?.data?.message || 'Error al cambiar estado')
  }
}

const deleteCategory = async (category) => {
  if (category.children_count > 0) {
    notifyWarning('No puedes eliminar una categoría con subcategorías')
    return
  }

  const confirmed = await showConfirm({
    title: '¿Eliminar categoría?',
    message: `¿Estás seguro de que deseas eliminar la categoría "${category.name}"?`,
    type: 'danger',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    try {
      await adminService.deleteCategory(category.id)
      await categoryStore.fetchCategories(true)
      notifySuccess('Categoría eliminada exitosamente')
    } catch (error) {
      notifyError(error.response?.data?.message || 'Error al eliminar categoría')
    }
  }
}

const loadCategories = async () => {
  loading.value = true
  try {
    await categoryStore.fetchCategories()
  } catch (error) {
    notifyError('Error al cargar categorías')
  } finally {
    loading.value = false
  }
}

const loadAnimalSections = async () => {
  try {
    const response = await animalSectionService.getAll()
    animalSections.value = response.data.data || response.data || []
  } catch (error) {
    notifyError('Error al cargar secciones de animales')
    animalSections.value = []
  }
}

onMounted(() => {
  loadCategories()
  loadAnimalSections()
})
</script>
