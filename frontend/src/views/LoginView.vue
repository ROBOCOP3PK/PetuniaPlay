<template>
  <div class="min-h-screen py-12 bg-gray-50 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-primary mb-2">Iniciar Sesión</h1>
          <p class="text-gray-600">Bienvenido de vuelta a Petunia Play</p>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="input-field"
              placeholder="tu@email.com"
              :disabled="loading"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-semibold mb-2">Contraseña</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="input-field"
              placeholder="••••••••"
              :disabled="loading"
            />
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input
                v-model="form.remember"
                type="checkbox"
                class="mr-2"
                :disabled="loading"
              />
              <span class="text-sm">Recordarme</span>
            </label>
            <router-link to="/forgot-password" class="text-sm text-primary hover:underline">
              ¿Olvidaste tu contraseña?
            </router-link>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="btn-primary w-full text-lg"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
          >
            {{ loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
          </button>
        </form>

        <!-- Divider -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            ¿No tienes cuenta?
            <router-link to="/register" class="text-primary font-semibold hover:underline">
              Regístrate aquí
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
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const toast = useToast()

const loading = ref(false)

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const handleLogin = async () => {
  loading.value = true

  const result = await authStore.login({
    email: form.email,
    password: form.password
  })

  loading.value = false

  if (result.success) {
    toast.success(`¡Bienvenido ${result.user.name}!`)

    // Redirigir a la página anterior o al dashboard
    const redirect = route.query.redirect || '/account'
    router.push(redirect)
  } else {
    toast.error(result.message)
  }
}
</script>
