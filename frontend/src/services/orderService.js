import api from './api'

export const orderService = {
  // Crear un nuevo pedido
  create(orderData) {
    return api.post('/orders', orderData)
  },

  // Obtener todos los pedidos del usuario
  getMyOrders() {
    return api.get('/orders')
  },

  // Obtener detalles de un pedido específico
  getByOrderNumber(orderNumber) {
    return api.get(`/orders/${orderNumber}`)
  },

  // Cancelar un pedido
  cancel(id) {
    return api.put(`/orders/${id}/cancel`)
  },

  // Obtener todos los pedidos (solo admin)
  getAllOrders(params = {}) {
    return api.get('/admin/orders', { params })
  },

  // Actualizar estado del pedido (solo admin)
  updateStatus(id, status) {
    return api.put(`/orders/${id}/status`, { status })
  }
}

export default orderService
