<template>
  <div>
    <!-- Add Pet Button -->
    <div class="mb-6">
      <button @click="openPetModal()" class="btn-primary flex items-center gap-2 justify-center">
        <i class="pi pi-plus"></i>
        <span>Agregar Mascota</span>
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <i class="pi pi-spin pi-spinner text-4xl text-primary"></i>
      <p class="text-gray-600 dark:text-gray-400 mt-2">Cargando mascotas...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="pets.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">🐾</div>
      <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">No tienes mascotas registradas</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Registra a tus mascotas para personalizar tu experiencia
      </p>
    </div>

    <!-- Pets Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="pet in pets"
        :key="pet.id"
        class="border rounded-lg p-6 bg-white dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow"
      >
        <!-- Pet Photo/Icon -->
        <div class="flex items-center gap-4 mb-4">
          <div class="relative">
            <div
              v-if="pet.photo"
              class="w-20 h-20 rounded-full overflow-hidden border-4 border-fuchsia-200 dark:border-fuchsia-800"
            >
              <img
                :src="pet.photo"
                :alt="pet.name"
                class="w-full h-full object-cover"
              />
            </div>
            <div
              v-else
              class="w-20 h-20 rounded-full bg-fuchsia-100 dark:bg-fuchsia-900/30 flex items-center justify-center text-4xl border-4 border-fuchsia-200 dark:border-fuchsia-800"
            >
              {{ pet.animal_section?.icon || '🐕' }}
            </div>
            <!-- Category Badge -->
            <span
              v-if="pet.animal_section"
              class="absolute -bottom-1 -right-1 bg-fuchsia-600 text-white text-xs px-2 py-0.5 rounded-full"
            >
              {{ pet.animal_section.name }}
            </span>
          </div>
          <div class="flex-1">
            <h3 class="font-bold text-xl text-gray-900 dark:text-white">{{ pet.name }}</h3>
            <p v-if="pet.age" class="text-sm text-gray-600 dark:text-gray-400">
              <i class="pi pi-calendar mr-1"></i>{{ pet.age }}
            </p>
            <p v-if="pet.weight" class="text-sm text-gray-600 dark:text-gray-400">
              <i class="pi pi-chart-line mr-1"></i>{{ pet.weight }} kg
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 mt-4 pt-4 border-t dark:border-gray-700">
          <button
            @click="openPetModal(pet)"
            class="btn-secondary flex-1 text-sm flex items-center justify-center gap-1"
          >
            <i class="pi pi-pencil"></i>
            Editar
          </button>
          <button
            @click="confirmDeletePet(pet)"
            class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-2 rounded transition"
            title="Eliminar mascota"
          >
            <i class="pi pi-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Pet Modal -->
    <div
      v-if="showModal"
      @click="closeModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div
        @click.stop
        class="bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto p-6"
      >
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ editingPet ? 'Editar Mascota' : 'Nueva Mascota' }}
          </h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <i class="pi pi-times text-xl"></i>
          </button>
        </div>

        <form @submit.prevent="savePet" class="space-y-5">
          <!-- Photo Preview -->
          <div class="text-center">
            <div class="relative inline-block">
              <div
                v-if="petForm.photo || photoPreview"
                class="w-24 h-24 rounded-full overflow-hidden border-4 border-fuchsia-200 dark:border-fuchsia-800 mx-auto"
              >
                <img
                  :src="photoPreview || petForm.photo"
                  alt="Foto de mascota"
                  class="w-full h-full object-cover"
                />
              </div>
              <div
                v-else
                class="w-24 h-24 rounded-full bg-fuchsia-100 dark:bg-fuchsia-900/30 flex items-center justify-center text-5xl border-4 border-fuchsia-200 dark:border-fuchsia-800 mx-auto"
              >
                {{ selectedCategoryIcon }}
              </div>
              <!-- Upload Button -->
              <label class="absolute bottom-0 right-0 bg-fuchsia-600 text-white p-2 rounded-full cursor-pointer hover:bg-fuchsia-700 transition">
                <i class="pi pi-camera"></i>
                <input
                  type="file"
                  accept="image/*"
                  @change="handlePhotoSelect"
                  class="hidden"
                />
              </label>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
              Haz clic en el ícono de cámara para subir una foto
            </p>
          </div>

          <!-- Name -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
              Nombre de la mascota *
            </label>
            <input
              v-model="petForm.name"
              type="text"
              required
              maxlength="100"
              class="input-field"
              placeholder="Ej: Max, Luna, Firulais..."
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
              Tipo de mascota
            </label>
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
              <button
                v-for="category in categories"
                :key="category.id"
                type="button"
                @click="petForm.animal_section_id = category.id"
                class="p-3 rounded-lg border-2 transition-all text-center"
                :class="petForm.animal_section_id === category.id
                  ? 'border-fuchsia-500 bg-fuchsia-50 dark:bg-fuchsia-900/30'
                  : 'border-gray-200 dark:border-gray-700 hover:border-fuchsia-300'"
              >
                <span class="text-2xl block mb-1">{{ category.icon }}</span>
                <span class="text-xs text-gray-700 dark:text-gray-300">{{ category.name }}</span>
              </button>
            </div>
          </div>

          <!-- Birth Date -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
              Fecha de nacimiento
            </label>
            <input
              v-model="petForm.birth_date"
              type="date"
              :max="today"
              class="input-field"
            />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
              Opcional - para calcular la edad
            </p>
          </div>

          <!-- Weight -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
              Peso (kg)
            </label>
            <input
              v-model="petForm.weight"
              type="number"
              step="0.01"
              min="0"
              max="999.99"
              class="input-field"
              placeholder="Ej: 5.5"
            />
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="btn-secondary flex-1"
              :disabled="saving"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="btn-primary flex-1 flex items-center justify-center gap-2"
              :disabled="saving || !petForm.name"
            >
              <i v-if="saving" class="pi pi-spin pi-spinner"></i>
              {{ saving ? 'Guardando...' : (editingPet ? 'Actualizar' : 'Guardar') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      @click="showDeleteModal = false"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div
        @click.stop
        class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6"
      >
        <div class="text-center">
          <div class="text-6xl mb-4">😢</div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            ¿Eliminar a {{ petToDelete?.name }}?
          </h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Esta acción no se puede deshacer.
          </p>
          <div class="flex gap-3">
            <button
              @click="showDeleteModal = false"
              class="btn-secondary flex-1"
            >
              Cancelar
            </button>
            <button
              @click="deletePet"
              class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition"
              :disabled="deleting"
            >
              {{ deleting ? 'Eliminando...' : 'Sí, eliminar' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import petService from '../../services/petService'

const toast = useToast()

// State
const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const pets = ref([])
const categories = ref([])
const showModal = ref(false)
const showDeleteModal = ref(false)
const editingPet = ref(null)
const petToDelete = ref(null)
const photoFile = ref(null)
const photoPreview = ref(null)

const petForm = reactive({
  name: '',
  animal_section_id: null,
  birth_date: '',
  weight: '',
  photo: ''
})

// Computed
const today = computed(() => new Date().toISOString().split('T')[0])

const selectedCategoryIcon = computed(() => {
  if (petForm.animal_section_id) {
    const category = categories.value.find(c => c.id === petForm.animal_section_id)
    return category?.icon || '🐕'
  }
  return '🐕'
})

// Methods
const loadPets = async () => {
  loading.value = true
  try {
    const response = await petService.getAll()
    pets.value = response.data.data
  } catch (error) {
    console.error('Error loading pets:', error)
    toast.error('Error al cargar las mascotas')
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await petService.getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Error loading categories:', error)
  }
}

const openPetModal = (pet = null) => {
  editingPet.value = pet
  photoFile.value = null
  photoPreview.value = null

  if (pet) {
    petForm.name = pet.name
    petForm.animal_section_id = pet.animal_section_id
    petForm.birth_date = pet.birth_date ? pet.birth_date.split('T')[0] : ''
    petForm.weight = pet.weight || ''
    petForm.photo = pet.photo || ''
  } else {
    petForm.name = ''
    petForm.animal_section_id = null
    petForm.birth_date = ''
    petForm.weight = ''
    petForm.photo = ''
  }

  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingPet.value = null
  photoFile.value = null
  photoPreview.value = null
}

const handlePhotoSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2 * 1024 * 1024) {
      toast.error('La imagen no debe superar 2MB')
      return
    }
    photoFile.value = file
    photoPreview.value = URL.createObjectURL(file)
  }
}

const savePet = async () => {
  if (!petForm.name.trim()) {
    toast.warning('El nombre es obligatorio')
    return
  }

  saving.value = true

  try {
    const data = {
      name: petForm.name.trim(),
      animal_section_id: petForm.animal_section_id,
      birth_date: petForm.birth_date || null,
      weight: petForm.weight || null
    }

    let savedPet

    if (editingPet.value) {
      const response = await petService.update(editingPet.value.id, data)
      savedPet = response.data.data
      toast.success('Mascota actualizada')
    } else {
      const response = await petService.create(data)
      savedPet = response.data.data
      toast.success('Mascota registrada')
    }

    // Si hay foto nueva, subirla
    if (photoFile.value) {
      try {
        await petService.uploadPhoto(savedPet.id, photoFile.value)
      } catch (error) {
        console.error('Error uploading photo:', error)
        toast.warning('Mascota guardada, pero hubo un error al subir la foto')
      }
    }

    await loadPets()
    closeModal()
  } catch (error) {
    console.error('Error saving pet:', error)
    const message = error.response?.data?.message || 'Error al guardar la mascota'
    toast.error(message)
  } finally {
    saving.value = false
  }
}

const confirmDeletePet = (pet) => {
  petToDelete.value = pet
  showDeleteModal.value = true
}

const deletePet = async () => {
  if (!petToDelete.value) return

  deleting.value = true

  try {
    await petService.delete(petToDelete.value.id)
    toast.success(`${petToDelete.value.name} ha sido eliminado`)
    await loadPets()
    showDeleteModal.value = false
    petToDelete.value = null
  } catch (error) {
    console.error('Error deleting pet:', error)
    toast.error('Error al eliminar la mascota')
  } finally {
    deleting.value = false
  }
}

onMounted(() => {
  loadCategories()
  loadPets()
})
</script>
