<template>
  <div class="card group cursor-pointer" @click="goToProduct">
    <!-- Image -->
    <div class="relative overflow-hidden aspect-square">
      <img
        :src="product.primary_image?.image_url || 'https://via.placeholder.com/400'"
        :alt="product.name"
        loading="lazy"
        decoding="async"
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
          class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold flex items-center gap-1"
        >
          <i class="pi pi-star-fill"></i>
          Destacado
        </span>
      </div>

      <!-- Wishlist Button -->
      <button
        v-if="authStore.isAuthenticated"
        @click.stop="toggleWishlist"
        class="absolute top-2 right-2 bg-white dark:bg-gray-800 p-2 rounded-full shadow-md hover:scale-110 transition-transform"
        :title="isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos'"
      >
        <i class="text-xl" :class="isInWishlist ? 'pi pi-heart-fill text-red-500' : 'pi pi-heart text-gray-400 dark:text-gray-500'"></i>
      </button>

      <!-- Out of stock overlay -->
      <div
        v-if="!product.in_stock"
        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      >
        <span class="bg-white dark:bg-gray-800 text-dark dark:text-white px-4 py-2 rounded-lg font-bold">
          Agotado
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <!-- Category -->
      <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
        {{ product.category?.name }}
      </p>

      <!-- Title -->
      <h3 class="font-bold text-lg mb-2 line-clamp-2 text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-primary-light transition">
        {{ product.name }}
      </h3>

      <!-- Description -->
      <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
        {{ product.description }}
      </p>

      <!-- Rating -->
      <div class="flex items-center mb-3">
        <div class="flex text-yellow-500 dark:text-yellow-400">
          <i
            v-for="star in 5"
            :key="star"
            :class="star <= Math.round(product.average_rating) ? 'pi pi-star-fill' : 'pi pi-star'"
          ></i>
        </div>
        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
          ({{ product.average_rating || 0 }})
        </span>
      </div>

      <!-- Price -->
      <div class="flex items-center justify-between">
        <div>
          <div v-if="product.has_discount" class="flex items-center gap-2">
            <span class="text-gray-400 dark:text-gray-500 line-through text-sm">
              ${{ formatPrice(product.price) }}
            </span>
            <span class="text-primary dark:text-fuchsia-400 font-bold text-xl">
              ${{ formatPrice(product.sale_price) }}
            </span>
          </div>
          <div v-else>
            <span class="text-primary dark:text-fuchsia-400 font-bold text-xl">
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
          <i class="pi pi-shopping-cart text-xl"></i>
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
import { useNotification } from '@/composables/useNotification'
import { useFormat } from '@/composables/useFormat'

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
const { notifyAddedToCart, notifySuccess, notifyError, notifyInfo } = useNotification()
const { formatPrice, formatDate } = useFormat()

const isInWishlist = computed(() => wishlistStore.isInWishlist(props.product.id))

const goToProduct = () => {
  router.push(`/product/${props.product.slug}`)
}

const addToCart = () => {
  try {
    cartStore.addItem(props.product, 1)
    // Mostrar notificación de éxito
    notifyAddedToCart(props.product.name, 1)
  } catch (error) {
    // Mostrar error (ej. sin stock)
    notifyError(error.message)
  }
}

const toggleWishlist = async () => {
  const result = await wishlistStore.toggleWishlist(props.product)
  if (result.success) {
    if (isInWishlist.value) {
      notifySuccess('Agregado a favoritos')
    } else {
      notifyInfo('Eliminado de favoritos')
    }
  } else {
    notifyError(result.message)
  }
}
</script>
