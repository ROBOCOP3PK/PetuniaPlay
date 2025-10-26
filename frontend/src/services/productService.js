import api from './api'

export const productService = {
  // Obtener todos los productos
  getAll(params = {}) {
    return api.get('/products', { params })
  },

  // Obtener producto por slug
  getBySlug(slug) {
    return api.get(`/products/${slug}`)
  },

  // Obtener productos destacados
  getFeatured(limit = 8) {
    return api.get('/products/featured', { params: { limit } })
  },

  // Buscar productos
  search(query, params = {}) {
    return api.get('/products/search', {
      params: { q: query, ...params }
    })
  },

  // Autocompletado de búsqueda
  autocomplete(query, limit = 10) {
    return api.get('/products/autocomplete', {
      params: { q: query, limit }
    })
  },

  // Obtener todas las marcas
  getBrands() {
    return api.get('/products/brands')
  },

  // Crear producto (solo admin)
  create(data) {
    return api.post('/products', data)
  },

  // Actualizar producto (solo admin)
  update(id, data) {
    return api.put(`/products/${id}`, data)
  },

  // Eliminar producto (solo admin)
  delete(id) {
    return api.delete(`/products/${id}`)
  }
}

export default productService
