import api from './api'

export const animalSectionService = {
  // Obtener todas las secciones (públicas si no autenticado, todas si admin/manager)
  getAll(params = {}) {
    return api.get('/animal-sections', { params })
  },

  // Obtener sección por slug
  getBySlug(slug) {
    return api.get(`/animal-sections/${slug}`)
  },

  // Crear sección (solo admin/manager)
  create(data) {
    return api.post('/animal-sections', data)
  },

  // Actualizar sección (solo admin/manager)
  update(id, data) {
    return api.put(`/animal-sections/${id}`, data)
  },

  // Eliminar sección (solo admin/manager)
  delete(id) {
    return api.delete(`/animal-sections/${id}`)
  },

  // Alternar estado activo/inactivo (solo admin/manager)
  toggleStatus(id) {
    return api.put(`/animal-sections/${id}/toggle-status`)
  },

  // Obtener estadísticas (solo admin/manager)
  getStats() {
    return api.get('/admin/animal-sections/stats')
  },

  // Reordenar secciones (solo admin/manager)
  reorder(sections) {
    return api.post('/admin/animal-sections/reorder', { sections })
  },

  // Subir/actualizar imagen de sección (solo admin/manager)
  uploadImage(id, imageFile) {
    const formData = new FormData()
    formData.append('image', imageFile)
    return api.post(`/animal-sections/${id}/image`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  }
}

export default animalSectionService
