import api from './api'

export const shippingConfigService = {
  // Obtener configuración actual (público)
  getConfig() {
    return api.get('/shipping-config')
  },

  // Actualizar configuración (solo manager/admin)
  updateConfig(configData) {
    return api.put('/shipping-config', configData)
  }
}

export default shippingConfigService
