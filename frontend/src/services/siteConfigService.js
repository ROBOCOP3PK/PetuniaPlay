import api from './api'

export const siteConfigService = {
  // Obtener configuración actual (público)
  getConfig() {
    return api.get('/site-config')
  },

  // Actualizar configuración (solo admin)
  updateConfig(configData) {
    return api.put('/site-config', configData)
  }
}

export default siteConfigService
