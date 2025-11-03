<template>
  <div class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2 text-gray-900 dark:text-white">Todos los Productos</h1>
        <p class="text-gray-600 dark:text-gray-400">Encuentra todo lo que necesita tu mascota</p>
      </div>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4 border border-gray-200 dark:border-gray-700 max-h-[calc(100vh-2rem)] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
              <h2 class="font-bold text-lg text-gray-900 dark:text-white">Filtros</h2>
              <button
                v-if="hasActiveFilters"
                @click="clearAllFilters"
                class="text-sm text-primary dark:text-primary-light hover:underline"
              >
                Limpiar
              </button>
            </div>

            <!-- Price Range Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Rango de Precio</h3>
              <div class="space-y-2">
                <input
                  v-model.number="filters.min_price"
                  type="number"
                  placeholder="Mín $"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                />
                <input
                  v-model.number="filters.max_price"
                  type="number"
                  placeholder="Máx $"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                />
              </div>
            </div>

            <!-- Animal Section Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Tipo de Mascota</h3>
              <div v-if="loadingSections" class="text-center py-2">
                <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-primary"></div>
              </div>
              <div v-else-if="animalSections.length > 0" class="grid grid-cols-2 gap-2">
                <button
                  v-for="section in animalSections"
                  :key="section.id"
                  @click="filters.animal_section_id = filters.animal_section_id === section.id ? null : section.id; applyFilters()"
                  :class="[
                    'flex flex-col items-center justify-center p-3 rounded-lg border-2 transition-all duration-200',
                    filters.animal_section_id === section.id
                      ? 'border-primary bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light'
                      : 'border-gray-300 dark:border-gray-600 hover:border-primary/50 dark:hover:border-primary/50 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                  ]"
                >
                  <span class="text-2xl mb-1">{{ section.icon }}</span>
                  <span class="text-xs font-medium text-center">{{ section.name }}</span>
                </button>
              </div>
              <p v-else class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">
                No hay secciones disponibles
              </p>
            </div>

            <!-- Brand Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Marca</h3>
              <select
                v-model="filters.brand"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              >
                <option value="">Todas las marcas</option>
                <option v-for="brand in productStore.brands" :key="brand" :value="brand">
                  {{ brand }}
                </option>
              </select>
            </div>

            <!-- Category Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Categoría</h3>
              <select
                v-model="filters.category"
                @change="applyFilters"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              >
                <option value="">Todas las categorías</option>
                <option
                  v-for="category in categoryStore.categories"
                  :key="category.id"
                  :value="category.slug"
                >
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Stock Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Disponibilidad</h3>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input
                  v-model="filters.in_stock"
                  type="checkbox"
                  class="form-checkbox h-4 w-4 text-primary rounded focus:ring-2 focus:ring-primary"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300">Solo en stock</span>
              </label>
            </div>

            <!-- Rating Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700 dark:text-gray-300">Calificación mínima</h3>
              <select
                v-model.number="filters.min_rating"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              >
                <option :value="null">Cualquier calificación</option>
                <option :value="4">4★ o más</option>
                <option :value="3">3★ o más</option>
                <option :value="2">2★ o más</option>
                <option :value="1">1★ o más</option>
              </select>
            </div>

            <button
              @click="applyFilters"
              class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary-dark transition"
            >
              Aplicar Filtros
            </button>
          </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <!-- Search & Sort Bar -->
          <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
              <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Buscar productos..."
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
              />
            </div>

            <!-- Sort -->
            <select
              v-model="filters.sort_by"
              @change="applyFilters"
              class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary sm:w-64 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            >
              <option value="">Ordenar por</option>
              <option value="newest">Más recientes</option>
              <option value="price_asc">Precio: Menor a Mayor</option>
              <option value="price_desc">Precio: Mayor a Menor</option>
              <option value="name">Nombre A-Z</option>
              <option value="rating">Mejor calificados</option>
            </select>
          </div>

          <!-- Active Filters Tags -->
          <div v-if="hasActiveFilters" class="mb-4 flex flex-wrap gap-2">
            <span
              v-if="selectedSection"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              {{ selectedSection.icon }} {{ selectedSection.name }}
              <button @click="filters.animal_section_id = null; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="selectedCategoryName"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              Categoría: {{ selectedCategoryName }}
              <button @click="filters.category = ''; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="filters.min_price"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              Precio mín: ${{ filters.min_price }}
              <button @click="filters.min_price = null; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="filters.max_price"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              Precio máx: ${{ filters.max_price }}
              <button @click="filters.max_price = null; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="filters.brand"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              Marca: {{ filters.brand }}
              <button @click="filters.brand = ''; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="filters.in_stock"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              En stock
              <button @click="filters.in_stock = false; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
            <span
              v-if="filters.min_rating"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light rounded-full text-sm"
            >
              {{ filters.min_rating }}★ o más
              <button @click="filters.min_rating = null; applyFilters()" class="hover:text-primary-dark dark:hover:text-primary">
                ×
              </button>
            </span>
          </div>

          <!-- Loading -->
          <div v-if="productStore.loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando productos...</p>
          </div>

          <!-- Error -->
          <div v-else-if="productStore.error" class="text-center py-12">
            <p class="text-red-600 dark:text-red-400">{{ productStore.error }}</p>
          </div>

          <!-- Products Grid -->
          <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              <ProductCard
                v-for="product in productStore.products"
                :key="product.id"
                :product="product"
              />
            </div>

            <!-- Empty State -->
            <div v-if="productStore.products.length === 0" class="text-center py-12">
              <i class="pi pi-search text-6xl text-gray-300 dark:text-gray-600 mb-4 block"></i>
              <p class="text-gray-600 dark:text-gray-400 text-lg">No se encontraron productos</p>
              <button
                @click="clearAllFilters"
                class="btn-primary mt-4"
              >
                Limpiar Filtros
              </button>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'
