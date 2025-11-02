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
import { ref } from 'vue'

const emit = defineEmits(['update:address'])

// Address data
const manualAddress = ref({
  address: '',
  city: '',
  state: '',
  zipCode: ''
})

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
