import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService from '../services/authService'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token') || null)
  const loading = ref(false)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isManager = computed(() => user.value?.role === 'manager')
  const isCustomer = computed(() => user.value?.role === 'customer')
  const hasManagerAccess = computed(() => ['manager', 'admin'].includes(user.value?.role))
  const userName = computed(() => user.value?.name || '')
  const userEmail = computed(() => user.value?.email || '')
  const userRole = computed(() => user.value?.role || 'customer')

  // Actions
  async function login(credentials) {
    loading.value = true
    try {
      const response = await authService.login(credentials)
      const { user: userData, token: authToken } = response.data

      user.value = userData
      token.value = authToken
      authService.setToken(authToken)

      return { success: true, user: userData }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al iniciar sesión'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function register(userData) {
    console.log('authStore.register called with:', userData)
    loading.value = true
    try {
      const response = await authService.register(userData)
      console.log('authService.register response:', response)
      const { user: newUser, token: authToken, requires_email_verification } = response.data

      user.value = newUser
      token.value = authToken
      authService.setToken(authToken)

      console.log('Registration successful, user:', newUser)
      return {
        success: true,
        user: newUser,
        requiresEmailVerification: requires_email_verification || false
      }
    } catch (error) {
      console.error('Registration error:', error)
      console.error('Error response:', error.response)
      const message = error.response?.data?.message || 'Error al registrarse'
      const errors = error.response?.data?.errors || {}
      return { success: false, message, errors }
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    try {
      await authService.logout()
    } catch (error) {
      console.error('Error during logout:', error)
    } finally {
      user.value = null
      token.value = null
      authService.removeToken()
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return

    loading.value = true
    try {
      const response = await authService.getUser()
      user.value = response.data.data || response.data
    } catch (error) {
      console.error('Error fetching user:', error)
      // Si el token es inválido, limpiar sesión
      if (error.response?.status === 401) {
        await logout()
      }
    } finally {
      loading.value = false
    }
  }

  async function updateProfile(profileData) {
    loading.value = true
    try {
      const response = await authService.updateProfile(profileData)
      user.value = response.data.data || response.data
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al actualizar perfil'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function changePassword(passwordData) {
    loading.value = true
    try {
      await authService.changePassword(passwordData)
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al cambiar contraseña'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  // ========== Verificación con código de 6 dígitos ==========

  async function sendVerificationCode(email) {
    loading.value = true
    try {
      await authService.sendVerificationCode(email)
      return { success: true, message: 'Código enviado exitosamente' }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al enviar código'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function verifyEmail(email, code) {
    loading.value = true
    try {
      const response = await authService.verifyEmail(email, code)
      // Actualizar el usuario local si está autenticado
      if (user.value && user.value.email === email) {
        user.value.email_verified_at = new Date().toISOString()
      }
      return { success: true, message: 'Email verificado exitosamente', user: response.data.user }
    } catch (error) {
      const message = error.response?.data?.message || 'Código inválido o expirado'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function resendVerificationCode(email, type = 'email_verification') {
    loading.value = true
    try {
      await authService.resendVerificationCode(email, type)
      return { success: true, message: 'Código reenviado exitosamente' }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al reenviar código'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function sendPasswordResetCode(email) {
    loading.value = true
    try {
      await authService.sendPasswordResetCode(email)
      return { success: true, message: 'Código enviado exitosamente' }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al enviar código'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function verifyPasswordResetCode(email, code) {
    loading.value = true
    try {
      await authService.verifyPasswordResetCode(email, code)
      return { success: true, message: 'Código verificado' }
    } catch (error) {
      const message = error.response?.data?.message || 'Código inválido o expirado'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function resetPasswordWithCode(email, code, password, password_confirmation) {
    loading.value = true
    try {
      await authService.resetPasswordWithCode({ email, code, password, password_confirmation })
      return { success: true, message: 'Contraseña restablecida exitosamente' }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al restablecer contraseña'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  // Computed para verificar si el email está verificado
  const isEmailVerified = computed(() => !!user.value?.email_verified_at)

  // Initialize: Fetch user if token exists
  function init() {
    if (token.value) {
      fetchUser()
    }
  }

  return {
    // State
    user,
    token,
    loading,
    // Getters
    isAuthenticated,
    isAdmin,
    isManager,
    isCustomer,
    hasManagerAccess,
    userName,
    userEmail,
    userRole,
    isEmailVerified,
    // Actions
    login,
    register,
    logout,
    fetchUser,
    updateProfile,
    changePassword,
    // Verification actions
    sendVerificationCode,
    verifyEmail,
    resendVerificationCode,
    sendPasswordResetCode,
    verifyPasswordResetCode,
    resetPasswordWithCode,
    init
  }
})
