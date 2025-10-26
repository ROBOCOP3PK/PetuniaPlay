<template>
  <div class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Todos los Productos</h1>
        <p class="text-gray-600">Encuentra todo lo que necesita tu mascota</p>
      </div>

      <!-- Filters & Search -->
      <div class="mb-8 flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Buscar productos..."
            class="input-field"
          />
        </div>

        <!-- Category Filter -->
        <select
          v-model="selectedCategory"
          @change="handleCategoryFilter"
          class="input-field md:w-64"
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

        <!-- Sort -->
        <select v-model="sortBy" @change="handleSort" class="input-field md:w-48">
          <option value="">Ordenar por</option>
          <option value="price_asc">Precio: Menor a Mayor</option>
          <option value="price_desc">Precio: Mayor a Menor</option>
          <option value="name">Nombre A-Z</option>
        </select>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
            @click="clearFilters"
            class="btn-primary mt-4"
          >
            Limpiar Filtros
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
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
const sortBy = ref('')

let searchTimeout = null

const handleSearch = () => {
  // Debounce search
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    if (searchQuery.value.trim()) {
      productStore.searchProducts(searchQuery.value)
    } else {
      productStore.fetchProducts()
    }
  }, 500)
}

const handleCategoryFilter = () => {
  if (selectedCategory.value) {
    router.push(`/category/${selectedCategory.value}`)
  } else {
    productStore.fetchProducts()
  }
}

const handleSort = () => {
  // TODO: Implementar ordenamiento
  console.log('Sort by:', sortBy.value)
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  sortBy.value = ''
  productStore.fetchProducts()
}

onMounted(() => {
  if (route.query.search) {
    productStore.searchProducts(route.query.search)
  } else {
    productStore.fetchProducts()
  }
  categoryStore.fetchCategories()
})
</script>
