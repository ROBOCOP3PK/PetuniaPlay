import api from './api'

export const loyaltyService = {
  // ==================== CLIENTE ====================

  // Obtener mis recompensas disponibles
  getMyRewards() {
    return api.get('/loyalty/my-rewards')
  },

  // Obtener mis canjes (historial)
  getMyRedemptions() {
    return api.get('/loyalty/my-redemptions')
  },

  // Canjear una recompensa
  redeemReward(rewardId) {
    return api.post('/loyalty/redeem', { reward_id: rewardId })
  },

  // ==================== ADMIN ====================

  // Obtener configuración del programa
  getProgram() {
    return api.get('/admin/loyalty/program')
  },

  // Actualizar configuración del programa
  updateProgram(data) {
    return api.post('/admin/loyalty/program', data)
  },

  // Activar/Desactivar programa
  toggleProgram(isActive) {
    return api.post('/admin/loyalty/program/toggle', { is_active: isActive })
  },

  // Obtener estadísticas del programa
  getStatistics() {
    return api.get('/admin/loyalty/statistics')
  },

  // Obtener lista de recompensas
  getRewards(params = {}) {
    return api.get('/admin/loyalty/rewards', { params })
  },

  // Crear nueva recompensa
  createReward(data) {
    return api.post('/admin/loyalty/rewards', data)
  },

  // Actualizar recompensa
  updateReward(id, data) {
    return api.put(`/admin/loyalty/rewards/${id}`, data)
  },

  // Eliminar recompensa
  deleteReward(id) {
    return api.delete(`/admin/loyalty/rewards/${id}`)
  },

  // Activar/Desactivar recompensa
  toggleReward(id) {
    return api.post(`/admin/loyalty/rewards/${id}/toggle`)
  },

  // Obtener lista de canjes
  getRedemptions(params = {}) {
    return api.get('/admin/loyalty/redemptions', { params })
  },

  // Obtener detalle de un canje
  getRedemptionDetail(id) {
    return api.get(`/admin/loyalty/redemptions/${id}`)
  },

  // Procesar un canje (marcar como completado)
  processRedemption(id) {
    return api.post(`/admin/loyalty/redemptions/${id}/process`)
  }
}

export default loyaltyService
