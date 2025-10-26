<template>
  <div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
      <!-- Breadcrumb -->
      <nav class="text-sm mb-8">
        <ol class="flex items-center space-x-2 text-gray-600">
          <li><router-link to="/" class="hover:text-primary">Inicio</router-link></li>
          <li>/</li>
          <li><router-link to="/products" class="hover:text-primary">Productos</router-link></li>
          <li v-if="product">/</li>
          <li v-if="product" class="text-primary font-semibold">{{ product.name }}</li>
        </ol>
      </nav>

      <!-- Loading -->
      <div v-if="productStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando producto...</p>
      </div>

      <!-- Error -->
      <div v-else-if="productStore.error" class="text-center py-12">
        <div class="text-6xl mb-4">😞</div>
        <h2 class="text-2xl font-bold mb-2">Producto no encontrado</h2>
        <p class="text-gray-600 mb-6">{{ productStore.error }}</p>
        <router-link to="/products" class="btn-primary">
          Ver todos los productos
        </router-link>
      </div>

      <!-- Product Detail -->
      <div v-else-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Images -->
        <div>
          <!-- Main Image -->
          <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
            <img
              :src="selectedImage || product.primary_image?.image_url || 'https://via.placeholder.com/600'"
              :alt="product.name"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Image Gallery -->
          <div v-if="product.images && product.images.length > 1" class="grid grid-cols-4 gap-2">
            <div
              v-for="image in product.images"
              :key="image.id"
              @click="selectedImage = image.image_url"
              class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 transition"
              :class="selectedImage === image.image_url ? 'border-primary' : 'border-transparent hover:border-gray-300'"
            >
              <img
                :src="image.image_url"
                :alt="product.name"
                class="w-full h-full object-cover"
              />
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div>
          <!-- Category -->
          <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">
            {{ product.category?.name }}
          </p>

          <!-- Title -->
          <h1 class="text-4xl font-bold mb-4">{{ product.name }}</h1>

          <!-- Rating -->
          <div class="mb-6">
            <StarRating
              :model-value="product.average_rating || 0"
              :show-rating="true"
              :total-reviews="reviewStats?.total_reviews || 0"
              size="lg"
            />
          </div>

          <!-- Price -->
          <div class="mb-6">
            <div v-if="product.has_discount" class="flex items-center gap-4">
              <span class="text-gray-400 line-through text-2xl">
                ${{ formatPrice(product.price) }}
              </span>
              <span class="text-primary font-bold text-4xl">
                ${{ formatPrice(product.sale_price) }}
              </span>
              <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-bold">
                -{{ product.discount_percentage }}%
              </span>
            </div>
            <div v-else>
              <span class="text-primary font-bold text-4xl">
                ${{ formatPrice(product.price) }}
              </span>
            </div>
          </div>

          <!-- Stock Status -->
          <div class="mb-6">
            <div v-if="product.in_stock" class="flex items-center text-green-600">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <span class="font-semibold">En Stock - {{ product.stock }} disponibles</span>
            </div>
            <div v-else class="flex items-center text-red-600">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <span class="font-semibold">Agotado</span>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-8">
            <h3 class="font-bold text-lg mb-2">Descripción</h3>
            <p class="text-gray-700 leading-relaxed">{{ product.description }}</p>
          </div>

          <!-- Quantity & Add to Cart -->
          <div v-if="product.in_stock" class="flex items-center gap-4 mb-8">
            <div class="flex items-center border border-gray-300 rounded-lg">
              <button
                @click="quantity > 1 ? quantity-- : null"
                class="px-4 py-2 hover:bg-gray-100 transition"
                :disabled="quantity <= 1"
              >
                -
              </button>
              <input
                v-model.number="quantity"
                type="number"
                min="1"
                :max="product.stock"
                class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
              />
              <button
                @click="quantity < product.stock ? quantity++ : null"
                class="px-4 py-2 hover:bg-gray-100 transition"
                :disabled="quantity >= product.stock"
              >
                +
              </button>
            </div>

            <button
              @click="addToCart"
              class="btn-primary flex-1 text-lg py-3"
            >
              🛒 Agregar al Carrito
            </button>

            <!-- Wishlist Button -->
            <button
              v-if="authStore.isAuthenticated"
              @click="toggleWishlist"
              class="border-2 px-6 py-3 rounded-lg transition-all hover:scale-105"
              :class="isInWishlist ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-red-500'"
              :title="isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos'"
            >
              <svg class="w-6 h-6" :class="isInWishlist ? 'text-red-500' : 'text-gray-600'" :fill="isInWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <!-- Wishlist Button (when out of stock) -->
          <div v-else-if="authStore.isAuthenticated" class="mb-8">
            <button
              @click="toggleWishlist"
              class="w-full border-2 px-6 py-3 rounded-lg transition-all flex items-center justify-center gap-2"
              :class="isInWishlist ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-300 hover:border-red-500 text-gray-700'"
            >
              <svg class="w-6 h-6" :fill="isInWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span class="font-semibold">
                {{ isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos' }}
              </span>
            </button>
          </div>

          <!-- Product Details -->
          <div class="border-t pt-6">
            <h3 class="font-bold text-lg mb-4">Detalles del Producto</h3>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="text-gray-600 w-32">SKU:</dt>
                <dd class="font-semibold">{{ product.sku }}</dd>
              </div>
              <div class="flex" v-if="product.weight">
                <dt class="text-gray-600 w-32">Peso:</dt>
                <dd class="font-semibold">{{ product.weight }} kg</dd>
              </div>
              <div class="flex">
                <dt class="text-gray-600 w-32">Categoría:</dt>
                <dd>
                  <router-link
                    :to="`/category/${product.category?.slug}`"
                    class="text-primary hover:underline font-semibold"
                  >
                    {{ product.category?.name }}
                  </router-link>
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <section v-if="product" class="mt-16">
        <div class="bg-white rounded-lg shadow-lg p-8">
          <!-- Review Stats -->
          <div v-if="reviewStats" class="mb-8 pb-8 border-b">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- Average Rating -->
              <div class="text-center">
                <div class="text-5xl font-bold text-gray-900 mb-2">
                  {{ reviewStats.average_rating || 0 }}
                </div>
                <StarRating
                  :model-value="reviewStats.average_rating || 0"
                  size="lg"
                  class="justify-center mb-2"
                />
                <p class="text-gray-600">
                  Basado en {{ reviewStats.total_reviews }} reseñas
                </p>
              </div>

              <!-- Rating Distribution -->
              <div class="space-y-2">
                <div
                  v-for="rating in [5, 4, 3, 2, 1]"
                  :key="rating"
                  class="flex items-center gap-3"
                >
                  <span class="text-sm font-medium w-8">{{ rating }} ★</span>
                  <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div
                      class="bg-yellow-400 h-full rounded-full transition-all"
                      :style="{
                        width: reviewStats.total_reviews > 0
                          ? `${(reviewStats.rating_distribution[rating] / reviewStats.total_reviews) * 100}%`
                          : '0%'
                      }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-600 w-12 text-right">
                    {{ reviewStats.rating_distribution[rating] }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Reviews List -->
          <ReviewsList
            :reviews="reviews"
            :pagination="reviewsPagination"
            :loading="loadingReviews"
            :can-write-review="canWriteReview"
            @write-review="openReviewForm()"
            @edit-review="openReviewForm"
            @delete-review="confirmDeleteReview"
            @page-change="loadReviews"
          />
        </div>
      </section>

      <!-- Related Products -->
      <section v-if="product" class="mt-16">
        <h2 class="text-3xl font-bold mb-8">Productos Relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- TODO: Implementar productos relacionados -->
          <p class="text-gray-600 col-span-full text-center py-8">
            Próximamente verás productos relacionados aquí
          </p>
        </div>
      </section>

      <!-- Review Form Modal -->
      <ReviewForm
        v-model="showReviewForm"
        :review="editingReview"
        :product="product"
        @submit="handleReviewSubmit"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'
import { useCartStore } from '../stores/cartStore'
import { useWishlistStore } from '../stores/wishlistStore'
import { useAuthStore } from '../stores/authStore'
import { useToast } from 'vue-toastification'
import StarRating from '../components/StarRating.vue'
import ReviewsList from '../components/ReviewsList.vue'
import ReviewForm from '../components/ReviewForm.vue'
import reviewService from '../services/reviewService'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const authStore = useAuthStore()
const toast = useToast()

const quantity = ref(1)
const selectedImage = ref(null)

// Reviews state
const reviews = ref([])
const reviewStats = ref(null)
const reviewsPagination = ref(null)
const loadingReviews = ref(false)
const showReviewForm = ref(false)
const editingReview = ref(null)

const product = computed(() => productStore.currentProduct)
const isInWishlist = computed(() => {
  return product.value ? wishlistStore.isInWishlist(product.value.id) : false
})

const canWriteReview = computed(() => {
  if (!authStore.isAuthenticated || !reviews.value) return false
  // Check if user has already reviewed this product
  const userReview = reviews.value.find(r => r.user_id === authStore.user?.id)
  return !userReview
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const addToCart = () => {
  try {
    cartStore.addItem(product.value, quantity.value)

    // Mostrar toast de éxito con opción de ir al carrito
    toast.success(`${quantity.value} x ${product.value.name} agregado al carrito`, {
      timeout: 3000,
      onClick: () => router.push('/cart')
    })

    // Reset quantity
    quantity.value = 1
  } catch (error) {
    toast.error(error.message)
  }
}

const toggleWishlist = async () => {
  const result = await wishlistStore.toggleWishlist(product.value)
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

const loadProduct = async () => {
  const slug = route.params.slug
  await productStore.fetchProductBySlug(slug)

  // Set default image
  if (product.value?.primary_image) {
    selectedImage.value = product.value.primary_image.image_url
  }

  // Load reviews when product is loaded
  if (product.value) {
    loadReviews()
    loadReviewStats()
  }
}

// Reviews methods
const loadReviews = async (page = 1) => {
  if (!product.value) return

  loadingReviews.value = true
  try {
    const response = await reviewService.getProductReviews(product.value.id, {
      page,
      per_page: 10
    })

    reviews.value = response.data.data
    reviewsPagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total
    }
  } catch (error) {
    console.error('Error loading reviews:', error)
    toast.error('Error al cargar las reseñas')
  } finally {
    loadingReviews.value = false
  }
}

const loadReviewStats = async () => {
  if (!product.value) return

  try {
    const response = await reviewService.getReviewStats(product.value.id)
    reviewStats.value = response.data
  } catch (error) {
    console.error('Error loading review stats:', error)
  }
}

const openReviewForm = (review = null) => {
  if (!authStore.isAuthenticated) {
    toast.warning('Debes iniciar sesión para escribir una reseña')
    router.push({ name: 'login', query: { redirect: route.fullPath } })
    return
  }

  editingReview.value = review
  showReviewForm.value = true
}

const handleReviewSubmit = async (reviewData) => {
  try {
    if (editingReview.value) {
      // Update existing review
      await reviewService.update(editingReview.value.id, reviewData)
      toast.success('Reseña actualizada exitosamente')
    } else {
      // Create new review
      await reviewService.create({
        product_id: product.value.id,
        ...reviewData
      })
      toast.success('Reseña publicada exitosamente')
    }

    // Reload reviews and stats
    await loadReviews()
    await loadReviewStats()

    // Close form
    showReviewForm.value = false
    editingReview.value = null
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'Error al enviar la reseña'
    toast.error(errorMessage)
    throw new Error(errorMessage)
  }
}

const confirmDeleteReview = async (review) => {
  if (!confirm('¿Estás seguro de que deseas eliminar esta reseña?')) {
    return
  }

  try {
    await reviewService.delete(review.id)
    toast.success('Reseña eliminada exitosamente')

    // Reload reviews and stats
    await loadReviews()
    await loadReviewStats()
  } catch (error) {
    toast.error('Error al eliminar la reseña')
  }
}

// Watch for route changes
watch(() => route.params.slug, () => {
  if (route.params.slug) {
    loadProduct()
  }
})

onMounted(() => {
  loadProduct()
})
</script>
