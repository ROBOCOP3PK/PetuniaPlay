<template>
  <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-fuchsia-100 dark:bg-fuchsia-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="pi pi-envelope text-3xl text-fuchsia-600 dark:text-fuchsia-400"></i>
          </div>
          <h1 class="text-2xl font-bold text-primary dark:text-fuchsia-400 mb-2">Verifica tu Email</h1>
          <p class="text-gray-600 dark:text-gray-300">
            Enviamos un código de 6 dígitos a
          </p>
          <p class="font-semibold text-gray-900 dark:text-white">{{ email }}</p>
        </div>

        <!-- Success State -->
        <div v-if="verified" class="text-center">
          <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="pi pi-check text-4xl text-green-600 dark:text-green-400"></i>
          </div>
          <h2 class="text-xl font-bold text-green-600 dark:text-green-400 mb-2">Email Verificado</h2>
          <p class="text-gray-600 dark:text-gray-300 mb-6">
            Tu cuenta ha sido verificada exitosamente.
          </p>
          <router-link
            to="/account"
            class="btn-primary w-full block text-center"
          >
            Ir a Mi Cuenta
          </router-link>
        </div>

        <!-- Verification Form -->
        <div v-else>
          <VerificationCodeInput
            ref="codeInput"
            v-model="code"
            :disabled="loading"
            :expiration-minutes="10"
            @complete="handleVerify"
            @resend="handleResend"
          />

          <!-- Error Message -->
          <div v-if="error" class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-400 text-center">{{ error }}</p>
          </div>

          <!-- Verify Button -->
          <button
            @click="handleVerify"
            :disabled="loading || code.length !== 6"
            class="btn-primary w-full mt-6"
            :class="{ 'opacity-50 cursor-not-allowed': loading || code.length !== 6 }"
          >
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <i class="pi pi-spin pi-spinner"></i>
              Verificando...
            </span>
            <span v-else>Verificar Email</span>
          </button>

          <!-- Skip for now -->
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
              Puedes verificar tu email más tarde
            </p>
            <router-link
              to="/account"
              class="text-primary dark:text-fuchsia-400 font-semibold hover:underline text-sm"
            >
              Continuar sin verificar
            </router-link>
          </div>
        </div>

        <!-- Help Text -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
          <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
            ¿No recibiste el código? Revisa tu carpeta de spam o
            <button
              @click="handleResend"
              :disabled="loading"
              class="text-fuchsia-600 dark:text-fuchsia-400 hover:underline"
            >
              solicita uno nuevo
            </button>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'
import VerificationCodeInput from '../components/common/VerificationCodeInput.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const email = ref(route.query.email || authStore.userEmail || '')
const code = ref('')
const loading = ref(false)
const error = ref('')
const verified = ref(false)
const codeInput = ref(null)

const handleVerify = async () => {
  if (code.value.length !== 6) return

  loading.value = true
  error.value = ''

  const result = await authStore.verifyEmail(email.value, code.value)

  loading.value = false

  if (result.success) {
    verified.value = true
    toast.success('Email verificado exitosamente')
  } else {
    error.value = result.message
    toast.error(result.message)
  }
}

const handleResend = async () => {
  loading.value = true
  error.value = ''

  const result = await authStore.resendVerificationCode(email.value, 'email_verification')

  loading.value = false

  if (result.success) {
    toast.success('Código reenviado exitosamente')
    codeInput.value?.resetTimer()
    codeInput.value?.reset()
  } else {
    toast.error(result.message)
  }
}

onMounted(() => {
  if (!email.value) {
    router.push('/login')
  }
})
</script>
