import { ref } from 'vue'

// Singleton del formateador (creado UNA sola vez)
const priceFormatter = new Intl.NumberFormat('es-CO', {
  style: 'decimal',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0
})

const dateFormatter = new Intl.DateTimeFormat('es-CO', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
})

export function useFormat() {
  /**
   * Formatea un precio en formato colombiano
   * @param {number} price - El precio a formatear
   * @returns {string} Precio formateado
   */
  const formatPrice = (price) => {
    if (price === null || price === undefined) return '0'
    return priceFormatter.format(price)
  }

  /**
   * Formatea una fecha en formato legible colombiano
   * @param {string|Date} date - La fecha a formatear
   * @returns {string} Fecha formateada
   */
  const formatDate = (date) => {
    if (!date) return ''
    return dateFormatter.format(new Date(date))
  }

  return {
    formatPrice,
    formatDate
  }
}
