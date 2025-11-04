import api from './api'

export default {
  /**
   * Crear una preferencia de pago en Mercado Pago
   * @param {number} orderId - ID de la orden
   * @returns {Promise}
   */
  createPreference(orderId) {
    return api.post('/payments/create-preference', { order_id: orderId })
  },

  /**
   * Obtener el estado de un pago
   * @param {number} orderId - ID de la orden
   * @returns {Promise}
   */
  getPaymentStatus(orderId) {
    return api.get('/payments/status', {
      params: { order_id: orderId }
    })
  }
}
