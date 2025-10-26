import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
  // State
  const items = ref([])
  const loading = ref(false)

  // Load cart from localStorage on initialization
  const loadCart = () => {
    const savedCart = localStorage.getItem('petunia_cart')
    if (savedCart) {
      try {
        items.value = JSON.parse(savedCart)
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
      const price = parseFloat(item.product.final_price)
      return total + (price * item.quantity)
    }, 0)
  })

  const tax = computed(() => {
    // IVA del 19% en Colombia
    return subtotal.value * 0.19
  })

  const shipping = computed(() => {
    // Envío gratis para compras mayores a $100,000
    if (subtotal.value >= 100000) {
      return 0
    }
    // Envío fijo de $10,000 para compras menores
    return 10000
  })

  const total = computed(() => {
    return subtotal.value + tax.value + shipping.value
  })

  const hasItems = computed(() => items.value.length > 0)

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
          final_price: product.final_price,
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

  // Initialize cart from localStorage
  loadCart()

  return {
    // State
    items,
    loading,
    // Getters
    itemCount,
    subtotal,
    tax,
    shipping,
    total,
    hasItems,
    // Actions
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    getItemQuantity,
    loadCart,
  }
})
