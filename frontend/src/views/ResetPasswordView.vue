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
          Restablecer contraseña
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          Ingresa tu nueva contraseña
        </p>
      </div>

      <!-- Success Message -->
      <div v-if="success" class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">
              Contraseña restablecida exitosamente. Serás redirigido al login...
            </p>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form v-if="!success" class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Nueva contraseña
            </label>
            <input
              v-model="form.password"
              id="password"
              name="password"
              type="password"
              required
              minlength="8"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              placeholder="Mínimo 8 caracteres"
            />
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Confirmar contraseña
            </label>
            <input
              v-model="form.password_confirmation"
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              required
              minlength="8"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              placeholder="Confirma tu contraseña"
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
              Restableciendo...
            </span>
            <span v-else>Restablecer contraseña</span>
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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { authService } from '../services/authService'
import { useToast } from 'vue-toastification'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const form = ref({
  email: '',
  token: '',
  password: '',
  password_confirmation: ''
})

const loading = ref(false)
const success = ref(false)
const error = ref('')

onMounted(() => {
  // Get token and email from URL query params
  form.value.token = route.query.token || ''
  form.value.email = route.query.email || ''

  if (!form.value.token || !form.value.email) {
    error.value = 'Enlace de recuperación inválido'
  }
})

const handleSubmit = async () => {
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Las contraseñas no coinciden'
    return
  }

  loading.value = true
  error.value = ''

  try {
    await authService.resetPassword({
      email: form.value.email,
      token: form.value.token,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    })

    success.value = true
    toast.success('Contraseña restablecida exitosamente')

    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al restablecer la contraseña'
  } finally {
    loading.value = false
  }
}
</script>
