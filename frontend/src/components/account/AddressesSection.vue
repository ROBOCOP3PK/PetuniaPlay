<template>
  <div>
    <!-- Add Address Button -->
    <div class="mb-6">
      <button @click="openAddressModal()" class="btn-primary">
        ➕ Agregar Nueva Dirección
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-600 dark:text-gray-400">Cargando direcciones...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="addresses.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">📍</div>
      <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">No tienes direcciones guardadas</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Agrega una dirección de envío para agilizar tus compras
      </p>
    </div>

    <!-- Addresses Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="address in addresses"
        :key="address.id"
        class="border rounded-lg p-6 relative bg-white dark:bg-gray-800 dark:border-gray-700"
        :class="{ 'border-primary border-2 dark:border-primary': address.is_default }"
      >
        <!-- Default Badge -->
        <span
          v-if="address.is_default"
          class="absolute top-3 right-3 bg-primary text-white text-xs px-2 py-1 rounded"
        >
          Predeterminada
        </span>

        <!-- Address Info -->
        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">{{ address.full_name }}</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ address.phone }}</p>
        <p class="text-gray-800 dark:text-gray-200">{{ address.address_line_1 }}</p>
        <p v-if="address.address_line_2" class="text-gray-800 dark:text-gray-200">{{ address.address_line_2 }}</p>
        <p class="text-gray-800 dark:text-gray-200">
          {{ address.city }}, {{ address.state }} {{ address.postal_code }}
        </p>
        <p class="text-gray-600 dark:text-gray-400">{{ address.country }}</p>

        <!-- Actions -->
        <div class="mt-4 flex gap-2">
          <button
            v-if="!address.is_default"
            @click="setDefaultAddress(address.id)"
            class="btn-outline flex-1 text-sm"
          >
            Marcar como Predeterminada
          </button>
          <button
            @click="openAddressModal(address)"
            class="btn-secondary flex-1 text-sm"
          >
            Editar
          </button>
          <button
            @click="deleteAddress(address.id)"
            class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 dark:hover:bg-opacity-20 px-3 py-2 rounded transition"
          >
            🗑️
          </button>
        </div>
      </div>
    </div>

    <!-- Address Modal -->
    <div
      v-if="showModal"
      @click="closeModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div
        @click.stop
        class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8"
      >
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
          {{ editingAddress ? 'Editar Dirección' : 'Nueva Dirección' }}
        </h2>

        <form @submit.prevent="saveAddress" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Nombre Completo *</label>
              <input
                v-model="addressForm.full_name"
                type="text"
                required
                class="input-field"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Teléfono *</label>
              <input
                v-model="addressForm.phone"
                type="tel"
                required
                class="input-field"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Dirección Línea 1 *</label>
            <input
              v-model="addressForm.address_line_1"
              type="text"
              required
              class="input-field"
              placeholder="Calle 123 #45-67"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Dirección Línea 2 (Opcional)</label>
            <input
              v-model="addressForm.address_line_2"
              type="text"
              class="input-field"
              placeholder="Apartamento, oficina, etc."
            />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Ciudad *</label>
              <input
                v-model="addressForm.city"
                type="text"
                required
                class="input-field"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Departamento *</label>
              <input
                v-model="addressForm.state"
                type="text"
                required
                class="input-field"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">Código Postal</label>
              <input
                v-model="addressForm.postal_code"
                type="text"
                class="input-field"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">País</label>
            <input
              v-model="addressForm.country"
              type="text"
              class="input-field"
            />
          </div>

          <div>
            <label class="flex items-center">
              <input
                v-model="addressForm.is_default"
                type="checkbox"
                class="mr-2"
              />
              <span class="text-sm text-gray-700 dark:text-gray-200">Marcar como dirección predeterminada</span>
            </label>
          </div>

          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="btn-outline flex-1"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="loadingSave"
              class="btn-primary flex-1"
              :class="{ 'opacity-50 cursor-not-allowed': loadingSave }"
            >
              {{ loadingSave ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import addressService from '../../services/addressService'
import { useToast } from 'vue-toastification'

const toast = useToast()
const loading = ref(false)
const loadingSave = ref(false)
const addresses = ref([])
const showModal = ref(false)
const editingAddress = ref(null)

const addressForm = reactive({
  full_name: '',
  phone: '',
  address_line_1: '',
  address_line_2: '',
  city: '',
  state: '',
  postal_code: '',
  country: 'Colombia',
  is_default: false
})

const resetForm = () => {
  addressForm.full_name = ''
  addressForm.phone = ''
  addressForm.address_line_1 = ''
  addressForm.address_line_2 = ''
  addressForm.city = ''
  addressForm.state = ''
  addressForm.postal_code = ''
  addressForm.country = 'Colombia'
  addressForm.is_default = false
}

const loadAddresses = async () => {
  loading.value = true
  try {
    const response = await addressService.getAll()
    addresses.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading addresses:', error)
    toast.error('Error al cargar las direcciones')
  } finally {
    loading.value = false
  }
}

const openAddressModal = (address = null) => {
  editingAddress.value = address
  if (address) {
    // Cargar datos de la dirección
    Object.assign(addressForm, address)
  } else {
    resetForm()
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingAddress.value = null
  resetForm()
}

const saveAddress = async () => {
  loadingSave.value = true
  try {
    if (editingAddress.value) {
      // Actualizar dirección existente
      await addressService.update(editingAddress.value.id, addressForm)
      toast.success('Dirección actualizada exitosamente')
    } else {
      // Crear nueva dirección
      await addressService.create(addressForm)
      toast.success('Dirección creada exitosamente')
    }
    await loadAddresses()
    closeModal()
  } catch (error) {
    console.error('Error saving address:', error)
    toast.error('Error al guardar la dirección')
  } finally {
    loadingSave.value = false
  }
}

const setDefaultAddress = async (addressId) => {
  try {
    await addressService.setDefault(addressId)
    toast.success('Dirección predeterminada actualizada')
    await loadAddresses()
  } catch (error) {
    toast.error('Error al actualizar dirección predeterminada')
  }
}

const deleteAddress = async (addressId) => {
  if (!confirm('¿Estás seguro de eliminar esta dirección?')) return

  try {
    await addressService.delete(addressId)
    toast.success('Dirección eliminada exitosamente')
    await loadAddresses()
  } catch (error) {
    toast.error('Error al eliminar la dirección')
  }
}

onMounted(() => {
  loadAddresses()
})
</script>
