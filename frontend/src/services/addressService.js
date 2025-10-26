import api from './api'

export const addressService = {
  // Obtener todas las direcciones del usuario
  getAll() {
    return api.get('/user/addresses')
  },

  // Obtener una dirección específica
  getById(id) {
    return api.get(`/user/addresses/${id}`)
  },

  // Crear nueva dirección
  create(addressData) {
    return api.post('/user/addresses', addressData)
  },

  // Actualizar dirección
  update(id, addressData) {
    return api.put(`/user/addresses/${id}`, addressData)
  },

  // Eliminar dirección
  delete(id) {
    return api.delete(`/user/addresses/${id}`)
  },

  // Establecer dirección por defecto
  setDefault(id) {
    return api.put(`/user/addresses/${id}/set-default`)
  }
}

export default addressService
