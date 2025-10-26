<template>
  <div class="card group cursor-pointer" @click="goToProduct">
    <!-- Image -->
    <div class="relative overflow-hidden aspect-square">
      <img
        :src="product.primary_image?.image_url || 'https://via.placeholder.com/400'"
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
      />

      <!-- Badges -->
      <div class="absolute top-2 left-2 flex flex-col gap-2">
        <span
          v-if="product.has_discount"
          class="bg-primary text-white px-2 py-1 rounded text-xs font-bold"
        >
          -{{ product.discount_percentage }}%
        </span>
        <span
          v-if="product.is_featured"
          class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold"
        >
          ⭐ Destacado
        </span>
      </div>

      <!-- Wishlist Button -->
      <button
        v-if="authStore.isAuthenticated"
        @click.stop="toggleWishlist"
        class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:scale-110 transition-transform"
        :title="isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos'"
      >
        <svg class="w-5 h-5" :class="isInWishlist ? 'text-red-500' : 'text-gray-400'" :fill="isInWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
      </button>

      <!-- Out of stock overlay -->
      <div
        v-if="!product.in_stock"
        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      >
        <span class="bg-white text-dark px-4 py-2 rounded-lg font-bold">
          Agotado
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <!-- Category -->
      <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">
        {{ product.category?.name }}
      </p>

      <!-- Title -->
      <h3 class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-primary transition">
        {{ product.name }}
      </h3>

      <!-- Description -->
      <p class="text-sm text-gray-600 mb-3 line-clamp-2">
        {{ product.description }}
      </p>

      <!-- Rating -->
      <div class="flex items-center mb-3">
        <div class="flex text-yellow-500">
          <span v-for="star in 5" :key="star">
            {{ star <= Math.round(product.average_rating) ? '★' : '☆' }}
          </span>
        </div>
        <span class="text-xs text-gray-500 ml-2">
          ({{ product.average_rating || 0 }})
        </span>
      </div>

      <!-- Price -->
      <div class="flex items-center justify-between">
        <div>
          <div v-if="product.has_discount" class="flex items-center gap-2">
            <span class="text-gray-400 line-through text-sm">
              ${{ formatPrice(product.price) }}
            </span>
            <span class="text-primary font-bold text-xl">
              ${{ formatPrice(product.sale_price) }}
            </span>
          </div>
          <div v-else>
            <span class="text-primary font-bold text-xl">
              ${{ formatPrice(product.price) }}
            </span>
          </div>
        </div>

        <!-- Add to cart button -->
        <button
          v-if="product.in_stock"
          @click.stop="addToCart"
          class="bg-primary hover:bg-primary-dark text-white p-2 rounded-lg transition"
          title="Agregar al carrito"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../../stores/cartStore'
import { useWishlistStore } from '../../stores/wishlistStore'
import { useAuthStore } from '../../stores/authStore'
import { useToast } from 'vue-toastification'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const router = useRouter()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useAuthStore()
const toast = useToast()

const isInWishlist = computed(() => wishlistStore.isInWishlist(props.product.id))

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const goToProduct = () => {
  router.push(`/product/${props.product.slug}`)
}

const addToCart = () => {
  try {
    cartStore.addItem(props.product, 1)
    // Mostrar notificación de éxito
    toast.success(`${props.product.name} agregado al carrito`, {
      timeout: 2500
    })
  } catch (error) {
    // Mostrar error (ej. sin stock)
    toast.error(error.message)
  }
}

const toggleWishlist = async () => {
  const result = await wishlistStore.toggleWishlist(props.product)
  if (result.success) {
    if (isInWishlist.value) {
      toast.success('Agregado a favoritos')
    } else {
      toast.info('Eliminado de favoritos')
    }
  } else {
    toast.error(result.message)
  }
}
</script>