import { useCategoryStore } from '../stores/categoryStore'
import ProductCard from '../components/product/ProductCard.vue'
import animalSectionService from '../services/animalSectionService'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const categoryStore = useCategoryStore()

const searchQuery = ref(route.query.search || '')
const animalSections = ref([])
const loadingSections = ref(false)

const filters = ref({
  animal_section_id: null,
  category: '',
  min_price: null,
  max_price: null,
  brand: '',
  in_stock: false,
  min_rating: null,
  sort_by: ''
})

const hasActiveFilters = computed(() => {
  return filters.value.animal_section_id ||
    filters.value.category ||
    filters.value.min_price ||
    filters.value.max_price ||
    filters.value.brand ||
    filters.value.in_stock ||
    filters.value.min_rating ||
    searchQuery.value
})

const selectedSection = computed(() => {
  if (!filters.value.animal_section_id) return null
  return animalSections.value.find(section => section.id === filters.value.animal_section_id)
})

const selectedCategoryName = computed(() => {
  if (!filters.value.category) return null
  const category = categoryStore.categories.find(cat => cat.slug === filters.value.category)
  return category ? category.name : null
})

let searchTimeout = null

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    if (searchQuery.value.trim()) {
      productStore.searchProducts(searchQuery.value)
    } else {
      applyFilters()
    }
  }, 500)
}

const applyFilters = () => {
  const params = {}

  if (filters.value.animal_section_id) params.animal_section_id = filters.value.animal_section_id
  if (filters.value.category) params.category = filters.value.category
  if (filters.value.min_price) params.min_price = filters.value.min_price
  if (filters.value.max_price) params.max_price = filters.value.max_price
  if (filters.value.brand) params.brand = filters.value.brand
  if (filters.value.in_stock) params.in_stock = true
  if (filters.value.min_rating) params.min_rating = filters.value.min_rating
  if (filters.value.sort_by) params.sort_by = filters.value.sort_by

  productStore.fetchProducts(params)
}

const clearAllFilters = () => {
  searchQuery.value = ''
  filters.value = {
    animal_section_id: null,
    category: '',
    min_price: null,
    max_price: null,
    brand: '',
    in_stock: false,
    min_rating: null,
    sort_by: ''
  }
  productStore.fetchProducts()
}

const loadAnimalSections = async () => {
  loadingSections.value = true
  try {
    const response = await animalSectionService.getAll()
    animalSections.value = response.data.data || []
  } catch (error) {
    console.error('Error al cargar secciones de animales:', error)
    animalSections.value = []
  } finally {
    loadingSections.value = false
  }
}

onMounted(async () => {
  // Cargar secciones de animales
  await loadAnimalSections()

  // Aplicar filtro de animal_section_id si viene en la query
  if (route.query.animal_section_id) {
    filters.value.animal_section_id = parseInt(route.query.animal_section_id)
  }

  if (route.query.search) {
    productStore.searchProducts(route.query.search)
  } else {
    // Si hay filtros, aplicarlos; si no, cargar todos los productos
    if (filters.value.animal_section_id) {
      applyFilters()
    } else {
      productStore.fetchProducts()
    }
  }

  await categoryStore.fetchCategories()
  await productStore.fetchBrands()
})
</script>
