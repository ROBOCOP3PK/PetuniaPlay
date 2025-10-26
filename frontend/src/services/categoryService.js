import api from './api'

export const categoryService = {
  // Obtener todas las categorías
  getAll() {
    return api.get('/categories')
  },

  // Obtener categoría por slug
  getBySlug(slug) {
    return api.get(`/categories/${slug}`)
  },

  // Crear categoría (solo admin)
  create(data) {
    return api.post('/categories', data)
  },

  // Actualizar categoría (solo admin)
  update(id, data) {
    return api.put(`/categories/${id}`, data)
  },

  // Eliminar categoría (solo admin)
  delete(id) {
    return api.delete(`/categories/${id}`)
  }
}

export default categoryService
