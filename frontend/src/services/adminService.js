import api from './api'

export const adminService = {
  // Dashboard & Statistics
  getDashboard() {
    return api.get('/admin/dashboard')
  },

  getSalesStats(period = 'month') {
    return api.get('/admin/sales-stats', { params: { period } })
  },

  // Users Management (admin only)
  getUsers() {
    return api.get('/admin/users')
  },

  updateUserRole(userId, role) {
    return api.put(`/admin/users/${userId}/role`, { role })
  },

  toggleUserStatus(userId) {
    return api.put(`/admin/users/${userId}/toggle-status`)
  },

  // Orders Management
  getAllOrders() {
    return api.get('/admin/orders')
  },

  updateOrderStatus(orderId, status) {
    return api.put(`/orders/${orderId}/status`, { status })
  },

  // Products Management
  createProduct(productData) {
    return api.post('/products', productData)
  },

  updateProduct(productId, productData) {
    return api.put(`/products/${productId}`, productData)
  },

  deleteProduct(productId) {
    return api.delete(`/products/${productId}`)
  },

  // Categories Management
  createCategory(categoryData) {
    return api.post('/categories', categoryData)
  },

  updateCategory(categoryId, categoryData) {
    return api.put(`/categories/${categoryId}`, categoryData)
  },

  deleteCategory(categoryId) {
    return api.delete(`/categories/${categoryId}`)
  }
}

export default adminService
