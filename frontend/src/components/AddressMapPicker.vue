<template>
  <div class="space-y-4">
    <!-- Manual Address Input -->
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-semibold mb-2">Dirección Completa *</label>
        <input
          v-model="manualAddress.address"
          @input="emitManualAddress"
          type="text"
          required
          class="input-field"
          placeholder="Calle 123 #45-67"
        />
      </div>
      <div>
        <label class="block text-sm font-semibold mb-2">Complemento (Opcional)</label>
        <input
          v-model="manualAddress.addressLine2"
          @input="emitManualAddress"
          type="text"
          class="input-field"
          placeholder="Apartamento, oficina, piso, etc."
        />
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2">Ciudad *</label>
          <input
            v-model="manualAddress.city"
            @input="emitManualAddress"
            type="text"
            required
            class="input-field"
            placeholder="Bogotá"
          />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Departamento *</label>
          <input
            v-model="manualAddress.state"
            @input="emitManualAddress"
            type="text"
            required
            class="input-field"
            placeholder="Cundinamarca"
          />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Código Postal</label>
          <input
            v-model="manualAddress.zipCode"
            @input="emitManualAddress"
            type="text"
            class="input-field"
            placeholder="110111"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  initialAddress: {
    type: Object,
    default: () => ({
      address: '',
      addressLine2: '',
      city: '',
      state: '',
      zipCode: ''
    })
  }
})

const emit = defineEmits(['update:address'])

// Address data
const manualAddress = ref({
  address: props.initialAddress.address || '',
  addressLine2: props.initialAddress.addressLine2 || '',
  city: props.initialAddress.city || '',
  state: props.initialAddress.state || '',
  zipCode: props.initialAddress.zipCode || ''
})

// Watch for changes in initialAddress prop
watch(() => props.initialAddress, (newAddress) => {
  if (newAddress) {
    manualAddress.value = {
      address: newAddress.address || '',
      addressLine2: newAddress.addressLine2 || '',
      city: newAddress.city || '',
      state: newAddress.state || '',
      zipCode: newAddress.zipCode || ''
    }
    emitManualAddress()
  }
}, { deep: true })

// Emit manual address
const emitManualAddress = () => {
  emit('update:address', {
    ...manualAddress.value,
    latitude: null,
    longitude: null
  })
}
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  transition: border-color 0.2s;
}

.input-field:focus {
  outline: none;
  border-color: #a97447;
  box-shadow: 0 0 0 3px rgba(169, 116, 71, 0.1);
}
</style>
