<template>
  <div class="min-h-screen py-8 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8 text-gray-900 dark:text-white">Carrito de Compras</h1>

      <!-- Confirm Dialog -->
      <ConfirmDialog
        v-model:isOpen="confirmDialog.isOpen"
        :title="confirmDialog.title"
        :message="confirmDialog.message"
        :type="confirmDialog.type"
        :confirm-text="confirmDialog.confirmText"
        :cancel-text="confirmDialog.cancelText"
        @confirm="confirmDialog.onConfirm"
        @cancel="confirmDialog.onCancel"
      />

      <!-- Empty Cart -->
      <div v-if="!cartStore.hasItems" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Tu carrito está vacío</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
          Agrega productos a tu carrito para continuar comprando
        </p>
        <router-link to="/products" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition inline-block">
          Explorar Productos
        </router-link>
      </div>

      <!-- Cart with Items -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.product.id"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 flex gap-6"
          >
            <!-- Product Image -->
            <router-link
              :to="`/product/${item.product.slug}`"
              class="flex-shrink-0"
            >
              <img
                :src="item.product.primary_image?.image_url || 'https://via.placeholder.com/150'"
                :alt="item.product.name"
                loading="lazy"
                decoding="async"
                class="w-32 h-32 object-cover rounded-lg"
              />
            </router-link>

            <!-- Product Info -->
            <div class="flex-1">
              <router-link
                :to="`/product/${item.product.slug}`"
                class="text-lg font-bold text-gray-900 dark:text-white hover:text-primary transition mb-2 block"
              >
                {{ item.product.name }}
              </router-link>

              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">SKU: {{ item.product.sku }}</p>

              <!-- Price -->
              <div class="mb-4">
                <div v-if="item.product.has_discount" class="flex items-center gap-2">
                  <span class="text-gray-500 dark:text-gray-400 line-through text-sm">
                    ${{ formatPrice(item.product.price) }}
                  </span>
                  <span class="text-primary dark:text-white font-bold text-xl">
                    ${{ formatPrice(item.product.final_price) }}
                  </span>
                  <span class="bg-primary text-white px-2 py-1 rounded text-xs">
                    -{{ item.product.discount_percentage }}%
                  </span>
                </div>
                <div v-else>
                  <span class="text-primary dark:text-white font-bold text-xl">
                    ${{ formatPrice(item.product.price) }}
                  </span>
                </div>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center gap-4">
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                  <button
                    @click="decreaseQuantity(item.product.id)"
                    class="px-3 py-1 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                  >
                    -
                  </button>
                  <input
                    :value="item.quantity"
                    @input="updateQuantity(item.product.id, $event.target.value)"
                    type="number"
                    min="1"
                    :max="item.product.stock"
                    class="w-16 text-center border-x border-gray-300 dark:border-gray-600 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none"
                  />
                  <button
                    @click="increaseQuantity(item.product.id)"
                    class="px-3 py-1 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                  >
                    +
                  </button>
                </div>

                <button
                  @click="removeItem(item.product.id)"
                  class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition"
                >
                  Eliminar
                </button>
              </div>
            </div>

            <!-- Item Total -->
            <div class="text-right">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Subtotal</p>
              <p class="text-2xl font-bold text-primary dark:text-white">
                ${{ formatPrice((parseFloat(item.product.final_price || item.product.sale_price || item.product.price || 0)) * item.quantity) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-24">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Resumen del Pedido</h2>

            <!-- Summary Items -->
            <div class="space-y-3 mb-6">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Subtotal ({{ cartStore.itemCount }} items)</span>
                <span class="font-semibold text-gray-900 dark:text-white">${{ formatPrice(cartStore.subtotal) }}</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">IVA (19%)</span>
                <span class="font-semibold text-gray-900 dark:text-white">${{ formatPrice(cartStore.tax) }}</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Envío</span>
                <span class="font-semibold text-gray-900 dark:text-white">
                  A calcular
                </span>
              </div>

              <div class="text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 p-2 rounded">
                💡 <strong>Envío gratis en Bogotá</strong> al comprar más de 3 artículos
              </div>
            </div>

            <!-- Total -->
            <div class="border-t dark:border-gray-700 pt-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-gray-900 dark:text-white">Total</span>
                <span class="text-3xl font-bold text-primary dark:text-white">
                  ${{ formatPrice(cartStore.total) }}
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
              <router-link to="/checkout" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition w-full block text-center text-lg">
                Proceder al Pago
              </router-link>

              <router-link to="/products" class="w-full block text-center px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-semibold transition">
                Continuar Comprando
              </router-link>

              <button
                @click="clearCart"
                class="w-full text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition py-2"
              >
                Vaciar Carrito
              </button>
            </div>

            <!-- Security Icons -->
            <div class="mt-6 pt-6 border-t dark:border-gray-700">
              <p class="text-xs text-gray-600 dark:text-gray-400 text-center mb-3">Compra 100% Segura</p>
              <div class="flex justify-center items-center gap-4 text-2xl">
                <span>🔒</span>
                <span>💳</span>
                <span>✅</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '../stores/cartStore'
import { useNotification } from '@/composables/useNotification'
import { useConfirm } from '@/composables/useConfirm'
import { useFormat } from '@/composables/useFormat'
import ConfirmDialog from '../components/ConfirmDialog.vue'

const cartStore = useCartStore()
const { notifySuccess, notifyError } = useNotification()
const { confirmDialog, showConfirm } = useConfirm()
const { formatPrice, formatDate } = useFormat()

const increaseQuantity = (productId) => {
  const item = cartStore.items.find(i => i.product.id === productId)
  if (item) {
    try {
      cartStore.updateQuantity(productId, item.quantity + 1)
    } catch (error) {
      notifyError(error.message)
    }
  }
}

const decreaseQuantity = (productId) => {
  const item = cartStore.items.find(i => i.product.id === productId)
  if (item && item.quantity > 1) {
    cartStore.updateQuantity(productId, item.quantity - 1)
  } else {
    removeItem(productId)
  }
}

const updateQuantity = (productId, value) => {
  const quantity = parseInt(value)
  if (isNaN(quantity) || quantity < 1) return

  try {
    cartStore.updateQuantity(productId, quantity)
  } catch (error) {
    notifyError(error.message)
  }
}

const removeItem = async (productId) => {
  const confirmed = await showConfirm({
    title: '¿Eliminar producto?',
    message: '¿Estás seguro de eliminar este producto del carrito?',
    type: 'warning',
    confirmText: 'Sí, eliminar',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    cartStore.removeItem(productId)
    notifySuccess('Producto eliminado del carrito')
  }
}

const clearCart = async () => {
  const confirmed = await showConfirm({
    title: '¿Vaciar carrito?',
    message: '¿Estás seguro de vaciar todo el carrito? Esta acción eliminará todos los productos.',
    type: 'danger',
    confirmText: 'Sí, vaciar todo',
    cancelText: 'Cancelar'
  })

  if (confirmed) {
    cartStore.clearCart()
    notifySuccess('Carrito vaciado exitosamente')
  }
}
</script>
