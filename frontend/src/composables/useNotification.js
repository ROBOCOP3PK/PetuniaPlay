import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

export function useNotification() {
  const toast = useToast()
  const router = useRouter()

  // Configuración centralizada
  const DEFAULT_TIMEOUT = 3000

  /**
   * Notifica que un producto fue agregado al carrito
   */
  const notifyAddedToCart = (productName, quantity = 1) => {
    const message = quantity > 1
      ? `${quantity} x ${productName} agregados al carrito`
      : `${productName} agregado al carrito`

    toast.success(message, {
      timeout: DEFAULT_TIMEOUT,
      onClick: () => router.push('/cart')
    })
  }

  /**
   * Notifica éxito genérico
   */
  const notifySuccess = (message, options = {}) => {
    toast.success(message, {
      timeout: DEFAULT_TIMEOUT,
      ...options
    })
  }

  /**
   * Notifica error genérico
   */
  const notifyError = (message, options = {}) => {
    toast.error(message, {
      timeout: DEFAULT_TIMEOUT,
      ...options
    })
  }

  /**
   * Notifica advertencia
   */
  const notifyWarning = (message, options = {}) => {
    toast.warning(message, {
      timeout: DEFAULT_TIMEOUT,
      ...options
    })
  }

  /**
   * Notifica información
   */
  const notifyInfo = (message, options = {}) => {
    toast.info(message, {
      timeout: DEFAULT_TIMEOUT,
      ...options
    })
  }

  return {
    notifyAddedToCart,
    notifySuccess,
    notifyError,
    notifyWarning,
    notifyInfo
  }
}
