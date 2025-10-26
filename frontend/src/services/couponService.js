import api from './api'

export const couponService = {
  // Validar un cupón
  validate(code, subtotal) {
    return api.post('/coupons/validate', {
      code,
      subtotal
    })
  },

  // Admin: Obtener todos los cupones
  getAll(params = {}) {
    return api.get('/coupons', { params })
  },

  // Admin: Obtener un cupón
  getById(id) {
    return api.get(`/coupons/${id}`)
  },

  // Admin: Crear cupón
  create(data) {
    return api.post('/coupons', data)
  },

  // Admin: Actualizar cupón
  update(id, data) {
    return api.put(`/coupons/${id}`, data)
  },

  // Admin: Eliminar cupón
  delete(id) {
    return api.delete(`/coupons/${id}`)
  },

  // Admin: Alternar estado activo/inactivo
  toggleStatus(id) {
    return api.put(`/coupons/${id}/toggle-status`)
  },

  // Admin: Obtener estadísticas
  getStats() {
    return api.get('/admin/coupons/stats')
  }
}

export default couponService
