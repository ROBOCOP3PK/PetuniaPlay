import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import productService from '../services/productService'

export const useProductStore = defineStore('product', () => {
  // State
  const products = ref([])
  const featuredProducts = ref([])
  const currentProduct = ref(null)
  const loading = ref(false)
  const error = ref(null)
  const pagination = ref({
    currentPage: 1,
    lastPage: 1,
    perPage: 15,
    total: 0
  })

  // Getters
  const hasProducts = computed(() => products.value.length > 0)
  const hasFeaturedProducts = computed(() => featuredProducts.value.length > 0)

  // Actions
  async function fetchProducts(params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await productService.getAll(params)
      products.value = response.data.data

      // Si viene paginación
      if (response.data.meta) {
        pagination.value = {
          currentPage: response.data.meta.current_page,
          lastPage: response.data.meta.last_page,
          perPage: response.data.meta.per_page,
          total: response.data.meta.total
        }
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar productos'
      console.error('Error fetching products:', err)
    } finally {
      loading.value = false
    }
  }

  async function fetchFeaturedProducts(limit = 8) {
    loading.value = true
    error.value = null
    try {
      const response = await productService.getFeatured(limit)
      featuredProducts.value = response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar productos destacados'
      console.error('Error fetching featured products:', err)
    } finally {
      loading.value = false
    }
  }

  async function fetchProductBySlug(slug) {
    loading.value = true
    error.value = null
    try {
      const response = await productService.getBySlug(slug)
      currentProduct.value = response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar producto'
      console.error('Error fetching product:', err)
    } finally {
      loading.value = false
    }
  }

  async function searchProducts(query, params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await productService.search(query, params)
      products.value = response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al buscar productos'
      console.error('Error searching products:', err)
    } finally {
      loading.value = false
    }
  }

  function clearProducts() {
    products.value = []
    currentProduct.value = null
  }

  return {
    // State
    products,
    featuredProducts,
    currentProduct,
    loading,
    error,
    pagination,
    // Getters
    hasProducts,
    hasFeaturedProducts,
    // Actions
    fetchProducts,
    fetchFeaturedProducts,
    fetchProductBySlug,
    searchProducts,
    clearProducts
  }
})
