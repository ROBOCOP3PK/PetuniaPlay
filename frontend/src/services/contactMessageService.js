import api from './api'

export const contactMessageService = {
  // Obtener todos los mensajes
  async getAll(params = {}) {
    return api.get('/admin/contact-messages', { params })
  },

  // Obtener estadísticas
  async getStats() {
    return api.get('/admin/contact-messages/stats')
  },

  // Obtener un mensaje específico
  async getById(id) {
    return api.get(`/admin/contact-messages/${id}`)
  },

  // Actualizar estado del mensaje
  async updateStatus(id, status, adminNotes = null) {
    return api.put(`/admin/contact-messages/${id}/status`, {
      status,
      admin_notes: adminNotes
    })
  },

  // Eliminar un mensaje
  async delete(id) {
    return api.delete(`/admin/contact-messages/${id}`)
  }
}

export default contactMessageService
