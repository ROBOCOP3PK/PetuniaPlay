<template>
  <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <!-- Success Icon -->
        <div class="text-center mb-6">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900 mb-4">
            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">¡Pago Exitoso!</h1>
          <p class="text-gray-600 dark:text-gray-400">
            Tu pago ha sido procesado correctamente
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
              <p class="text-sm text-gray-600 dark:text-gray-400">Total Pagado</p>
              <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ formatPrice(orderInfo.total) }}</p>
            </div>
          </div>
        </div>

        <!-- Success Message -->
        <div class="text-center mb-8">
          <p class="text-gray-700 dark:text-gray-300 mb-4">
            Hemos enviado un email de confirmación a tu correo electrónico con los detalles de tu pedido.
          </p>
          <p class="text-gray-700 dark:text-gray-300">
            Recibirás actualizaciones sobre el estado de tu envío.
          </p>
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
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import { useFormat } from '@/composables/useFormat'
import paymentService from '../services/paymentService'

const route = useRoute()
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

      // Limpiar el session storage
      sessionStorage.removeItem('pending_order_id')
    } catch (error) {
      console.error('Error al obtener información del pago:', error)
    }
  }

  loading.value = false
})
</script>
