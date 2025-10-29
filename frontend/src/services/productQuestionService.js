import api from './api'

export const productQuestionService = {
  // Obtener preguntas publicas de un producto
  getProductQuestions(productId) {
    return api.get(`/products/${productId}/questions`)
  },

  // Crear una nueva pregunta (requiere autenticacion)
  create(data) {
    return api.post('/product-questions', data)
  },

  // Admin: Obtener todas las preguntas con filtros
  adminGetAll(params = {}) {
    return api.get('/admin/product-questions', { params })
  },

  // Admin: Obtener estadisticas de preguntas
  getStats() {
    return api.get('/admin/product-questions/stats')
  },

  // Admin: Responder una pregunta
  answer(questionId, data) {
    return api.put(`/admin/product-questions/${questionId}/answer`, data)
  },

  // Admin: Eliminar una pregunta
  delete(questionId) {
    return api.delete(`/admin/product-questions/${questionId}`)
  }
}

export default productQuestionService
