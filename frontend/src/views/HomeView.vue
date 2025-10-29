<template>
  <div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-primary-light dark:from-primary-dark dark:to-primary text-white py-20">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl">
          <h1 class="text-5xl font-bold mb-4">
            Más detalles para consentirlos
          </h1>
          <p class="text-xl mb-8 text-white text-opacity-90">
            Encuentra los mejores productos para el cuidado, alimentación y diversión de tus compañeros peludos
          </p>
          <router-link to="/products" class="btn-secondary inline-block">
            Ver Productos
          </router-link>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Compra por Categoría</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="category in parentCategories"
            :key="category.id"
            class="card cursor-pointer"
            @click="goToCategory(category.slug)"
          >
            <div class="aspect-square bg-beige dark:bg-gray-700 flex items-center justify-center">
              <span class="text-6xl">
                {{ getCategoryIcon(category.name) }}
              </span>
            </div>
            <div class="p-6 text-center">
              <h3 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">{{ category.name }}</h3>
              <p class="text-gray-600 dark:text-gray-300 text-sm">{{ category.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Productos Destacados</h2>
          <router-link to="/products" class="text-primary dark:text-primary-light hover:text-primary-dark dark:hover:text-primary font-semibold">
            Ver todos →
          </router-link>
        </div>

        <!-- Loading -->
        <div v-if="productStore.loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary dark:border-primary-light"></div>
          <p class="mt-4 text-gray-600 dark:text-gray-300">Cargando productos...</p>
        </div>

        <!-- Error -->
        <div v-else-if="productStore.error" class="text-center py-12">
          <p class="text-red-600 dark:text-red-400">{{ productStore.error }}</p>
        </div>

        <!-- Products Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard
            v-for="product in productStore.featuredProducts"
            :key="product.id"
            :product="product"
          />
        </div>

        <div v-if="!productStore.loading && productStore.featuredProducts.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-300">No hay productos destacados disponibles</p>
        </div>
      </div>
    </section>

    <!-- Benefits -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-4xl mb-4">🚚</div>
            <h3 class="font-bold mb-2 text-gray-900 dark:text-white">Envío Gratis</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">En compras superiores a $100.000</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">💳</div>
            <h3 class="font-bold mb-2 text-gray-900 dark:text-white">Pago Seguro</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Múltiples métodos de pago</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">🔄</div>
            <h3 class="font-bold mb-2 text-gray-900 dark:text-white">Devoluciones</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">30 días para devoluciones</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">💬</div>
            <h3 class="font-bold mb-2 text-gray-900 dark:text-white">Soporte 24/7</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Estamos aquí para ayudarte</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'
import { useCategoryStore } from '../stores/categoryStore'
import ProductCard from '../components/product/ProductCard.vue'

const router = useRouter()
const productStore = useProductStore()
const categoryStore = useCategoryStore()

const parentCategories = computed(() => categoryStore.parentCategories)

const getCategoryIcon = (name) => {
  const icons = {
    'Perros': '🐕',
    'Gatos': '🐈',
    'Accesorios': '🎾'
  }
  return icons[name] || '🐾'
}

const goToCategory = (slug) => {
  router.push(`/category/${slug}`)
}

onMounted(() => {
  productStore.fetchFeaturedProducts(8)
  categoryStore.fetchCategories()
})
</script>
