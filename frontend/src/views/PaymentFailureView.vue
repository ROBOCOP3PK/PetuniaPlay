<template>
  <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <!-- Error Icon -->
        <div class="text-center mb-6">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 dark:bg-red-900 mb-4">
            <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pago No Completado</h1>
          <p class="text-gray-600 dark:text-gray-400">
            No pudimos procesar tu pago
          </p>
        </div>

        <!-- Error Message -->
        <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border border-red-200 dark:border-red-700 rounded-lg p-6 mb-6">
          <p class="text-gray-700 dark:text-gray-300 text-center">
            Tu pago fue rechazado o cancelado. No se ha realizado ningún cargo a tu cuenta.
          </p>
        </div>

        <!-- Suggestions -->
        <div class="mb-8">
          <h3 class="font-bold text-gray-900 dark:text-white mb-3">¿Qué puedes hacer?</h3>
          <ul class="space-y-2 text-gray-700 dark:text-gray-300">
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Verifica que tu tarjeta tenga fondos disponibles</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Intenta con otro método de pago</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Revisa que los datos ingresados sean correctos</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Contacta con tu banco para verificar el estado de tu tarjeta</span>
            </li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/checkout"
            class="btn-primary text-center"
          >
            Intentar Nuevamente
          </router-link>
          <router-link
            to="/"
            class="btn-secondary text-center"
          >
            Volver al Inicio
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  // Limpiar el session storage al llegar a esta página
  sessionStorage.removeItem('pending_order_id')
})
</script>
