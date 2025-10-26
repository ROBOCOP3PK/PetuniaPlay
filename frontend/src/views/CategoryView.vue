<template>
  <div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
      <!-- Loading Category -->
      <div v-if="categoryStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando categoría...</p>
      </div>

      <!-- Error -->
      <div v-else-if="categoryStore.error" class="text-center py-12">
        <div class="text-6xl mb-4">😞</div>
        <h2 class="text-2xl font-bold mb-2">Categoría no encontrada</h2>
        <p class="text-gray-600 mb-6">{{ categoryStore.error }}</p>
        <router-link to="/products" class="btn-primary">
          Ver todos los productos
        </router-link>
      </div>

      <!-- Category Content -->
      <div v-else-if="category">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-8">
          <ol class="flex items-center space-x-2 text-gray-600">
            <li><router-link to="/" class="hover:text-primary">Inicio</router-link></li>
            <li>/</li>
            <li><router-link to="/products" class="hover:text-primary">Productos</router-link></li>
            <li>/</li>
            <li class="text-primary font-semibold">{{ category.name }}</li>
          </ol>
        </nav>

        <!-- Category Header -->
        <div class="mb-12">
          <div class="flex items-center gap-4 mb-4">
            <span class="text-6xl">{{ getCategoryIcon(category.name) }}</span>
            <div>
              <h1 class="text-4xl font-bold">{{ category.name }}</h1>
              <p class="text-gray-600 mt-2">{{ category.description }}</p>
            </div>
          </div>
        </div>

        <!-- Subcategories -->
        <div v-if="subcategories.length > 0" class="mb-12">
          <h2 class="text-2xl font-bold mb-6">Subcategorías</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <router-link
              v-for="subcat in subcategories"
              :key="subcat.id"
              :to="`/category/${subcat.slug}`"
              class="card p-6 text-center hover:shadow-xl transition cursor-pointer"
            >
              <h3 class="font-bold text-lg mb-1">{{ subcat.name }}</h3>
              <p class="text-sm text-gray-600">{{ subcat.description }}</p>
            </router-link>
          </div>
        </div>

        <!-- Filters -->
        <div class="mb-8 flex flex-col md:flex-row gap-4">
          <!-- Search -->
          <div class="flex-1">
            <input
              v-model="searchQuery"
              @input="handleSearch"
              type="text"
              placeholder="Buscar en esta categoría..."
              class="input-field"
            />
          </div>

          <!-- Sort -->
          <select v-model="sortBy" @change="handleSort" class="input-field md:w-48">
            <option value="">Ordenar por</option>
            <option value="price_asc">Precio: Menor a Mayor</option>
            <option value="price_desc">Precio: Mayor a Menor</option>
            <option value="name">Nombre A-Z</option>
          </select>
        </div>

        <!-- Products Count -->
        <div class="mb-6">
          <p class="text-gray-600">
            Mostrando <span class="font-bold">{{ filteredProducts.length }}</span> productos
          </p>
        </div>

        <!-- Products Grid -->
        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard
            v-for="product in filteredProducts"
            :key="product.id"
            :product="product"
          />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="text-6xl mb-4">🔍</div>
          <p class="text-gray-600 text-lg">No hay productos en esta categoría</p>
          <router-link to="/products" class="btn-primary mt-6 inline-block">
            Ver todos los productos
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useCategoryStore } from '../stores/categoryStore'
import { useProductStore } from '../stores/productStore'
import ProductCard from '../components/product/ProductCard.vue'

const route = useRoute()
const categoryStore = useCategoryStore()
const productStore = useProductStore()

const searchQuery = ref('')
const sortBy = ref('')

const category = computed(() => categoryStore.currentCategory)

const subcategories = computed(() => {
  if (!category.value) return []
  return categoryStore.getCategoriesByParent(category.value.id)
})

const filteredProducts = computed(() => {
  if (!category.value?.products) return []

  let products = [...category.value.products]

  // Filter by search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    products = products.filter(p =>
      p.name.toLowerCase().includes(query) ||
      p.description.toLowerCase().includes(query)
    )
  }

  // Sort
  if (sortBy.value === 'price_asc') {
    products.sort((a, b) => parseFloat(a.final_price) - parseFloat(b.final_price))
  } else if (sortBy.value === 'price_desc') {
    products.sort((a, b) => parseFloat(b.final_price) - parseFloat(a.final_price))
  } else if (sortBy.value === 'name') {
    products.sort((a, b) => a.name.localeCompare(b.name))
  }

  return products
})

const getCategoryIcon = (name) => {
  const icons = {
    'Perros': '🐕',
    'Gatos': '🐈',
    'Accesorios': '🎾',
    'Alimentos': '🍖',
    'Juguetes': '🎾',
    'Camas': '🛏️',
    'Rascadores': '🪵',
    'Collares': '🦴',
    'Comederos': '🥣',
    'Transportadoras': '🎒'
  }

  // Try exact match
  if (icons[name]) return icons[name]

  // Try partial match
  for (const [key, value] of Object.entries(icons)) {
    if (name.includes(key)) return value
  }

  return '🐾'
}

let searchTimeout = null

const handleSearch = () => {
  // Debounce search
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    // Filter is handled by computed property
  }, 300)
}

const handleSort = () => {
  // Sort is handled by computed property
}

const loadCategory = async () => {
  const slug = route.params.slug
  await categoryStore.fetchCategories() // Ensure all categories are loaded for subcategories
  await categoryStore.fetchCategoryBySlug(slug)
}

// Watch for route changes
watch(() => route.params.slug, () => {
  if (route.params.slug) {
    searchQuery.value = ''
    sortBy.value = ''
    loadCategory()
  }
})

onMounted(() => {
  loadCategory()
})
</script>
