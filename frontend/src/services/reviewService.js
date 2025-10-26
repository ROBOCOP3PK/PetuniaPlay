import api from './api'

export const reviewService = {
  // Obtener reseñas de un producto
  getProductReviews(productId, params = {}) {
    return api.get(`/products/${productId}/reviews`, { params })
  },

  // Obtener estadísticas de reseñas de un producto
  getReviewStats(productId) {
    return api.get(`/products/${productId}/reviews/stats`)
  },

  // Crear una nueva reseña (requiere autenticación)
  create(data) {
    return api.post('/reviews', data)
  },

  // Actualizar una reseña (requiere autenticación)
  update(reviewId, data) {
    return api.put(`/reviews/${reviewId}`, data)
  },

  // Eliminar una reseña (requiere autenticación)
  delete(reviewId) {
    return api.delete(`/reviews/${reviewId}`)
  },

  // Admin: Obtener todas las reseñas
  adminGetAll(params = {}) {
    return api.get('/admin/reviews', { params })
  },

  // Admin: Aprobar/rechazar reseña
  toggleApproval(reviewId) {
    return api.put(`/admin/reviews/${reviewId}/toggle-approval`)
  }
}

export default reviewService
