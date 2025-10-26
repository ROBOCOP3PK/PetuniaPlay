<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-dark">Mi Lista de Deseos</h1>
        <button
          v-if="wishlistStore.itemCount > 0"
          @click="handleClearWishlist"
          class="text-red-600 hover:text-red-700 font-semibold transition"
        >
          Limpiar todo
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando lista de deseos...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="wishlistStore.itemCount === 0" class="text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-700 mb-2">Tu lista de deseos está vacía</h2>
        <p class="text-gray-600 mb-6">Agrega productos que te gusten para guardarlos aquí</p>
        <router-link
          to="/products"
          class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-dark transition"
        >
          Explorar Productos
        </router-link>
      </div>

      <!-- Wishlist Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="item in wishlistStore.items"
          :key="item.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow"
        >
          <!-- Product Image -->
          <div class="relative aspect-square overflow-hidden bg-gray-100">
            <router-link :to="`/product/${item.product.slug}`">
              <img
                v-if="item.product.images && item.product.images.length > 0"
                :src="item.product.images[0].url"
                :alt="item.product.name"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                Sin imagen
              </div>
            </router-link>

            <!-- Remove Button -->
            <button
              @click="handleRemove(item.product.id)"
              class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-red-50 transition-colors"
              title="Eliminar de favoritos"
            >
              <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
              </svg>
            </button>

            <!-- Stock Badge -->
            <div v-if="item.product.stock <= 0" class="absolute bottom-2 left-2">
              <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Agotado
              </span>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-4">
            <router-link
              :to="`/product/${item.product.slug}`"
              class="text-lg font-semibold text-dark hover:text-primary transition mb-2 block line-clamp-2"
            >
              {{ item.product.name }}
            </router-link>

            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
              {{ item.product.description }}
            </p>

            <!-- Price and Actions -->
            <div class="flex items-center justify-between">
              <div>
                <p v-if="item.product.sale_price" class="text-2xl font-bold text-primary">
                  ${{ formatPrice(item.product.sale_price) }}
                </p>
                <p v-else class="text-2xl font-bold text-primary">
                  ${{ formatPrice(item.product.price) }}
                </p>
                <p v-if="item.product.sale_price" class="text-sm text-gray-500 line-through">
                  ${{ formatPrice(item.product.price) }}
                </p>
              </div>

              <button
                @click="handleAddToCart(item.product)"
                :disabled="item.product.stock <= 0"
                class="bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary-dark transition disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                {{ item.product.stock > 0 ? 'Agregar' : 'Agotado' }}
              </button>
            </div>

            <!-- Added Date -->
            <p class="text-xs text-gray-500 mt-3">
              Agregado el {{ formatDate(item.added_at) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useWishlistStore } from '../stores/wishlistStore'
import { useCartStore } from '../stores/cartStore'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

const wishlistStore = useWishlistStore()
const cartStore = useCartStore()
const toast = useToast()
const router = useRouter()

const loading = ref(false)

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const handleRemove = async (productId) => {
  const result = await wishlistStore.removeFromWishlist(productId)
  if (result.success) {
    toast.success(result.message)
  } else {
    toast.error(result.message)
  }
}

const handleAddToCart = (product) => {
  cartStore.addItem(product, 1)
  toast.success(`${product.name} agregado al carrito`, {
    timeout: 3000,
    onClick: () => router.push('/cart')
  })
}

const handleClearWishlist = async () => {
  if (confirm('¿Estás seguro de que deseas eliminar todos los productos de tu lista de deseos?')) {
    const result = await wishlistStore.clearWishlist()
    if (result.success) {
      toast.success(result.message)
    } else {
      toast.error(result.message)
    }
  }
}

onMounted(async () => {
  loading.value = true
  await wishlistStore.fetchWishlist()
  loading.value = false
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
