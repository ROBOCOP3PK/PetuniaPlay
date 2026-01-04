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

    <!-- Animal Sections -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4 text-gray-900 dark:text-white">Compra por Tipo de Mascota</h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-12">Encuentra productos especializados para cada compañero</p>

        <!-- Animal Sections Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <div
            v-for="section in animalSections"
            :key="section.id"
            class="card cursor-pointer hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden"
            @click="goToAnimalSection(section.id)"
          >
            <div class="aspect-square bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
              <img
                v-if="section.image_url"
                :src="getImageUrl(section.image_url)"
                :alt="section.name"
                class="w-full h-full object-cover"
                loading="lazy"
              />
              <div v-else class="w-full h-full bg-gradient-to-br from-beige to-cream dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                <span class="text-8xl">
                  {{ section.icon }}
                </span>
              </div>
            </div>
            <div class="p-6 text-center">
              <h3 class="font-bold text-2xl mb-2 text-gray-900 dark:text-white">{{ section.name }}</h3>
              <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ section.description }}</p>
              <span class="text-primary dark:text-fuchsia-400 font-semibold text-sm">
                Ver productos →
              </span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="animalSections.length === 0 && !loadingSections" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400">No hay secciones disponibles</p>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Productos Destacados</h2>
          <router-link to="/products" class="text-primary dark:text-fuchsia-400 hover:text-primary-dark dark:hover:text-primary font-semibold">
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'
import ProductCard from '../components/product/ProductCard.vue'
import animalSectionService from '../services/animalSectionService'

const router = useRouter()
const productStore = useProductStore()

const animalSections = ref([])
const loadingSections = ref(false)

// Get backend base URL
const getBackendBaseUrl = () => {
  const apiUrl = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api/v1'
  return apiUrl.replace('/api/v1', '')
}

// Helper to get full image URL
const getImageUrl = (imageUrl) => {
  if (!imageUrl) return null
  if (imageUrl.startsWith('http')) return imageUrl
  return `${getBackendBaseUrl()}${imageUrl}`
}

// Cargar secciones de animales activas
const loadAnimalSections = async () => {
  loadingSections.value = true
  try {
    const response = await animalSectionService.getAll()
    // Solo mostrar secciones activas
    animalSections.value = (response.data.data || response.data || []).filter(section => section.is_active)
  } catch (error) {
    console.error('Error al cargar secciones de animales:', error)
    animalSections.value = []
  } finally {
    loadingSections.value = false
  }
}

// Navegar a productos filtrados por sección de animal
const goToAnimalSection = (sectionId) => {
  router.push({
    path: '/products',
    query: { animal_section_id: sectionId }
  })
}

onMounted(async () => {
  await loadAnimalSections()
  productStore.fetchFeaturedProducts(8)
})
</script>
