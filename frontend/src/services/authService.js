import api from './api'
import axios from 'axios'

export const authService = {
  // Obtener el CSRF token (necesario para Sanctum)
  async getCsrfToken() {
    const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'
    const apiBaseUrl = baseUrl.replace('/api/v1', '')
    return axios.get(`${apiBaseUrl}/sanctum/csrf-cookie`, {
      withCredentials: true
    })
  },

  // Registro de nuevo usuario
  async register(userData) {
    console.log('authService.register called with:', userData)
    await this.getCsrfToken()
    console.log('CSRF token obtained')
    const response = await api.post('/register', userData)
    console.log('Register response:', response)
    return response
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

  // ========== Verificación con código de 6 dígitos ==========

  // Enviar código de verificación de email
  async sendVerificationCode(email) {
    await this.getCsrfToken()
    return api.post('/send-verification-code', { email })
  },

  // Verificar email con código
  async verifyEmail(email, code) {
    await this.getCsrfToken()
    return api.post('/verify-email', { email, code })
  },

  // Reenviar código de verificación
  async resendVerificationCode(email, type = 'email_verification') {
    await this.getCsrfToken()
    return api.post('/resend-verification-code', { email, type })
  },

  // Enviar código para restablecer contraseña
  async sendPasswordResetCode(email) {
    await this.getCsrfToken()
    return api.post('/send-password-reset-code', { email })
  },

  // Verificar código de reset de contraseña
  async verifyPasswordResetCode(email, code) {
    await this.getCsrfToken()
    return api.post('/verify-password-reset-code', { email, code })
  },

  // Restablecer contraseña con código verificado
  async resetPasswordWithCode(data) {
    await this.getCsrfToken()
    return api.post('/reset-password-with-code', data)
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
