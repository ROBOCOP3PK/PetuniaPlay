<template>
  <div class="min-h-screen py-8 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <!-- Redirect if cart is empty -->
      <div v-if="!cartStore.hasItems" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Tu carrito está vacío</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
          Debes agregar productos antes de proceder al pago
        </p>
        <router-link to="/products" class="btn-primary inline-block">
          Explorar Productos
        </router-link>
      </div>

      <!-- Checkout Form -->
      <div v-else>
        <h1 class="text-4xl font-bold mb-8 text-gray-900 dark:text-white">Finalizar Compra</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Forms -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">1. Información Personal</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Nombre Completo *</label>
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="input-field"
                    placeholder="Juan Pérez"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Email *</label>
                  <input
                    v-model="form.email"
                    type="email"
                    required
                    class="input-field"
                    placeholder="juan@ejemplo.com"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Teléfono *</label>
                  <input
                    v-model="form.phone"
                    type="tel"
                    required
                    class="input-field"
                    placeholder="+57 305 759 4088"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Documento de Identidad *</label>
                  <input
                    v-model="form.document"
                    type="text"
                    required
                    class="input-field"
                    placeholder="1234567890"
                  />
                </div>
              </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">2. Dirección de Envío</h2>

              <!-- Address Map Picker Component -->
              <AddressMapPicker
                :initial-address="initialAddressData"
                @update:address="handleAddressUpdate"
              />

              <!-- Notes Field -->
              <div class="mt-4">
                <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Notas de Entrega (Opcional)</label>
                <textarea
                  v-model="form.notes"
                  rows="3"
                  class="input-field resize-none"
                  placeholder="Apartamento 301, tocar timbre..."
                ></textarea>
              </div>

              <!-- Night Delivery Option (Only for Bogotá and if enabled) -->
              <div v-if="isBogota && nightDeliveryEnabled" class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900 dark:to-indigo-900 dark:bg-opacity-20 border-2 border-purple-200 dark:border-purple-700 rounded-lg">
                <div class="flex items-start space-x-3">
                  <input
                    id="nightDelivery"
                    v-model="form.nightDelivery"
                    type="checkbox"
                    class="mt-1 w-5 h-5 text-purple-600 dark:text-purple-400 bg-white dark:bg-gray-700 border-purple-300 dark:border-purple-600 rounded focus:ring-purple-500 focus:ring-2 cursor-pointer"
                  />
                  <label for="nightDelivery" class="cursor-pointer flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="text-lg">🌙</span>
                      <span class="font-bold text-gray-900 dark:text-white">Entrega Nocturna - ¡ENVÍO GRATIS!</span>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                      Recibe tu pedido en horario nocturno <strong>({{ nightDeliveryTime.start }} - {{ nightDeliveryTime.end }})</strong> sin costo adicional.
                      <span class="block mt-1 text-purple-700 dark:text-purple-300 font-semibold">
                        ✨ Exclusivo para Bogotá - Ahorra ${{ formatPrice(cartStore.shippingConfig?.standard_shipping_cost || 10000) }} en envío
                      </span>
                    </p>
                  </label>
                </div>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">3. Método de Pago</h2>

              <div class="space-y-4">
                <!-- Payment Options -->
                <div class="space-y-3">
                  <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                    :class="form.paymentMethod === 'online' ? 'border-primary bg-primary bg-opacity-5 dark:bg-opacity-20' : 'border-gray-300 dark:border-gray-600 hover:border-primary'">
                    <input
                      v-model="form.paymentMethod"
                      type="radio"
                      value="online"
                      class="mr-3"
                    />
                    <div class="flex-1">
                      <div class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        💳 Pago Online con Mercado Pago
                        <img src="https://http2.mlstatic.com/storage/logos-api-admin/51b446b0-571c-11e8-9a2d-4b2bd7b1bf77-m.svg" alt="Mercado Pago" class="h-5">
                      </div>
                      <div class="text-sm text-gray-600 dark:text-gray-400">Tarjetas de crédito/débito, PSE, efectivo y más</div>
                    </div>
                  </label>

                  <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                    :class="form.paymentMethod === 'cash' ? 'border-primary bg-primary bg-opacity-5 dark:bg-opacity-20' : 'border-gray-300 dark:border-gray-600 hover:border-primary'">
                    <input
                      v-model="form.paymentMethod"
                      type="radio"
                      value="cash"
                      class="mr-3"
                    />
                    <div class="flex-1">
                      <div class="font-semibold text-gray-900 dark:text-white">💵 Contra Entrega</div>
                      <div class="text-sm text-gray-600 dark:text-gray-400">Paga en efectivo al recibir tu pedido</div>
                    </div>
                  </label>
                </div>

                <!-- Online Payment Info -->
                <div v-if="form.paymentMethod === 'online'" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-700 rounded-lg">
                  <div class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                      <p class="font-semibold mb-1">Serás redirigido a Mercado Pago</p>
                      <p>Completa tu pago de forma segura con todos los métodos disponibles: tarjetas, PSE, Nequi, Daviplata y más.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <label class="flex items-start">
                <input
                  v-model="form.acceptTerms"
                  type="checkbox"
                  class="mt-1 mr-3"
                />
                <span class="text-sm text-gray-900 dark:text-gray-300">
                  Acepto los <a href="#" class="text-primary dark:text-fuchsia-400 hover:underline">términos y condiciones</a>
                  y la <a href="#" class="text-primary dark:text-fuchsia-400 hover:underline">política de privacidad</a> de Petunia Play
                </span>
              </label>
            </div>
          </div>

          <!-- Order Summary (Sticky) -->
          <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-24">
              <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Resumen del Pedido</h2>

              <!-- Products -->
              <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                <div
                  v-for="item in cartStore.items"
                  :key="item.product.id"
                  class="flex gap-3 pb-3 border-b dark:border-gray-700"
                >
                  <img
                    :src="item.product.primary_image?.image_url || 'https://via.placeholder.com/60'"
                    :alt="item.product.name"
                    class="w-16 h-16 object-cover rounded"
                  />
                  <div class="flex-1">
                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ item.product.name }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Cantidad: {{ item.quantity }}</p>
                    <p class="text-primary dark:text-white font-bold">
                      ${{ formatPrice(parseFloat(item.product.final_price) * item.quantity) }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Coupon Section -->
              <div class="mb-6 pb-6 border-b dark:border-gray-700">
                <h3 class="font-bold mb-3 text-sm text-gray-900 dark:text-white">¿Tienes un cupón?</h3>

                <!-- Applied Coupon -->
                <div v-if="cartStore.hasCoupon" class="bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border border-green-200 dark:border-green-700 rounded-lg p-3 mb-3">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                      <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <div>
                        <p class="font-bold text-green-800 dark:text-green-300 text-sm">{{ cartStore.appliedCoupon.code }}</p>
                        <p class="text-xs text-green-700 dark:text-green-400">
                          Descuento: ${{ formatPrice(cartStore.discount) }}
                        </p>
                      </div>
                    </div>
                    <button
                      @click="removeCoupon"
                      class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold"
                    >
                      Quitar
                    </button>
                  </div>
                </div>

                <!-- Coupon Input -->
                <div v-else class="flex gap-2">
                  <input
                    v-model="couponCode"
                    type="text"
                    placeholder="Código de cupón"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                    @keyup.enter="applyCoupon"
                    :disabled="applyingCoupon"
                  />
                  <button
                    @click="applyCoupon"
                    :disabled="applyingCoupon || !couponCode"
                    class="px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-semibold"
                  >
                    {{ applyingCoupon ? 'Verificando...' : 'Aplicar' }}
                  </button>
                </div>

                <!-- Coupon Error -->
                <p v-if="couponError" class="text-red-600 dark:text-red-400 text-xs mt-2">
                  {{ couponError }}
                </p>
              </div>

              <!-- Totals -->
              <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                  <span class="font-semibold text-gray-900 dark:text-white">${{ formatPrice(cartStore.subtotal) }}</span>
                </div>
                <!-- Seccion de cobro de iva -->
                <!-- <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">IVA (19%)</span>
                  <span class="font-semibold text-gray-900 dark:text-white">${{ formatPrice(cartStore.tax) }}</span>
                </div> -->
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-400">Envío</span>
                  <span v-if="shippingCost === 0" class="text-green-600 dark:text-green-400 font-semibold">
                    <span v-if="form.nightDelivery">¡Gratis! 🌙 Entrega nocturna</span>
                    <span v-else>¡Gratis! (+{{ freeShippingMinItems - 1 }} artículos)</span>
                  </span>
                  <span v-else class="font-semibold text-gray-900 dark:text-white">
                    ${{ formatPrice(shippingCost) }}
                  </span>
                </div>

                <!-- Shipping Info -->
                <div v-if="shippingCost === 0 && form.nightDelivery" class="text-xs text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-900 dark:bg-opacity-20 p-2 rounded border border-purple-200 dark:border-purple-700">
                  🌙 <strong>Entrega nocturna seleccionada</strong> - Tu pedido llegará entre {{ nightDeliveryTime.start }} y {{ nightDeliveryTime.end }}
                </div>
                <div v-else-if="shippingCost > 0 && isBogota && cartStore.itemCount < freeShippingMinItems" class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                  💡 <strong>Opciones de envío gratis:</strong><br>
                  • Agrega {{ freeShippingMinItems - cartStore.itemCount }} artículo(s) más<br>
                  <span v-if="nightDeliveryEnabled">• O selecciona entrega nocturna ({{ nightDeliveryTime.startShort }}-{{ nightDeliveryTime.endShort }})</span>
                </div>
                <div v-else-if="shippingCost > 0 && !isBogota" class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                  ℹ️ Envío gratis disponible solo en Bogotá
                </div>

                <div v-if="cartStore.hasCoupon" class="flex justify-between text-sm text-green-600 dark:text-green-400">
                  <span class="font-semibold">Descuento</span>
                  <span class="font-bold">-${{ formatPrice(cartStore.discount) }}</span>
                </div>
              </div>

              <!-- Total -->
              <div class="border-t dark:border-gray-700 pt-4 mb-6">
                <div class="flex justify-between items-center">
                  <span class="text-xl font-bold text-gray-900 dark:text-white">Total</span>
                  <span class="text-3xl font-bold text-primary dark:text-white">
                    ${{ formatPrice(orderTotal) }}
                  </span>
                </div>
              </div>

              <!-- Email Verification Warning -->
              <EmailVerificationRequired
                v-if="requiresEmailVerification"
                message="Debes verificar tu email para realizar compras."
                class="mb-4"
              />

              <!-- Place Order Button -->
              <button
                @click="placeOrder"
                :disabled="processing || !form.acceptTerms || requiresEmailVerification"
                class="btn-primary w-full text-lg"
                :class="{ 'opacity-50 cursor-not-allowed': processing || !form.acceptTerms || requiresEmailVerification }"
              >
                {{ processing ? 'Procesando...' : 'Realizar Pedido' }}
              </button>

              <!-- Security Icons -->
              <div class="mt-6 pt-6 border-t dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400 text-center mb-3">🔒 Pago 100% Seguro</p>
                <div class="flex justify-center items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                  <span>SSL Cifrado</span>
                  <span>•</span>
                  <span>Datos Protegidos</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cartStore'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'
import { useFormat } from '@/composables/useFormat'
import orderService from '../services/orderService'
import addressService from '../services/addressService'
import paymentService from '../services/paymentService'
import AddressMapPicker from '../components/AddressMapPicker.vue'
import EmailVerificationRequired from '../components/common/EmailVerificationRequired.vue'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()
const toast = useToast()
const { formatPrice, formatDate } = useFormat()

// Estado para la dirección inicial
const initialAddressData = ref({
  address: '',
  addressLine2: '',
  city: '',
  state: '',
  zipCode: ''
})

// Computed para calcular el costo de envío dinámicamente basado en la ciudad y opciones
const shippingCost = computed(() => {
  return cartStore.calculateShipping(form.city, form.nightDelivery)
})

// Computed para el total con el shipping correcto
const orderTotal = computed(() => {
  return cartStore.subtotal + cartStore.tax + shippingCost.value - cartStore.discount
})

// Computed para saber si es Bogotá
const isBogota = computed(() => {
  const city = form.city.toLowerCase().trim()
  return city === 'bogotá' || city === 'bogota'
})

// Computed para verificar si requiere verificación de email
const requiresEmailVerification = computed(() => {
  return authStore.isAuthenticated && !authStore.isEmailVerified
})

// Computed para obtener el horario formateado desde la configuración
const nightDeliveryTime = computed(() => {
  if (!cartStore.shippingConfig) {
    return { start: '9:00 PM', end: '2:00 AM' }
  }

  const formatTime = (time) => {
    if (!time) return ''
    const [hours, minutes] = time.substring(0, 5).split(':')
    const hour = parseInt(hours)

    if (hour === 0) {
      return `12:${minutes} AM`
    } else if (hour < 12) {
      return `${hour}:${minutes} AM`
    } else if (hour === 12) {
      return `12:${minutes} PM`
    } else {
      return `${hour - 12}:${minutes} PM`
    }
  }

  return {
    start: formatTime(cartStore.shippingConfig.night_delivery_start_time),
    end: formatTime(cartStore.shippingConfig.night_delivery_end_time),
    startShort: cartStore.shippingConfig.night_delivery_start_time.substring(0, 5).replace(':', '') > '1200'
      ? parseInt(cartStore.shippingConfig.night_delivery_start_time.substring(0, 2)) - 12 + 'PM'
      : cartStore.shippingConfig.night_delivery_start_time.substring(0, 2) + 'PM',
    endShort: cartStore.shippingConfig.night_delivery_end_time.substring(0, 5).replace(':', '') < '1200'
      ? cartStore.shippingConfig.night_delivery_end_time.substring(0, 2) + 'AM'
      : parseInt(cartStore.shippingConfig.night_delivery_end_time.substring(0, 2)) - 12 + 'PM'
  }
})

// Computed para verificar si la entrega nocturna está habilitada
const nightDeliveryEnabled = computed(() => {
  return cartStore.shippingConfig?.night_delivery_enabled ?? true
})

// Computed para obtener el mínimo de artículos para envío gratis
const freeShippingMinItems = computed(() => {
  return cartStore.shippingConfig?.free_shipping_min_items ?? 4
})

const processing = ref(false)
const couponCode = ref('')
const applyingCoupon = ref(false)
const couponError = ref('')

const form = reactive({
  // Personal Info
  name: '',
  email: '',
  phone: '',
  document: '',

  // Shipping
  address: '',
  addressLine2: '',
  city: '',
  state: '',
  zipCode: '',
  latitude: null,
  longitude: null,
  notes: '',
  nightDelivery: false,

  // Payment
  paymentMethod: 'online',

  // Terms
  acceptTerms: false
})

// Handle address update from map picker
const handleAddressUpdate = (addressData) => {
  form.address = addressData.address
  form.addressLine2 = addressData.addressLine2 || ''
  form.city = addressData.city
  form.state = addressData.state
  form.zipCode = addressData.zipCode || ''
  form.latitude = addressData.latitude
  form.longitude = addressData.longitude
}

// Coupon functions
const applyCoupon = async () => {
  if (!couponCode.value || couponCode.value.trim() === '') {
    couponError.value = 'Por favor ingresa un código de cupón'
    return
  }

  applyingCoupon.value = true
  couponError.value = ''

  try {
    const result = await cartStore.applyCoupon(couponCode.value)

    if (result.success) {
      toast.success(result.message || 'Cupón aplicado correctamente')
      couponCode.value = ''
    } else {
      couponError.value = result.message || 'Cupón inválido'
    }
  } catch (error) {
    couponError.value = 'Error al aplicar el cupón'
  } finally {
    applyingCoupon.value = false
  }
}

const removeCoupon = () => {
  cartStore.removeCoupon()
  couponCode.value = ''
  couponError.value = ''
  toast.info('Cupón removido')
}

const validateForm = () => {
  if (!form.name || !form.email || !form.phone || !form.document) {
    toast.warning('Por favor completa todos los campos personales')
    return false
  }

  if (!form.address || !form.city || !form.state) {
    toast.warning('Por favor completa la dirección de envío')
    return false
  }

  if (!form.acceptTerms) {
    toast.warning('Debes aceptar los términos y condiciones')
    return false
  }

  return true
}

const placeOrder = async () => {
  if (!validateForm()) return

  processing.value = true

  try {
    // Preparar datos del pedido
    const orderData = {
      customer: {
        name: form.name,
        email: form.email,
        phone: form.phone,
        document: form.document
      },
      shipping: {
        address: form.address,
        addressLine2: form.addressLine2,
        city: form.city,
        state: form.state,
        zipCode: form.zipCode,
        latitude: form.latitude,
        longitude: form.longitude,
        notes: form.notes,
        nightDelivery: form.nightDelivery
      },
      payment: {
        method: form.paymentMethod === 'cash' ? 'cash' : 'mercadopago',
        amount: orderTotal.value
      },
      items: cartStore.items.map(item => ({
        product_id: item.product.id,
        quantity: item.quantity,
        price: parseFloat(item.product.final_price)
      })),
      totals: {
        subtotal: cartStore.subtotal,
        tax: cartStore.tax,
        shipping: shippingCost.value,
        discount: cartStore.discount,
        total: orderTotal.value
      },
      coupon_code: cartStore.appliedCoupon?.code || null
    }

    // Enviar pedido a la API
    const response = await orderService.create(orderData)
    const order = response.data.data

    // Si el método de pago es contra entrega, finalizar el proceso
    if (form.paymentMethod === 'cash') {
      // Limpiar carrito
      cartStore.clearCart()

      // Mostrar mensaje de éxito
      toast.success(`¡Pedido realizado exitosamente! Número de orden: ${order.order_number}. Recibirás un email de confirmación pronto.`, {
        timeout: 6000
      })

      // Redirigir a página principal
      router.push('/')
    } else {
      // Si es pago online, crear preferencia de Mercado Pago y redirigir
      const paymentResponse = await paymentService.createPreference(order.id)
      const paymentData = paymentResponse.data

      if (paymentData.success && paymentData.init_point) {
        // Guardar el order ID para recuperarlo después
        sessionStorage.setItem('pending_order_id', order.id)

        // Limpiar carrito antes de redirigir
        cartStore.clearCart()

        // Mostrar mensaje informativo
        toast.info('Redirigiendo a Mercado Pago para completar el pago...', {
          timeout: 2000
        })

        // Esperar un momento y redirigir a Mercado Pago
        setTimeout(() => {
          window.location.href = paymentData.init_point
        }, 1000)
      } else {
        throw new Error('No se pudo crear la preferencia de pago')
      }
    }

  } catch (error) {
    console.error('Error al procesar el pedido:', error)

    // Mostrar mensaje de error más específico
    const errorMessage = error.response?.data?.message || error.message || 'Hubo un error al procesar tu pedido. Por favor intenta nuevamente.'
    toast.error(errorMessage)
  } finally {
    processing.value = false
  }
}

// Precargar datos del usuario al montar el componente
onMounted(async () => {
  if (authStore.isAuthenticated && authStore.user) {
    // Precargar datos personales del usuario
    form.name = authStore.user.name || ''
    form.email = authStore.user.email || ''
    form.phone = authStore.user.phone || ''
    form.document = authStore.user.document || ''

    // Cargar dirección predeterminada del usuario
    try {
      const response = await addressService.getAll()
      const addresses = response.data.data || response.data
      const defaultAddress = addresses.find(addr => addr.is_default)

      if (defaultAddress) {
        // Mapear los campos de la dirección al formato esperado
        initialAddressData.value = {
          address: defaultAddress.address_line_1 || '',
          addressLine2: defaultAddress.address_line_2 || '',
          city: defaultAddress.city || '',
          state: defaultAddress.state || '',
          zipCode: defaultAddress.postal_code || ''
        }

        // También prellenar el formulario con estos datos
        form.address = defaultAddress.address_line_1 || ''
        form.addressLine2 = defaultAddress.address_line_2 || ''
        form.city = defaultAddress.city || ''
        form.state = defaultAddress.state || ''
        form.zipCode = defaultAddress.postal_code || ''
      }
    } catch (error) {
      console.error('Error al cargar direcciones:', error)
      // No mostramos error al usuario, simplemente no precargamos la dirección
    }
  }
})
</script>
