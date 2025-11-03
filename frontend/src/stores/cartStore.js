import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import couponService from '../services/couponService'
import shippingConfigService from '../services/shippingConfigService'

export const useCartStore = defineStore('cart', () => {
  // State
  const items = ref([])
  const loading = ref(false)
  const appliedCoupon = ref(null)
  const couponDiscount = ref(0)
  const shippingConfig = ref(null)

  // Load cart from localStorage on initialization
  const loadCart = () => {
    const savedCart = localStorage.getItem('petunia_cart')
    if (savedCart) {
      try {
        const cartItems = JSON.parse(savedCart)
        // Asegurar que todos los items tengan final_price calculado
        items.value = cartItems.map(item => ({
          ...item,
          product: {
            ...item.product,
            final_price: item.product.final_price || item.product.sale_price || item.product.price
          }
        }))
        // Guardar los datos actualizados
        saveCart()
      } catch (error) {
        console.error('Error loading cart from localStorage:', error)
        items.value = []
      }
    }
  }

  // Save cart to localStorage
  const saveCart = () => {
    localStorage.setItem('petunia_cart', JSON.stringify(items.value))
  }

  // Getters
  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const subtotal = computed(() => {
    return items.value.reduce((total, item) => {
      const price = parseFloat(item.product.final_price || item.product.sale_price || item.product.price || 0)
      return total + (price * item.quantity)
    }, 0)
  })

  const tax = computed(() => {
    // IVA del 19% en Colombia
    return subtotal.value * 0.19
  })

  // Load shipping configuration from API
  const loadShippingConfig = async () => {
    try {
      const response = await shippingConfigService.getConfig()
      if (response.data.success) {
        shippingConfig.value = response.data.data
      }
    } catch (error) {
      console.error('Error loading shipping config:', error)
      // Use default values if API fails
      shippingConfig.value = {
        night_delivery_enabled: true,
        night_delivery_start_time: '21:00:00',
        night_delivery_end_time: '02:00:00',
        free_shipping_min_items: 4,
        standard_shipping_cost: 10000
      }
    }
  }

  // Método para calcular envío basado en ciudad y opciones de entrega
  const calculateShipping = (city = '', nightDelivery = false) => {
    // Si no hay configuración cargada, usar valores por defecto
    const config = shippingConfig.value || {
      night_delivery_enabled: true,
      free_shipping_min_items: 4,
      standard_shipping_cost: 10000
    }

    const totalItems = itemCount.value
    const cityNormalized = city.toLowerCase().trim()
    const isBogota = cityNormalized === 'bogotá' || cityNormalized === 'bogota'

    // Envío gratis en Bogotá si elige entrega nocturna y está habilitada
    if (isBogota && nightDelivery && config.night_delivery_enabled) {
      return 0
    }

    // Envío gratis en Bogotá si compra más artículos que el mínimo configurado
    if (isBogota && totalItems >= config.free_shipping_min_items) {
      return 0
    }

    // Para Bogotá sin cumplir condiciones, o para otras ciudades
    return parseFloat(config.standard_shipping_cost)
  }

  // Computed por defecto (sin ciudad específica)
  const shipping = computed(() => {
    return 10000 // Valor por defecto
  })

  const discount = computed(() => {
    return couponDiscount.value
  })

  const total = computed(() => {
    return subtotal.value + tax.value + shipping.value - discount.value
  })

  const hasItems = computed(() => items.value.length > 0)

  const hasCoupon = computed(() => appliedCoupon.value !== null)

  // Actions
  function addItem(product, quantity = 1) {
    // Verificar si el producto ya está en el carrito
    const existingItem = items.value.find(item => item.product.id === product.id)

    if (existingItem) {
      // Si ya existe, aumentar la cantidad
      const newQuantity = existingItem.quantity + quantity

      // Verificar stock
      if (newQuantity > product.stock) {
        throw new Error(`Solo hay ${product.stock} unidades disponibles`)
      }

      existingItem.quantity = newQuantity
    } else {
      // Si no existe, agregar nuevo item
      if (quantity > product.stock) {
        throw new Error(`Solo hay ${product.stock} unidades disponibles`)
      }

      items.value.push({
        product: {
          id: product.id,
          name: product.name,
          slug: product.slug,
          price: product.price,
          sale_price: product.sale_price,
          final_price: product.final_price || product.sale_price || product.price,
          has_discount: product.has_discount,
          discount_percentage: product.discount_percentage,
          stock: product.stock,
          sku: product.sku,
          primary_image: product.primary_image,
        },
        quantity: quantity,
        addedAt: new Date().toISOString()
      })
    }

    saveCart()
  }

  function removeItem(productId) {
    const index = items.value.findIndex(item => item.product.id === productId)
    if (index > -1) {
      items.value.splice(index, 1)
      saveCart()
    }
  }

  function updateQuantity(productId, quantity) {
    const item = items.value.find(item => item.product.id === productId)

    if (!item) return

    // Verificar stock
    if (quantity > item.product.stock) {
      throw new Error(`Solo hay ${item.product.stock} unidades disponibles`)
    }

    if (quantity <= 0) {
      removeItem(productId)
    } else {
      item.quantity = quantity
      saveCart()
    }
  }

  function clearCart() {
    items.value = []
    saveCart()
  }

  function getItemQuantity(productId) {
    const item = items.value.find(item => item.product.id === productId)
    return item ? item.quantity : 0
  }

  // Coupon functions
  async function applyCoupon(code) {
    if (!code || code.trim() === '') {
      throw new Error('Por favor ingresa un código de cupón')
    }

    try {
      const response = await couponService.validate(code.toUpperCase(), subtotal.value)

      if (response.data.valid) {
        appliedCoupon.value = response.data.data.coupon
        couponDiscount.value = response.data.data.discount

        return {
          success: true,
          message: response.data.message,
          discount: response.data.data.discount
        }
      } else {
        return {
          success: false,
          message: response.data.message
        }
      }
    } catch (error) {
      const message = error.response?.data?.message || 'Error al validar el cupón'
      return {
        success: false,
        message
      }
    }
  }

  function removeCoupon() {
    appliedCoupon.value = null
    couponDiscount.value = 0
  }

  function clearCoupon() {
    removeCoupon()
  }

  // Initialize cart from localStorage and load shipping config
  loadCart()
  loadShippingConfig()

  return {
    // State
    items,
    loading,
    appliedCoupon,
    couponDiscount,
    shippingConfig,
    // Getters
    itemCount,
    subtotal,
    tax,
    shipping,
    discount,
    total,
    hasItems,
    hasCoupon,
    // Actions
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    getItemQuantity,
    loadCart,
    loadShippingConfig,
    applyCoupon,
    removeCoupon,
    clearCoupon,
    calculateShipping,
  }
})
