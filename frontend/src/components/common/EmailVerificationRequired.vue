<template>
  <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
    <div class="flex items-start gap-3">
      <div class="flex-shrink-0">
        <i class="pi pi-exclamation-triangle text-xl text-amber-600 dark:text-amber-400"></i>
      </div>
      <div class="flex-1">
        <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-200 mb-1">
          Verificación de email requerida
        </h3>
        <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
          {{ message || 'Debes verificar tu email para realizar esta acción.' }}
        </p>
        <router-link
          :to="{ path: '/verify-email', query: { email: userEmail } }"
          class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg transition"
        >
          <i class="pi pi-envelope"></i>
          Verificar email
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../stores/authStore'

defineProps({
  message: {
    type: String,
    default: ''
  }
})

const authStore = useAuthStore()
const userEmail = computed(() => authStore.userEmail)
</script>
