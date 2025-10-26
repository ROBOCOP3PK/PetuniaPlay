import api from './api'

export const wishlistService = {
  // Obtener todos los items de la wishlist
  getAll() {
    return api.get('/wishlist')
  },

  // Agregar un producto a la wishlist
  add(productId) {
    return api.post('/wishlist', { product_id: productId })
  },

  // Eliminar un producto de la wishlist
  remove(productId) {
    return api.delete(`/wishlist/${productId}`)
  },

  // Verificar si un producto está en la wishlist
  check(productId) {
    return api.get(`/wishlist/check/${productId}`)
  },

  // Obtener IDs de todos los productos en la wishlist
  getProductIds() {
    return api.get('/wishlist/product-ids')
  },

  // Limpiar toda la wishlist
  clear() {
    return api.delete('/wishlist/clear')
  }
}

export default wishlistService
