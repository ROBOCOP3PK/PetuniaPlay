<template>
  <div class="min-h-screen py-8 bg-gray-50">
    <div class="container mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Carrito de Compras</h1>

      <!-- Empty Cart -->
      <div v-if="!cartStore.hasItems" class="bg-white rounded-lg shadow-md p-12 text-center">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold mb-2">Tu carrito está vacío</h2>
        <p class="text-gray-600 mb-6">
          Agrega productos a tu carrito para continuar comprando
        </p>
        <router-link to="/products" class="btn-primary inline-block">
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
            class="bg-white rounded-lg shadow-md p-6 flex gap-6"
          >
            <!-- Product Image -->
            <router-link
              :to="`/product/${item.product.slug}`"
              class="flex-shrink-0"
            >
              <img
                :src="item.product.primary_image?.image_url || 'https://via.placeholder.com/150'"
                :alt="item.product.name"
                class="w-32 h-32 object-cover rounded-lg"
              />
            </router-link>

            <!-- Product Info -->
            <div class="flex-1">
              <router-link
                :to="`/product/${item.product.slug}`"
                class="text-lg font-bold hover:text-primary transition mb-2 block"
              >
                {{ item.product.name }}
              </router-link>

              <p class="text-sm text-gray-600 mb-2">SKU: {{ item.product.sku }}</p>

              <!-- Price -->
              <div class="mb-4">
                <div v-if="item.product.has_discount" class="flex items-center gap-2">
                  <span class="text-gray-400 line-through text-sm">
                    ${{ formatPrice(item.product.price) }}
                  </span>
                  <span class="text-primary font-bold text-xl">
                    ${{ formatPrice(item.product.final_price) }}
                  </span>
                  <span class="bg-primary text-white px-2 py-1 rounded text-xs">
                    -{{ item.product.discount_percentage }}%
                  </span>
                </div>
                <div v-else>
                  <span class="text-primary font-bold text-xl">
                    ${{ formatPrice(item.product.price) }}
                  </span>
                </div>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center gap-4">
                <div class="flex items-center border border-gray-300 rounded-lg">
                  <button
                    @click="decreaseQuantity(item.product.id)"
                    class="px-3 py-1 hover:bg-gray-100 transition"
                  >
                    -
                  </button>
                  <input
                    :value="item.quantity"
                    @input="updateQuantity(item.product.id, $event.target.value)"
                    type="number"
                    min="1"
                    :max="item.product.stock"
                    class="w-16 text-center border-x border-gray-300 py-1 focus:outline-none"
                  />
                  <button
                    @click="increaseQuantity(item.product.id)"
                    class="px-3 py-1 hover:bg-gray-100 transition"
                  >
                    +
                  </button>
                </div>

                <button
                  @click="removeItem(item.product.id)"
                  class="text-red-600 hover:text-red-800 font-semibold transition"
                >
                  🗑️ Eliminar
                </button>
              </div>
            </div>

            <!-- Item Total -->
            <div class="text-right">
              <p class="text-sm text-gray-600 mb-1">Subtotal</p>
              <p class="text-2xl font-bold text-primary">
                ${{ formatPrice(parseFloat(item.product.final_price) * item.quantity) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
            <h2 class="text-2xl font-bold mb-6">Resumen del Pedido</h2>

            <!-- Summary Items -->
            <div class="space-y-3 mb-6">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal ({{ cartStore.itemCount }} items)</span>
                <span class="font-semibold">${{ formatPrice(cartStore.subtotal) }}</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-600">IVA (19%)</span>
                <span class="font-semibold">${{ formatPrice(cartStore.tax) }}</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-600">Envío</span>
                <span v-if="cartStore.shipping === 0" class="text-green-600 font-semibold">
                  ¡Gratis!
                </span>
                <span v-else class="font-semibold">
                  ${{ formatPrice(cartStore.shipping) }}
                </span>
              </div>

              <div v-if="cartStore.shipping === 0" class="text-xs text-green-600">
                ✅ Envío gratis en compras mayores a $100,000
              </div>
            </div>

            <!-- Total -->
            <div class="border-t pt-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold">Total</span>
                <span class="text-3xl font-bold text-primary">
                  ${{ formatPrice(cartStore.total) }}
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
              <router-link to="/checkout" class="btn-primary w-full block text-center text-lg">
                Proceder al Pago
              </router-link>

              <router-link to="/products" class="btn-outline w-full block text-center">
                Continuar Comprando
              </router-link>

              <button
                @click="clearCart"
                class="w-full text-red-600 hover:text-red-800 font-semibold transition py-2"
              >
                Vaciar Carrito
              </button>
            </div>

            <!-- Security Icons -->
            <div class="mt-6 pt-6 border-t">
              <p class="text-xs text-gray-600 text-center mb-3">Compra 100% Segura</p>
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
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cartStore'
import { useToast } from 'vue-toastification'

const router = useRouter()
const cartStore = useCartStore()
const toast = useToast()

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const increaseQuantity = (productId) => {
  const item = cartStore.items.find(i => i.product.id === productId)
  if (item) {
    try {
      cartStore.updateQuantity(productId, item.quantity + 1)
    } catch (error) {
      toast.error(error.message)
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
    toast.error(error.message)
  }
}

const removeItem = (productId) => {
  if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
    cartStore.removeItem(productId)
  }
}

const clearCart = () => {
  if (confirm('¿Estás seguro de vaciar todo el carrito?')) {
    cartStore.clearCart()
  }
}
</script>
