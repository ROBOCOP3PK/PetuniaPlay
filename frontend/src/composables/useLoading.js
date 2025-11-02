import { ref, computed } from 'vue'

/**
 * Estado global de loading compartido entre todas las instancias
 * @type {Map<string, import('vue').Ref<boolean>>}
 */
const loadingStates = new Map()

/**
 * Key por defecto para el estado de loading global
 */
const DEFAULT_KEY = '__default__'

/**
 * Composable para gestionar estados de carga de forma centralizada
 *
 * @example
 * // Uso básico (loading global)
 * const { isLoading, startLoading, stopLoading } = useLoading()
 * startLoading()
 * await fetchData()
 * stopLoading()
 *
 * @example
 * // Uso con keys (múltiples loaders)
 * const { isLoading, startLoading, stopLoading } = useLoading()
 * startLoading('products')
 * await fetchProducts()
 * stopLoading('products')
 * console.log(isLoading('products'))
 *
 * @example
 * // Uso con helper async
 * const { withLoading } = useLoading()
 * await withLoading(async () => {
 *   await fetchData()
 * })
 *
 * @example
 * // Helper async con key
 * const { withLoading } = useLoading()
 * await withLoading('products', async () => {
 *   await fetchProducts()
 * })
 *
 * @returns {Object} API del composable
 */
export function useLoading() {
  /**
   * Obtiene o crea el ref de loading para una key específica
   * @param {string} key - Identificador del estado de loading
   * @returns {import('vue').Ref<boolean>}
   */
  const getLoadingState = (key = DEFAULT_KEY) => {
    if (!loadingStates.has(key)) {
      loadingStates.set(key, ref(false))
    }
    return loadingStates.get(key)
  }

  /**
   * Inicia el estado de carga
   * @param {string} [key] - Key opcional para identificar el loading específico
   */
  const startLoading = (key = DEFAULT_KEY) => {
    const state = getLoadingState(key)
    state.value = true
  }

  /**
   * Detiene el estado de carga
   * @param {string} [key] - Key opcional para identificar el loading específico
   */
  const stopLoading = (key = DEFAULT_KEY) => {
    const state = getLoadingState(key)
    state.value = false
  }

  /**
   * Verifica si está cargando
   * @param {string} [key] - Key opcional para verificar loading específico
   * @returns {import('vue').ComputedRef<boolean>}
   */
  const isLoading = (key = DEFAULT_KEY) => {
    const state = getLoadingState(key)
    return computed(() => state.value)
  }

  /**
   * Helper para ejecutar una función async con loading automático
   * Soporta dos formas de uso:
   * - withLoading(fn) - usa el loading global
   * - withLoading(key, fn) - usa un loading con key específica
   *
   * @param {string|Function} keyOrFn - Key del loading o función async
   * @param {Function} [fn] - Función async (si el primer parámetro es key)
   * @returns {Promise<any>} Resultado de la función ejecutada
   */
  const withLoading = async (keyOrFn, fn) => {
    // Determinar si el primer argumento es una key o una función
    const isKeyProvided = typeof keyOrFn === 'string'
    const key = isKeyProvided ? keyOrFn : DEFAULT_KEY
    const callback = isKeyProvided ? fn : keyOrFn

    if (typeof callback !== 'function') {
      throw new Error('withLoading requiere una función async como parámetro')
    }

    startLoading(key)
    try {
      return await callback()
    } finally {
      stopLoading(key)
    }
  }

  /**
   * Limpia el estado de loading de una key específica
   * Útil para liberar memoria cuando ya no se necesita un loading
   * @param {string} [key] - Key del loading a limpiar
   */
  const clearLoading = (key = DEFAULT_KEY) => {
    loadingStates.delete(key)
  }

  /**
   * Limpia todos los estados de loading
   * Útil para reset completo o cleanup
   */
  const clearAllLoading = () => {
    loadingStates.clear()
  }

  return {
    isLoading,
    startLoading,
    stopLoading,
    withLoading,
    clearLoading,
    clearAllLoading
  }
}
