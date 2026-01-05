import api from './api'

export const petService = {
  // Obtener todas las mascotas del usuario
  async getAll() {
    return api.get('/user/pets')
  },

  // Obtener una mascota específica
  async get(id) {
    return api.get(`/user/pets/${id}`)
  },

  // Crear una nueva mascota
  async create(petData) {
    return api.post('/user/pets', petData)
  },

  // Actualizar una mascota
  async update(id, petData) {
    return api.put(`/user/pets/${id}`, petData)
  },

  // Eliminar una mascota
  async delete(id) {
    return api.delete(`/user/pets/${id}`)
  },

  // Subir foto de mascota
  async uploadPhoto(id, file) {
    const formData = new FormData()
    formData.append('photo', file)
    return api.post(`/user/pets/${id}/photo`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },

  // Eliminar foto de mascota
  async deletePhoto(id) {
    return api.delete(`/user/pets/${id}/photo`)
  },

  // Obtener categorías de animales disponibles
  async getCategories() {
    return api.get('/pet-categories')
  }
}

export default petService
