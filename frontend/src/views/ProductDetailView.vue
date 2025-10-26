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
          <div class="flex items-center mb-6">
            <div class="flex text-yellow-500 text-lg">
              <span v-for="star in 5" :key="star">
                {{ star <= Math.round(product.average_rating) ? '★' : '☆' }}
              </span>
            </div>
            <span class="text-gray-600 ml-2">
              ({{ product.average_rating || 0 }} / 5)
            </span>
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
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'
import { useCartStore } from '../stores/cartStore'
import { useToast } from 'vue-toastification'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const cartStore = useCartStore()
const toast = useToast()

const quantity = ref(1)
const selectedImage = ref(null)

const product = computed(() => productStore.currentProduct)

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

const loadProduct = async () => {
  const slug = route.params.slug
  await productStore.fetchProductBySlug(slug)

  // Set default image
  if (product.value?.primary_image) {
    selectedImage.value = product.value.primary_image.image_url
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
