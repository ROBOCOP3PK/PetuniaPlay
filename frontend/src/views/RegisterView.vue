<template>
  <div class="min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-primary mb-2">Crear Cuenta</h1>
          <p class="text-gray-600">Únete a Petunia Play</p>
        </div>

        <!-- Register Form -->
        <form @submit.prevent="handleRegister" class="space-y-5">
          <!-- Name -->
          <div>
            <label class="block text-sm font-semibold mb-2">Nombre Completo *</label>
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
            <label class="block text-sm font-semibold mb-2">Email *</label>
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
            <label class="block text-sm font-semibold mb-2">Teléfono *</label>
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
            <label class="block text-sm font-semibold mb-2">Documento de Identidad *</label>
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
            <label class="block text-sm font-semibold mb-2">Contraseña *</label>
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
            <label class="block text-sm font-semibold mb-2">Confirmar Contraseña *</label>
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
                class="mt-1 mr-3"
                :disabled="loading"
              />
              <span class="text-sm">
                Acepto los <a href="#" class="text-primary hover:underline">términos y condiciones</a>
                y la <a href="#" class="text-primary hover:underline">política de privacidad</a> de Petunia Play
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
          <p class="text-sm text-gray-600">
            ¿Ya tienes cuenta?
            <router-link to="/login" class="text-primary font-semibold hover:underline">
              Inicia sesión aquí
            </router-link>
          </p>
        </div>

        <!-- Or continue as guest -->
        <div class="mt-6 pt-6 border-t">
          <p class="text-center text-sm text-gray-600 mb-3">
            También puedes continuar sin cuenta
          </p>
          <router-link to="/products" class="btn-outline w-full block text-center">
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
    router.push('/account')
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
