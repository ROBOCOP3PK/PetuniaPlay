import { ref, onUnmounted } from 'vue'

/**
 * Composable con utilidades de optimización de rendimiento
 */
export function usePerformance() {
  /**
   * Debounce - Retrasa la ejecución de una función
   * @param {Function} fn - Función a ejecutar
   * @param {number} delay - Tiempo de espera en ms
   * @returns {Function} Función debounced
   */
  const debounce = (fn, delay = 300) => {
    let timeoutId = null

    const debouncedFn = (...args) => {
      clearTimeout(timeoutId)
      timeoutId = setTimeout(() => {
        fn(...args)
      }, delay)
    }

    // Cleanup en caso de que el componente se desmonte
    onUnmounted(() => {
      if (timeoutId) clearTimeout(timeoutId)
    })

    return debouncedFn
  }

  /**
   * Throttle - Limita la frecuencia de ejecución de una función
   * @param {Function} fn - Función a ejecutar
   * @param {number} limit - Tiempo mínimo entre ejecuciones en ms
   * @returns {Function} Función throttled
   */
  const throttle = (fn, limit = 300) => {
    let inThrottle = false

    return (...args) => {
      if (!inThrottle) {
        fn(...args)
        inThrottle = true
        setTimeout(() => {
          inThrottle = false
        }, limit)
      }
    }
  }

  /**
   * Hook de Intersection Observer para lazy loading
   * @param {Function} callback - Función a ejecutar cuando el elemento es visible
   * @param {Object} options - Opciones del IntersectionObserver
   * @returns {Object} Observer ref
   */
  const useIntersectionObserver = (callback, options = {}) => {
    const target = ref(null)
    let observer = null

    const defaultOptions = {
      root: null,
      rootMargin: '50px',
      threshold: 0.1,
      ...options
    }

    if (typeof window !== 'undefined' && 'IntersectionObserver' in window) {
      observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            callback(entry)
            if (observer && entry.target) {
              observer.unobserve(entry.target)
            }
          }
        })
      }, defaultOptions)
    }

    onUnmounted(() => {
      if (observer) {
        observer.disconnect()
      }
    })

    return {
      target,
      observe: (element) => {
        if (observer && element) {
          observer.observe(element)
        }
      },
      unobserve: (element) => {
        if (observer && element) {
          observer.unobserve(element)
        }
      }
    }
  }

  /**
   * Memoización simple para cachear resultados de funciones
   * @param {Function} fn - Función a memoizar
   * @returns {Function} Función memoizada
   */
  const memoize = (fn) => {
    const cache = new Map()

    return (...args) => {
      const key = JSON.stringify(args)

      if (cache.has(key)) {
        return cache.get(key)
      }

      const result = fn(...args)
      cache.set(key, result)

      // Limitar el tamaño del cache
      if (cache.size > 100) {
        const firstKey = cache.keys().next().value
        cache.delete(firstKey)
      }

      return result
    }
  }

  /**
   * Formateo de precio memoizado para mejor rendimiento
   */
  const formatPrice = memoize((price) => {
    return new Intl.NumberFormat('es-CO').format(price)
  })

  /**
   * Request Animation Frame para animaciones optimizadas
   * @param {Function} callback - Función a ejecutar en el frame
   */
  const useRAF = (callback) => {
    let rafId = null

    const start = () => {
      const animate = (timestamp) => {
        callback(timestamp)
        rafId = requestAnimationFrame(animate)
      }
      rafId = requestAnimationFrame(animate)
    }

    const stop = () => {
      if (rafId) {
        cancelAnimationFrame(rafId)
        rafId = null
      }
    }

    onUnmounted(() => {
      stop()
    })

    return { start, stop }
  }

  /**
   * Precarga de imágenes
   * @param {Array<string>} urls - Array de URLs de imágenes
   * @returns {Promise} Promise que se resuelve cuando todas las imágenes cargan
   */
  const preloadImages = (urls) => {
    return Promise.all(
      urls.map(url => {
        return new Promise((resolve, reject) => {
          const img = new Image()
          img.onload = () => resolve(url)
          img.onerror = () => reject(new Error(`Failed to load image: ${url}`))
          img.src = url
        })
      })
    )
  }

  return {
    debounce,
    throttle,
    useIntersectionObserver,
    memoize,
    formatPrice,
    useRAF,
    preloadImages
  }
}
