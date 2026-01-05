<template>
  <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-primary dark:text-fuchsia-400 mb-2">Crear Cuenta</h1>
          <p class="text-gray-600 dark:text-gray-300">Únete a Petunia Play</p>
        </div>

        <!-- Register Form -->
        <form @submit.prevent="handleRegister" class="space-y-5">
          <!-- Name -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Nombre Completo *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="input-field"
              placeholder="Juan Pérez"
              :disabled="loading"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Email *</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="input-field"
              placeholder="tu@email.com"
              :disabled="loading"
            />
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Teléfono *</label>
            <input
              v-model="form.phone"
              type="tel"
              required
              class="input-field"
              placeholder="+57 305 759 4088"
              :disabled="loading"
            />
          </div>

          <!-- Document -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Documento de Identidad *</label>
            <input
              v-model="form.document"
              type="text"
              required
              class="input-field"
              placeholder="1234567890"
              :disabled="loading"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Contraseña *</label>
            <input
              v-model="form.password"
              type="password"
              required
              minlength="8"
              class="input-field"
              placeholder="Mínimo 8 caracteres"
              :disabled="loading"
            />
          </div>

          <!-- Password Confirmation -->
          <div>
            <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Confirmar Contraseña *</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              class="input-field"
              placeholder="Repite tu contraseña"
              :disabled="loading"
            />
          </div>

          <!-- Terms -->
          <div>
            <label class="flex items-start">
              <input
                v-model="form.acceptTerms"
                type="checkbox"
                required
                class="mt-1 mr-3 w-4 h-4 text-primary bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary"
                :disabled="loading"
              />
              <span class="text-sm text-gray-900 dark:text-gray-300">
                Acepto los <a href="#" class="text-primary dark:text-fuchsia-400 hover:underline font-semibold">términos y condiciones</a>
                y la <a href="#" class="text-primary dark:text-fuchsia-400 hover:underline font-semibold">política de privacidad</a> de Petunia Play
              </span>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading || !form.acceptTerms"
            class="btn-primary w-full text-lg mt-6"
            :class="{ 'opacity-50 cursor-not-allowed': loading || !form.acceptTerms }"
          >
            {{ loading ? 'Creando cuenta...' : 'Crear Cuenta' }}
          </button>
        </form>

        <!-- Divider -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600 dark:text-gray-300">
            ¿Ya tienes cuenta?
            <router-link to="/login" class="text-primary dark:text-fuchsia-400 font-semibold hover:underline">
              Inicia sesión aquí
            </router-link>
          </p>
        </div>

        <!-- Or continue as guest -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
          <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-3">
            También puedes continuar sin cuenta
          </p>
          <router-link to="/products" class="w-full block text-center px-6 py-3 border-2 border-primary dark:border-fuchsia-400 text-primary dark:text-fuchsia-400 hover:bg-primary dark:hover:bg-fuchsia-500 hover:text-white rounded-lg font-semibold transition">
            Continuar como Invitado
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const loading = ref(false)

const form = reactive({
  name: '',
  email: '',
  phone: '',
  document: '',
  password: '',
  password_confirmation: '',
  acceptTerms: false
})

const handleRegister = async () => {
  console.log('handleRegister called', form)

  // Validar que las contraseñas coincidan
  if (form.password !== form.password_confirmation) {
    toast.warning('Las contraseñas no coinciden')
    return
  }

  loading.value = true

  const result = await authStore.register({
    name: form.name,
    email: form.email,
    phone: form.phone,
    document: form.document,
    password: form.password,
    password_confirmation: form.password_confirmation
  })

  loading.value = false

  if (result.success) {
    toast.success(`¡Bienvenido ${result.user.name}! Tu cuenta ha sido creada exitosamente.`)

    // Redirigir a verificación de email si es requerido
    if (result.requiresEmailVerification) {
      router.push({ path: '/verify-email', query: { email: form.email } })
    } else {
      router.push('/account')
    }
  } else {
    // Mostrar errores de validación si existen
    if (result.errors) {
      Object.values(result.errors).forEach(errorArray => {
        errorArray.forEach(error => toast.error(error))
      })
    } else {
      toast.error(result.message)
    }
  }
}
</script>
