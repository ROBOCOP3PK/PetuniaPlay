<template>
  <div class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Todos los Productos</h1>
        <p class="text-gray-600">Encuentra todo lo que necesita tu mascota</p>
      </div>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
          <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
            <div class="flex justify-between items-center mb-4">
              <h2 class="font-bold text-lg">Filtros</h2>
              <button
                v-if="hasActiveFilters"
                @click="clearAllFilters"
                class="text-sm text-primary hover:underline"
              >
                Limpiar
              </button>
            </div>

            <!-- Price Range Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700">Rango de Precio</h3>
              <div class="space-y-2">
                <input
                  v-model.number="filters.min_price"
                  type="number"
                  placeholder="Mín $"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                />
                <input
                  v-model.number="filters.max_price"
                  type="number"
                  placeholder="Máx $"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                />
              </div>
            </div>

            <!-- Brand Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700">Marca</h3>
              <select
                v-model="filters.brand"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
              >
                <option value="">Todas las marcas</option>
                <option v-for="brand in productStore.brands" :key="brand" :value="brand">
                  {{ brand }}
                </option>
              </select>
            </div>

            <!-- Category Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700">Categoría</h3>
              <select
                v-model="selectedCategory"
                @change="handleCategoryFilter"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
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
              <h3 class="font-semibold mb-3 text-sm text-gray-700">Disponibilidad</h3>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input
                  v-model="filters.in_stock"
                  type="checkbox"
                  class="form-checkbox h-4 w-4 text-primary rounded focus:ring-2 focus:ring-primary"
                />
                <span class="text-sm">Solo en stock</span>
              </label>
            </div>

            <!-- Rating Filter -->
            <div class="mb-6">
              <h3 class="font-semibold mb-3 text-sm text-gray-700">Calificación mínima</h3>
              <select
                v-model.number="filters.min_rating"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
              >
                <option :value="null">Cualquier calificación</option>
                <option :value="4">4⭐ o más</option>
                <option :value="3">3⭐ o más</option>
                <option :value="2">2⭐ o más</option>
                <option :value="1">1⭐ o más</option>
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>

            <!-- Sort -->
            <select
              v-model="filters.sort_by"
              @change="applyFilters"
              class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary sm:w-64"
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
              v-if="filters.min_price"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"
            >
              Precio mín: ${{ filters.min_price }}
              <button @click="filters.min_price = null; applyFilters()" class="hover:text-primary-dark">
                ×
              </button>
            </span>
            <span
              v-if="filters.max_price"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"
            >
              Precio máx: ${{ filters.max_price }}
              <button @click="filters.max_price = null; applyFilters()" class="hover:text-primary-dark">
                ×
              </button>
            </span>
            <span
              v-if="filters.brand"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"
            >
              Marca: {{ filters.brand }}
              <button @click="filters.brand = ''; applyFilters()" class="hover:text-primary-dark">
                ×
              </button>
            </span>
            <span
              v-if="filters.in_stock"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"
            >
              En stock
              <button @click="filters.in_stock = false; applyFilters()" class="hover:text-primary-dark">
                ×
              </button>
            </span>
            <span
              v-if="filters.min_rating"
              class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"
            >
              {{ filters.min_rating }}⭐ o más
              <button @click="filters.min_rating = null; applyFilters()" class="hover:text-primary-dark">
                ×
              </button>
            </span>
          </div>

          <!-- Loading -->
          <div v-if="productStore.loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            <p class="mt-4 text-gray-600">Cargando productos...</p>
          </div>

          <!-- Error -->
          <div v-else-if="productStore.error" class="text-center py-12">
            <p class="text-red-600">{{ productStore.error }}</p>
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
              <div class="text-6xl mb-4">🔍</div>
              <p class="text-gray-600 text-lg">No se encontraron productos</p>
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

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const categoryStore = useCategoryStore()

const searchQuery = ref(route.query.search || '')
const selectedCategory = ref('')

const filters = ref({
  min_price: null,
  max_price: null,
  brand: '',
  in_stock: false,
  min_rating: null,
  sort_by: ''
})

const hasActiveFilters = computed(() => {
  return filters.value.min_price ||
    filters.value.max_price ||
    filters.value.brand ||
    filters.value.in_stock ||
    filters.value.min_rating ||
    searchQuery.value
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

const handleCategoryFilter = () => {
  if (selectedCategory.value) {
    router.push(`/category/${selectedCategory.value}`)
  } else {
    applyFilters()
  }
}

const applyFilters = () => {
  const params = {}

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
  selectedCategory.value = ''
  filters.value = {
    min_price: null,
    max_price: null,
    brand: '',
    in_stock: false,
    min_rating: null,
    sort_by: ''
  }
  productStore.fetchProducts()
}

onMounted(async () => {
  if (route.query.search) {
    productStore.searchProducts(route.query.search)
  } else {
    productStore.fetchProducts()
  }

  await categoryStore.fetchCategories()
  await productStore.fetchBrands()
})
</script>
