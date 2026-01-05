<template>
  <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-md w-full space-y-8">
      <div>
        <router-link to="/" class="flex justify-center">
          <div class="w-16 h-16 bg-primary dark:bg-primary-light rounded-full flex items-center justify-center text-white font-bold text-2xl">
            PP
          </div>
        </router-link>
        <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">
          ¿Olvidaste tu contraseña?
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          Te enviaremos un código de 6 dígitos para restablecerla
        </p>
      </div>

      <!-- Success Message -->
      <div v-if="codeSent" class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">
              Código enviado a {{ form.email }}. Serás redirigido...
            </p>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form v-if="!codeSent" class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Correo electrónico</label>
            <input
              v-model="form.email"
              id="email"
              name="email"
              type="email"
              required
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
              placeholder="Correo electrónico"
            />
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
          <p class="text-sm text-red-800 dark:text-red-200">{{ error }}</p>
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Enviando...
            </span>
            <span v-else>Enviar código de recuperación</span>
          </button>
        </div>

        <div class="text-center">
          <router-link to="/login" class="font-medium text-primary dark:text-fuchsia-400 hover:text-primary-dark">
            ← Volver a iniciar sesión
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const form = ref({
  email: ''
})

const loading = ref(false)
const codeSent = ref(false)
const error = ref('')

const handleSubmit = async () => {
  loading.value = true
  error.value = ''

  const result = await authStore.sendPasswordResetCode(form.value.email)

  loading.value = false

  if (result.success) {
    codeSent.value = true
    toast.success('Código enviado exitosamente')

    // Redirigir a reset-password después de 1.5 segundos
    setTimeout(() => {
      router.push({ path: '/reset-password', query: { email: form.value.email } })
    }, 1500)
  } else {
    error.value = result.message
  }
}
</script>
