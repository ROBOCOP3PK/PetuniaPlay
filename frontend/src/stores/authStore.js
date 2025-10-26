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
  const userName = computed(() => user.value?.name || '')
  const userEmail = computed(() => user.value?.email || '')

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
    loading.value = true
    try {
      const response = await authService.register(userData)
      const { user: newUser, token: authToken } = response.data

      user.value = newUser
      token.value = authToken
      authService.setToken(authToken)

      return { success: true, user: newUser }
    } catch (error) {
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
    userName,
    userEmail,
    // Actions
    login,
    register,
    logout,
    fetchUser,
    updateProfile,
    changePassword,
    init
  }
})
