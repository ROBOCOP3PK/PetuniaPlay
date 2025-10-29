import api from './api'
import axios from 'axios'

export const authService = {
  // Obtener el CSRF token (necesario para Sanctum)
  async getCsrfToken() {
    return axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie', {
      withCredentials: true
    })
  },

  // Registro de nuevo usuario
  async register(userData) {
    await this.getCsrfToken()
    return api.post('/register', userData)
  },

  // Login
  async login(credentials) {
    await this.getCsrfToken()
    return api.post('/login', credentials)
  },

  // Logout
  async logout() {
    return api.post('/logout')
  },

  // Obtener usuario autenticado
  async getUser() {
    return api.get('/user')
  },

  // Actualizar perfil
  async updateProfile(userData) {
    return api.put('/user/profile', userData)
  },

  // Cambiar contraseña
  async changePassword(passwordData) {
    return api.put('/user/password', passwordData)
  },

  // Actualizar preferencias de notificación
  async updateNotificationPreferences(preferences) {
    return api.put('/user/notification-preferences', preferences)
  },

  // Recuperar contraseña (enviar email)
  async forgotPassword(email) {
    await this.getCsrfToken()
    return api.post('/forgot-password', { email })
  },

  // Restablecer contraseña
  async resetPassword(data) {
    await this.getCsrfToken()
    return api.post('/reset-password', data)
  },

  // Verificar si el usuario está autenticado
  isAuthenticated() {
    return !!localStorage.getItem('auth_token')
  },

  // Obtener token
  getToken() {
    return localStorage.getItem('auth_token')
  },

  // Guardar token
  setToken(token) {
    localStorage.setItem('auth_token', token)
  },

  // Eliminar token
  removeToken() {
    localStorage.removeItem('auth_token')
  }
}

export default authService
