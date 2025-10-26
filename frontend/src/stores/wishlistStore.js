import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { wishlistService } from '../services/wishlistService'
import { useAuthStore } from './authStore'

export const useWishlistStore = defineStore('wishlist', () => {
  const items = ref([])
  const productIds = ref([]) // IDs de productos en wishlist para búsqueda rápida
  const loading = ref(false)

  const authStore = useAuthStore()

  // Computeds
  const itemCount = computed(() => items.value.length)

  const isInWishlist = (productId) => {
    return productIds.value.includes(productId)
  }

  // Actions

  /**
   * Cargar wishlist desde el servidor
   */
  async function fetchWishlist() {
    if (!authStore.isAuthenticated) {
      items.value = []
      productIds.value = []
      return
    }

    loading.value = true
    try {
      const response = await wishlistService.getAll()
      items.value = response.data.data
      productIds.value = items.value.map(item => item.product.id)
    } catch (error) {
      console.error('Error al cargar wishlist:', error)
    } finally {
      loading.value = false
    }
  }

  /**
   * Cargar solo los IDs de productos (más rápido)
   */
  async function fetchProductIds() {
    if (!authStore.isAuthenticated) {
      productIds.value = []
      return
    }

    try {
      const response = await wishlistService.getProductIds()
      productIds.value = response.data.product_ids
    } catch (error) {
      console.error('Error al cargar product IDs:', error)
    }
  }

  /**
   * Agregar producto a wishlist
   */
  async function addToWishlist(product) {
    if (!authStore.isAuthenticated) {
      return { success: false, message: 'Debes iniciar sesión para agregar a favoritos' }
    }

    try {
      const response = await wishlistService.add(product.id)

      // Agregar a la lista local
      items.value.push({
        id: response.data.data.id,
        product: product,
        added_at: response.data.data.added_at
      })

      // Agregar ID a la lista rápida
      productIds.value.push(product.id)

      return { success: true, message: response.data.message }
    } catch (error) {
      if (error.response?.status === 409) {
        return { success: false, message: 'Este producto ya está en tu lista de deseos' }
      }
      return { success: false, message: 'Error al agregar a favoritos' }
    }
  }

  /**
   * Eliminar producto de wishlist
   */
  async function removeFromWishlist(productId) {
    try {
      const response = await wishlistService.remove(productId)

      // Eliminar de la lista local
      items.value = items.value.filter(item => item.product.id !== productId)

      // Eliminar ID de la lista rápida
      productIds.value = productIds.value.filter(id => id !== productId)

      return { success: true, message: response.data.message }
    } catch (error) {
      return { success: false, message: 'Error al eliminar de favoritos' }
    }
  }

  /**
   * Toggle wishlist - agregar si no está, eliminar si está
   */
  async function toggleWishlist(product) {
    if (isInWishlist(product.id)) {
      return await removeFromWishlist(product.id)
    } else {
      return await addToWishlist(product)
    }
  }

  /**
   * Limpiar toda la wishlist
   */
  async function clearWishlist() {
    try {
      const response = await wishlistService.clear()
      items.value = []
      productIds.value = []
      return { success: true, message: response.data.message }
    } catch (error) {
      return { success: false, message: 'Error al limpiar lista de deseos' }
    }
  }

  /**
   * Inicializar wishlist (cargar solo IDs al inicio)
   */
  function init() {
    if (authStore.isAuthenticated) {
      fetchProductIds()
    }
  }

  return {
    items,
    productIds,
    loading,
    itemCount,
    isInWishlist,
    fetchWishlist,
    fetchProductIds,
    addToWishlist,
    removeFromWishlist,
    toggleWishlist,
    clearWishlist,
    init
  }
})
