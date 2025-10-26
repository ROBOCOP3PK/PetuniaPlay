<template>
  <div class="relative" v-click-outside="closeDropdown">
    <div class="relative">
      <input
        v-model="searchQuery"
        @input="handleInput"
        @focus="showDropdown = true"
        @keydown.down.prevent="navigateDown"
        @keydown.up.prevent="navigateUp"
        @keydown.enter.prevent="selectCurrent"
        @keydown.esc="closeDropdown"
        type="text"
        :placeholder="placeholder"
        class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
        autocomplete="off"
      />

      <!-- Search Icon -->
      <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>

      <!-- Clear Button -->
      <button
        v-if="searchQuery"
        @click="clearSearch"
        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Loading Spinner -->
      <div
        v-if="loading"
        class="absolute right-3 top-1/2 transform -translate-y-1/2"
      >
        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary"></div>
      </div>
    </div>

    <!-- Dropdown Results -->
    <div
      v-if="showDropdown && (results.length > 0 || (searchQuery && !loading))"
      class="absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto"
    >
      <!-- Results -->
      <div v-if="results.length > 0">
        <router-link
          v-for="(product, index) in results"
          :key="product.id"
          :to="`/products/${product.slug}`"
          @click="closeDropdown"
          class="flex items-center gap-3 p-3 hover:bg-gray-50 transition cursor-pointer"
          :class="{ 'bg-gray-100': index === selectedIndex }"
        >
          <!-- Product Image -->
          <div class="flex-shrink-0">
            <img
              v-if="product.primary_image"
              :src="product.primary_image.image_url"
              :alt="product.name"
              class="w-12 h-12 object-cover rounded"
            />
            <div v-else class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>

          <!-- Product Info -->
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-dark truncate">{{ product.name }}</p>
            <div class="flex items-center gap-2 text-sm text-gray-600">
              <span v-if="product.brand" class="truncate">{{ product.brand }}</span>
              <span v-if="product.brand && product.sku" class="text-gray-400">•</span>
              <span v-if="product.sku" class="font-mono text-xs">{{ product.sku }}</span>
            </div>
          </div>

          <!-- Price -->
          <div class="flex-shrink-0 text-right">
            <p v-if="product.sale_price" class="font-bold text-primary">
              ${{ formatPrice(product.sale_price) }}
            </p>
            <p
              v-if="product.sale_price"
              class="text-xs text-gray-500 line-through"
            >
              ${{ formatPrice(product.price) }}
            </p>
            <p v-else class="font-bold text-dark">
              ${{ formatPrice(product.price) }}
            </p>
          </div>
        </router-link>
      </div>

      <!-- No Results -->
      <div v-else-if="searchQuery && !loading" class="p-4 text-center text-gray-500">
        <div class="text-4xl mb-2">🔍</div>
        <p>No se encontraron productos</p>
        <p class="text-sm mt-1">Intenta con otro término de búsqueda</p>
      </div>

      <!-- View All Results Link -->
      <div v-if="results.length > 0" class="border-t border-gray-200">
        <router-link
          :to="`/products?search=${encodeURIComponent(searchQuery)}`"
          @click="closeDropdown"
          class="block p-3 text-center text-primary font-semibold hover:bg-gray-50 transition"
        >
          Ver todos los resultados →
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import productService from '../../services/productService'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Buscar productos...'
  }
})

const router = useRouter()
const searchQuery = ref('')
const results = ref([])
const loading = ref(false)
const showDropdown = ref(false)
const selectedIndex = ref(-1)

let searchTimeout = null

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const handleInput = () => {
  selectedIndex.value = -1

  clearTimeout(searchTimeout)

  if (searchQuery.value.length < 2) {
    results.value = []
    showDropdown.value = false
    return
  }

  loading.value = true

  searchTimeout = setTimeout(async () => {
    try {
      const response = await productService.autocomplete(searchQuery.value, 10)
      results.value = response.data.data || []
      showDropdown.value = true
    } catch (error) {
      console.error('Error fetching autocomplete:', error)
      results.value = []
    } finally {
      loading.value = false
    }
  }, 300)
}

const navigateDown = () => {
  if (selectedIndex.value < results.value.length - 1) {
    selectedIndex.value++
  }
}

const navigateUp = () => {
  if (selectedIndex.value > 0) {
    selectedIndex.value--
  }
}

const selectCurrent = () => {
  if (selectedIndex.value >= 0 && selectedIndex.value < results.value.length) {
    const product = results.value[selectedIndex.value]
    router.push(`/products/${product.slug}`)
    closeDropdown()
  } else if (searchQuery.value) {
    router.push(`/products?search=${encodeURIComponent(searchQuery.value)}`)
    closeDropdown()
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  results.value = []
  showDropdown.value = false
  selectedIndex.value = -1
}

const closeDropdown = () => {
  showDropdown.value = false
  selectedIndex.value = -1
}

// Custom directive for click outside
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value()
      }
    }
    document.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent)
  }
}
</script>
