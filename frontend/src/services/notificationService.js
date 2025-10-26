import api from './api'

export const notificationService = {
  // Obtener notificaciones del usuario
  async getNotifications(params = {}) {
    return api.get('/notifications', { params })
  },

  // Obtener contador de notificaciones no leídas
  async getUnreadCount() {
    return api.get('/notifications/unread-count')
  },

  // Marcar una notificación como leída
  async markAsRead(id) {
    return api.put(`/notifications/${id}/read`)
  },

  // Marcar todas como leídas
  async markAllAsRead() {
    return api.put('/notifications/mark-all-read')
  },

  // Eliminar una notificación
  async deleteNotification(id) {
    return api.delete(`/notifications/${id}`)
  },

  // Eliminar notificaciones leídas
  async deleteReadNotifications() {
    return api.delete('/notifications/delete-read')
  }
}

export default notificationService
