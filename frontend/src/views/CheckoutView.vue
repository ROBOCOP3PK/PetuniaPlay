<template>
  <div class="min-h-screen py-8 bg-gray-50">
    <div class="container mx-auto px-4">
      <!-- Redirect if cart is empty -->
      <div v-if="!cartStore.hasItems" class="bg-white rounded-lg shadow-md p-12 text-center">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold mb-2">Tu carrito está vacío</h2>
        <p class="text-gray-600 mb-6">
          Debes agregar productos antes de proceder al pago
        </p>
        <router-link to="/products" class="btn-primary inline-block">
          Explorar Productos
        </router-link>
      </div>

      <!-- Checkout Form -->
      <div v-else>
        <h1 class="text-4xl font-bold mb-8">Finalizar Compra</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Forms -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6">1. Información Personal</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold mb-2">Nombre Completo *</label>
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="input-field"
                    placeholder="Juan Pérez"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2">Email *</label>
                  <input
                    v-model="form.email"
                    type="email"
                    required
                    class="input-field"
                    placeholder="juan@ejemplo.com"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2">Teléfono *</label>
                  <input
                    v-model="form.phone"
                    type="tel"
                    required
                    class="input-field"
                    placeholder="+57 305 759 4088"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-2">Documento de Identidad *</label>
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
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6">2. Dirección de Envío</h2>

              <!-- Address Map Picker Component -->
              <AddressMapPicker @update:address="handleAddressUpdate" />

              <!-- Notes Field -->
              <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Notas de Entrega (Opcional)</label>
                <textarea
                  v-model="form.notes"
                  rows="3"
                  class="input-field resize-none"
                  placeholder="Apartamento 301, tocar timbre..."
                ></textarea>
              </div>

              <!-- Night Delivery Option (Only for Bogotá and if enabled) -->
              <div v-if="isBogota && nightDeliveryEnabled" class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg">
                <div class="flex items-start space-x-3">
                  <input
                    id="nightDelivery"
                    v-model="form.nightDelivery"
                    type="checkbox"
                    class="mt-1 w-5 h-5 text-purple-600 bg-white border-purple-300 rounded focus:ring-purple-500 focus:ring-2 cursor-pointer"
                  />
                  <label for="nightDelivery" class="cursor-pointer flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="text-lg">🌙</span>
                      <span class="font-bold text-gray-900">Entrega Nocturna - ¡ENVÍO GRATIS!</span>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed">
                      Recibe tu pedido en horario nocturno <strong>({{ nightDeliveryTime.start }} - {{ nightDeliveryTime.end }})</strong> sin costo adicional.
                      <span class="block mt-1 text-purple-700 font-semibold">
                        ✨ Exclusivo para Bogotá - Ahorra ${{ formatPrice(cartStore.shippingConfig?.standard_shipping_cost || 10000) }} en envío
                      </span>
                    </p>
                  </label>
                </div>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-6">3. Método de Pago</h2>

              <div class="space-y-4">
                <!-- Payment Options -->
                <div class="space-y-3">
                  <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                    :class="form.paymentMethod === 'card' ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-300 hover:border-primary'">
                    <input
                      v-model="form.paymentMethod"
                      type="radio"
                      value="card"
                      class="mr-3"
                    />
                    <div class="flex-1">
                      <div class="font-semibold">💳 Tarjeta de Crédito/Débito</div>
                      <div class="text-sm text-gray-600">Visa, Mastercard, American Express</div>
                    </div>
                  </label>

                  <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                    :class="form.paymentMethod === 'pse' ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-300 hover:border-primary'">
                    <input
                      v-model="form.paymentMethod"
                      type="radio"
                      value="pse"
                      class="mr-3"
                    />
                    <div class="flex-1">
                      <div class="font-semibold">🏦 PSE</div>
                      <div class="text-sm text-gray-600">Pago seguro en línea</div>
                    </div>
                  </label>

                  <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                    :class="form.paymentMethod === 'cash' ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-300 hover:border-primary'">
                    <input
                      v-model="form.paymentMethod"
                      type="radio"
                      value="cash"
                      class="mr-3"
                    />
                    <div class="flex-1">
                      <div class="font-semibold">💵 Contra Entrega</div>
                      <div class="text-sm text-gray-600">Paga al recibir tu pedido</div>
                    </div>
                  </label>
                </div>

                <!-- Card Details (only if card selected) -->
                <div v-if="form.paymentMethod === 'card'" class="mt-6 p-4 bg-gray-50 rounded-lg">
                  <h3 class="font-bold mb-4">Datos de la Tarjeta</h3>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-semibold mb-2">Número de Tarjeta *</label>
                      <input
                        v-model="form.cardNumber"
                        type="text"
                        maxlength="19"
                        class="input-field"
                        placeholder="1234 5678 9012 3456"
                        @input="formatCardNumber"
                      />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-semibold mb-2">Vencimiento (MM/AA) *</label>
                        <input
                          v-model="form.cardExpiry"
                          type="text"
                          maxlength="5"
                          class="input-field"
                          placeholder="12/25"
                          @input="formatExpiry"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-semibold mb-2">CVV *</label>
                        <input
                          v-model="form.cardCvv"
                          type="text"
                          maxlength="4"
                          class="input-field"
                          placeholder="123"
                        />
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-semibold mb-2">Nombre en la Tarjeta *</label>
                      <input
                        v-model="form.cardName"
                        type="text"
                        class="input-field"
                        placeholder="JUAN PEREZ"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white rounded-lg shadow-md p-6">
              <label class="flex items-start">
                <input
                  v-model="form.acceptTerms"
                  type="checkbox"
                  class="mt-1 mr-3"
                />
                <span class="text-sm">
                  Acepto los <a href="#" class="text-primary hover:underline">términos y condiciones</a>
                  y la <a href="#" class="text-primary hover:underline">política de privacidad</a> de Petunia Play
                </span>
              </label>
            </div>
          </div>

          <!-- Order Summary (Sticky) -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
              <h2 class="text-2xl font-bold mb-6">Resumen del Pedido</h2>

              <!-- Products -->
              <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                <div
                  v-for="item in cartStore.items"
                  :key="item.product.id"
                  class="flex gap-3 pb-3 border-b"
                >
                  <img
                    :src="item.product.primary_image?.image_url || 'https://via.placeholder.com/60'"
                    :alt="item.product.name"
                    class="w-16 h-16 object-cover rounded"
                  />
                  <div class="flex-1">
                    <p class="font-semibold text-sm">{{ item.product.name }}</p>
                    <p class="text-xs text-gray-600">Cantidad: {{ item.quantity }}</p>
                    <p class="text-primary font-bold">
                      ${{ formatPrice(parseFloat(item.product.final_price) * item.quantity) }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Coupon Section -->
              <div class="mb-6 pb-6 border-b">
                <h3 class="font-bold mb-3 text-sm">¿Tienes un cupón?</h3>

                <!-- Applied Coupon -->
                <div v-if="cartStore.hasCoupon" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                      <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <div>
                        <p class="font-bold text-green-800 text-sm">{{ cartStore.appliedCoupon.code }}</p>
                        <p class="text-xs text-green-700">
                          Descuento: ${{ formatPrice(cartStore.discount) }}
                        </p>
                      </div>
                    </div>
                    <button
                      @click="removeCoupon"
                      class="text-red-600 hover:text-red-700 text-sm font-semibold"
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
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                    @keyup.enter="applyCoupon"
                    :disabled="applyingCoupon"
                  />
                  <button
                    @click="applyCoupon"
                    :disabled="applyingCoupon || !couponCode"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-semibold"
                  >
                    {{ applyingCoupon ? 'Verificando...' : 'Aplicar' }}
                  </button>
                </div>

                <!-- Coupon Error -->
                <p v-if="couponError" class="text-red-600 text-xs mt-2">
                  {{ couponError }}
                </p>
              </div>

              <!-- Totals -->
              <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Subtotal</span>
                  <span class="font-semibold">${{ formatPrice(cartStore.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">IVA (19%)</span>
                  <span class="font-semibold">${{ formatPrice(cartStore.tax) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Envío</span>
                  <span v-if="shippingCost === 0" class="text-green-600 font-semibold">
                    <span v-if="form.nightDelivery">¡Gratis! 🌙 Entrega nocturna</span>
                    <span v-else>¡Gratis! (+{{ freeShippingMinItems - 1 }} artículos)</span>
                  </span>
                  <span v-else class="font-semibold">
                    ${{ formatPrice(shippingCost) }}
                  </span>
                </div>

                <!-- Shipping Info -->
                <div v-if="shippingCost === 0 && form.nightDelivery" class="text-xs text-purple-700 bg-purple-50 p-2 rounded border border-purple-200">
                  🌙 <strong>Entrega nocturna seleccionada</strong> - Tu pedido llegará entre {{ nightDeliveryTime.start }} y {{ nightDeliveryTime.end }}
                </div>
                <div v-else-if="shippingCost > 0 && isBogota && cartStore.itemCount < freeShippingMinItems" class="text-xs text-gray-600 bg-gray-50 p-2 rounded">
                  💡 <strong>Opciones de envío gratis:</strong><br>
                  • Agrega {{ freeShippingMinItems - cartStore.itemCount }} artículo(s) más<br>
                  <span v-if="nightDeliveryEnabled">• O selecciona entrega nocturna ({{ nightDeliveryTime.startShort }}-{{ nightDeliveryTime.endShort }})</span>
                </div>
                <div v-else-if="shippingCost > 0 && !isBogota" class="text-xs text-gray-600 bg-gray-50 p-2 rounded">
                  ℹ️ Envío gratis disponible solo en Bogotá
                </div>

                <div v-if="cartStore.hasCoupon" class="flex justify-between text-sm text-green-600">
                  <span class="font-semibold">Descuento</span>
                  <span class="font-bold">-${{ formatPrice(cartStore.discount) }}</span>
                </div>
              </div>

              <!-- Total -->
              <div class="border-t pt-4 mb-6">
                <div class="flex justify-between items-center">
                  <span class="text-xl font-bold">Total</span>
                  <span class="text-3xl font-bold text-primary">
                    ${{ formatPrice(orderTotal) }}
                  </span>
                </div>
              </div>

              <!-- Place Order Button -->
              <button
                @click="placeOrder"
                :disabled="processing || !form.acceptTerms"
                class="btn-primary w-full text-lg"
                :class="{ 'opacity-50 cursor-not-allowed': processing || !form.acceptTerms }"
              >
                {{ processing ? 'Procesando...' : 'Realizar Pedido' }}
              </button>

              <!-- Security Icons -->
              <div class="mt-6 pt-6 border-t">
                <p class="text-xs text-gray-600 text-center mb-3">🔒 Pago 100% Seguro</p>
                <div class="flex justify-center items-center gap-3 text-xs text-gray-600">
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
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cartStore'
import { useToast } from 'vue-toastification'
import { useFormat } from '@/composables/useFormat'
import orderService from '../services/orderService'
import AddressMapPicker from '../components/AddressMapPicker.vue'

const router = useRouter()
const cartStore = useCartStore()
const toast = useToast()
const { formatPrice, formatDate } = useFormat()

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
  city: '',
  state: '',
  zipCode: '',
  latitude: null,
  longitude: null,
  notes: '',
  nightDelivery: false,

  // Payment
  paymentMethod: 'card',
  cardNumber: '',
  cardExpiry: '',
  cardCvv: '',
  cardName: '',

  // Terms
  acceptTerms: false
})

const formatCardNumber = (event) => {
  let value = event.target.value.replace(/\s/g, '')
  let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value
  form.cardNumber = formattedValue
}

const formatExpiry = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length >= 2) {
    value = value.slice(0, 2) + '/' + value.slice(2, 4)
  }
  form.cardExpiry = value
}

// Handle address update from map picker
const handleAddressUpdate = (addressData) => {
  form.address = addressData.address
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

  if (form.paymentMethod === 'card') {
    if (!form.cardNumber || !form.cardExpiry || !form.cardCvv || !form.cardName) {
      toast.warning('Por favor completa todos los datos de la tarjeta')
      return false
    }
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
        city: form.city,
        state: form.state,
        zipCode: form.zipCode,
        latitude: form.latitude,
        longitude: form.longitude,
        notes: form.notes,
        nightDelivery: form.nightDelivery
      },
      payment: {
        method: form.paymentMethod,
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

    // Limpiar carrito
    cartStore.clearCart()

    // Mostrar mensaje de éxito
    toast.success(`¡Pedido realizado exitosamente! Número de orden: ${order.order_number}. Recibirás un email de confirmación pronto.`, {
      timeout: 6000
    })

    // Redirigir a página principal
    router.push('/')

  } catch (error) {
    console.error('Error al procesar el pedido:', error)

    // Mostrar mensaje de error más específico
    const errorMessage = error.response?.data?.message || 'Hubo un error al procesar tu pedido. Por favor intenta nuevamente.'
    toast.error(errorMessage)
  } finally {
    processing.value = false
  }
}
</script>
