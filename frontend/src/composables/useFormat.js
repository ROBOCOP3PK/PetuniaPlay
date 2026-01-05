import { ref } from 'vue'

// Singleton del formateador de precios (formato COP colombiano)
// Formato: $1.234.567 (punto como separador de miles, sin decimales para COP)
const priceFormatter = new Intl.NumberFormat('es-CO', {
  style: 'decimal',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0
})

// Formateador con decimales para casos especiales (ej: peso de mascotas)
const decimalFormatter = new Intl.NumberFormat('es-CO', {
  style: 'decimal',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
})

const dateFormatter = new Intl.DateTimeFormat('es-CO', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
})

export function useFormat() {
  /**
   * Formatea un precio en formato colombiano con símbolo de peso
   * @param {number} price - El precio a formatear
   * @param {boolean} showDecimals - Mostrar decimales (default: false)
   * @returns {string} Precio formateado (ej: $1.234.567)
   */
  const formatPrice = (price, showDecimals = false) => {
    if (price === null || price === undefined || isNaN(price)) return '$0'
    const formatter = showDecimals ? decimalFormatter : priceFormatter
    return '$' + formatter.format(price)
  }

  /**
   * Formatea un número en formato colombiano (sin símbolo de moneda)
   * @param {number} number - El número a formatear
   * @param {number} decimals - Cantidad de decimales (default: 0)
   * @returns {string} Número formateado (ej: 1.234.567)
   */
  const formatNumber = (number, decimals = 0) => {
    if (number === null || number === undefined || isNaN(number)) return '0'
    const formatter = new Intl.NumberFormat('es-CO', {
      style: 'decimal',
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals
    })
    return formatter.format(number)
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
    formatNumber,
    formatDate
  }
}
