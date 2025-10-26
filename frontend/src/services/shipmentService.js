import api from './api'

export const shipmentService = {
  /**
   * Obtener todos los envíos (admin)
   */
  getAll(params = {}) {
    return api.get('/shipments', { params })
  },

  /**
   * Crear un nuevo envío
   */
  create(data) {
    return api.post('/shipments', data)
  },

  /**
   * Obtener un envío específico
   */
  get(id) {
    return api.get(`/shipments/${id}`)
  },

  /**
   * Actualizar un envío
   */
  update(id, data) {
    return api.put(`/shipments/${id}`, data)
  },

  /**
   * Eliminar un envío
   */
  delete(id) {
    return api.delete(`/shipments/${id}`)
  },

  /**
   * Rastrear un envío por número de tracking (público)
   */
  trackByNumber(trackingNumber) {
    return api.get(`/shipments/track/${trackingNumber}`)
  },

  /**
   * Obtener estadísticas de envíos (admin)
   */
  getStats() {
    return api.get('/admin/shipments/stats')
  }
}
