import axios from 'axios'

// Crear instancia de axios con configuración base
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  timeout: 30000, // 30 segundos para operaciones de pago
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true // Importante para CORS con Sanctum
})

// Interceptor para requests - agregar token si existe
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Interceptor para responses - manejar errores globalmente
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response) {
      // El servidor respondió con un código de error
      switch (error.response.status) {
        case 401:
          // No autenticado - limpiar token y redirigir a login
          localStorage.removeItem('auth_token')
          // router.push('/login') // Descomentar cuando tengamos el router
          break
        case 403:
          console.error('No tienes permisos para realizar esta acción')
          break
        case 404:
          console.error('Recurso no encontrado')
          break
        case 500:
          console.error('Error del servidor')
          break
        default:
          console.error('Error:', error.response.data.message || 'Error desconocido')
      }
    } else if (error.request) {
      console.error('No se pudo conectar con el servidor')
    } else {
      console.error('Error:', error.message)
    }
    return Promise.reject(error)
  }
)

export default api
