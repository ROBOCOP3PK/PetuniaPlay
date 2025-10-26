<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
      <!-- Loading State -->
      <div v-if="loading" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-4"></div>
        <p class="text-gray-700 dark:text-gray-300">Procesando tu solicitud...</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
          Desuscripción Exitosa
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
          {{ message }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
          Ya no recibirás emails de notificaciones. Si cambias de opinión, puedes volver a suscribirte desde tu cuenta.
        </p>
        <div class="space-y-3">
          <router-link
            to="/"
            class="block w-full px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold"
          >
            Volver al Inicio
          </router-link>
          <router-link
            to="/account"
            class="block w-full px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition font-semibold"
          >
            Ir a Mi Cuenta
          </router-link>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
          Error en la Desuscripción
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
          {{ message }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
          El enlace puede haber expirado o ser inválido. Si continúas teniendo problemas, contáctanos directamente.
        </p>
        <div class="space-y-3">
          <router-link
            to="/contact"
            class="block w-full px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold"
          >
            Contactar Soporte
          </router-link>
          <router-link
            to="/"
            class="block w-full px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition font-semibold"
          >
            Volver al Inicio
          </router-link>
        </div>
      </div>

      <!-- Legal Info -->
      <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        <p class="mb-2">
          De acuerdo con la Ley 1581 de 2012 de Protección de Datos Personales de Colombia
        </p>
        <p>
          Tienes derecho a conocer, actualizar, rectificar y suprimir tus datos personales
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const loading = ref(true)
const success = ref(false)
const error = ref(false)
const message = ref('')

const unsubscribe = async () => {
  const token = route.query.token

  if (!token) {
    error.value = true
    message.value = 'Token de desuscripción no válido'
    loading.value = false
    return
  }

  try {
    const response = await api.get(`/api/v1/unsubscribe/${encodeURIComponent(token)}`)

    if (response.data.success) {
      success.value = true
      message.value = response.data.message || 'Te has desuscrito exitosamente de las notificaciones por email.'
    } else {
      error.value = true
      message.value = response.data.message || 'No se pudo procesar tu solicitud'
    }
  } catch (err) {
    error.value = true
    message.value = err.response?.data?.message || 'Token inválido o expirado'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  unsubscribe()
})
</script>
