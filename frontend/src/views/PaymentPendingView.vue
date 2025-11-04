<template>
  <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <!-- Pending Icon -->
        <div class="text-center mb-6">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-4">
            <svg class="w-12 h-12 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Pago Pendiente</h1>
          <p class="text-gray-600 dark:text-gray-400">
            Tu pago está siendo procesado
          </p>
        </div>

        <!-- Order Info -->
        <div v-if="orderInfo" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Número de Orden</p>
              <p class="text-lg font-bold text-gray-900 dark:text-white">{{ orderInfo.order_number }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
              <p class="text-lg font-bold text-yellow-600 dark:text-yellow-400">${{ formatPrice(orderInfo.total) }}</p>
            </div>
          </div>
        </div>

        <!-- Pending Message -->
        <div class="bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 mb-6">
          <p class="text-gray-700 dark:text-gray-300 text-center mb-2">
            Tu pago está siendo verificado por la entidad financiera.
          </p>
          <p class="text-gray-700 dark:text-gray-300 text-center">
            Recibirás un email de confirmación cuando el pago sea aprobado.
          </p>
        </div>

        <!-- Info -->
        <div class="mb-8">
          <h3 class="font-bold text-gray-900 dark:text-white mb-3">¿Qué significa esto?</h3>
          <ul class="space-y-2 text-gray-700 dark:text-gray-300">
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <span>El proceso de verificación puede tardar algunos minutos o hasta 48 horas</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <span>Te notificaremos por email cuando el pago sea aprobado</span>
            </li>
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <span>Puedes verificar el estado de tu pedido en "Mis Pedidos"</span>
            </li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/"
            class="btn-primary text-center"
          >
            Volver al Inicio
          </router-link>
          <router-link
            v-if="isAuthenticated"
            to="/profile/orders"
            class="btn-secondary text-center"
          >
            Ver Mis Pedidos
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '../stores/authStore'
import { useFormat } from '@/composables/useFormat'
import paymentService from '../services/paymentService'

const authStore = useAuthStore()
const { formatPrice } = useFormat()

const orderInfo = ref(null)
const loading = ref(true)

const isAuthenticated = computed(() => authStore.isAuthenticated)

onMounted(async () => {
  // Obtener el order_id del session storage
  const orderId = sessionStorage.getItem('pending_order_id')

  if (orderId) {
    try {
      const response = await paymentService.getPaymentStatus(orderId)
      orderInfo.value = response.data.order
    } catch (error) {
      console.error('Error al obtener información del pago:', error)
    }
  }

  loading.value = false
})
</script>
