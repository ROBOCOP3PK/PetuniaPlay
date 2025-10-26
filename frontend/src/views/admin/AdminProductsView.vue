<template>
  <AdminLayout>
    <div>
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-dark">Gestión de Productos</h1>
        <button
          @click="openCreateModal"
          class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition"
        >
          + Nuevo Producto
        </button>
      </div>

      <!-- Search and Filters -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por nombre o SKU..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          />
          <select
            v-model="filterCategory"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Todas las categorías</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <select
            v-model="filterStock"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Todos los productos</option>
            <option value="in_stock">En stock</option>
            <option value="low_stock">⚠️ Bajo stock</option>
            <option value="out_of_stock">❌ Sin stock</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600">Cargando productos...</p>
      </div>

      <!-- Products Table -->
      <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Categoría</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Precio</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Stock</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="product in filteredProducts"
                :key="product.id"
                class="hover:bg-gray-50 transition"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <img
                      v-if="product.primary_image"
                      :src="product.primary_image.image_url"
                      :alt="product.name"
                      class="w-12 h-12 object-cover rounded"
                    />
                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center" v-else>
                      <span class="text-gray-400 text-xs">Sin imagen</span>
                    </div>
                    <div>
                      <p class="font-semibold text-dark">{{ product.name }}</p>
                      <p class="text-sm text-gray-600">{{ truncate(product.description, 40) }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ product.sku }}</code>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm text-gray-700">{{ product.category?.name }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div v-if="editingProduct === product.id">
                    <input
                      v-model.number="product.price"
                      type="number"
                      class="w-24 px-2 py-1 border rounded text-right"
                      @blur="saveProduct(product)"
                    />
                  </div>
                  <div v-else class="cursor-pointer" @click="editingProduct = product.id">
                    <p class="font-bold text-primary">${{ formatPrice(product.price) }}</p>
                    <p v-if="product.sale_price" class="text-sm text-green-600">
                      Oferta: ${{ formatPrice(product.sale_price) }}
                    </p>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div v-if="editingStock === product.id">
                    <input
                      v-model.number="product.stock"
                      type="number"
                      class="w-20 px-2 py-1 border rounded text-right"
                      @blur="saveStock(product)"
                    />
                  </div>
                  <div v-else class="flex items-center justify-end space-x-2">
                    <span
                      class="font-bold cursor-pointer"
                      :class="{
                        'text-red-600': product.is_out_of_stock,
                        'text-yellow-600': product.is_low_stock,
                        'text-green-600': product.in_stock && !product.is_low_stock
                      }"
                      @click="editingStock = product.id"
                    >
                      {{ product.stock }}
                    </span>
                    <!-- Badge de Stock Bajo -->
                    <span
                      v-if="product.is_low_stock"
                      class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-semibold"
                      :title="`Stock por debajo del umbral (${product.low_stock_threshold})`"
                    >
                      ⚠️ Bajo
                    </span>
                    <!-- Badge de Sin Stock -->
                    <span
                      v-if="product.is_out_of_stock"
                      class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold"
                    >
                      ❌ Agotado
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span
                    v-if="product.is_active"
                    class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold"
                  >
                    Activo
                  </span>
                  <span
                    v-else
                    class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-semibold"
                  >
                    Inactivo
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end space-x-2">
                    <button
                      @click="editProduct(product)"
                      class="text-blue-600 hover:text-blue-700 p-2"
                      title="Editar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteProduct(product)"
                      class="text-red-600 hover:text-red-700 p-2"
                      title="Eliminar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredProducts.length === 0" class="text-center py-12">
          <p class="text-gray-600">No se encontraron productos</p>
        </div>
      </div>

      <!-- Product Modal (Create/Edit) -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="closeModal"
      >
        <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6 border-b sticky top-0 bg-white">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold">
                {{ editingProductData ? 'Editar Producto' : 'Nuevo Producto' }}
              </h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <form @submit.prevent="saveProductForm" class="p-6 space-y-6">
            <!-- Basic Information -->
            <div>
              <h3 class="font-bold text-lg mb-3">Información Básica</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                  <input
                    v-model="productForm.name"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría *</label>
                  <select
                    v-model="productForm.category_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  >
                    <option value="">Seleccionar categoría</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">SKU</label>
                  <input
                    v-model="productForm.sku"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción Corta *</label>
                  <textarea
                    v-model="productForm.description"
                    required
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  ></textarea>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción Larga</label>
                  <textarea
                    v-model="productForm.long_description"
                    rows="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Pricing & Stock -->
            <div>
              <h3 class="font-bold text-lg mb-3">Precio e Inventario</h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Precio Regular *</label>
                  <input
                    v-model.number="productForm.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Precio de Oferta</label>
                  <input
                    v-model.number="productForm.sale_price"
                    type="number"
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Stock *</label>
                  <input
                    v-model.number="productForm.stock"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Umbral de Stock Bajo *
                    <span class="text-xs text-gray-500 font-normal">(alerta cuando stock ≤ este valor)</span>
                  </label>
                  <input
                    v-model.number="productForm.low_stock_threshold"
                    type="number"
                    min="1"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Peso (kg)</label>
                  <input
                    v-model.number="productForm.weight"
                    type="number"
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                </div>
              </div>
            </div>

            <!-- Images -->
            <div>
              <h3 class="font-bold text-lg mb-3">Imágenes del Producto</h3>
              <p class="text-sm text-gray-600 mb-3">La primera imagen será la imagen principal</p>
              <ImageUpload
                v-model="productForm.images"
                :multiple="true"
                :max-files="10"
                type="product"
              />
            </div>

            <!-- Status -->
            <div>
              <h3 class="font-bold text-lg mb-3">Estado</h3>
              <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="productForm.is_active"
                    type="checkbox"
                    class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary"
                  />
                  <span class="text-sm font-semibold text-gray-700">Producto activo</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="productForm.is_featured"
                    type="checkbox"
                    class="w-5 h-5 text-primary rounded focus:ring-2 focus:ring-primary"
                  />
                  <span class="text-sm font-semibold text-gray-700">Producto destacado</span>
                </label>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t">
              <button
                type="button"
                @click="closeModal"
                class="px-6 py-2 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="savingProduct"
                class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition disabled:opacity-50"
              >
                {{ savingProduct ? 'Guardando...' : (editingProductData ? 'Actualizar' : 'Crear Producto') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import ImageUpload from '../../components/admin/ImageUpload.vue'
import { useProductStore } from '../../stores/productStore'
import { useCategoryStore } from '../../stores/categoryStore'
import { useToast } from 'vue-toastification'
import { adminService } from '../../services/adminService'

const productStore = useProductStore()
const categoryStore = useCategoryStore()
const toast = useToast()

const loading = ref(false)
const search = ref('')
const filterCategory = ref('')
const filterStock = ref('')
const editingProduct = ref(null)
const editingStock = ref(null)

// Modal state
const showModal = ref(false)
const editingProductData = ref(null)
const savingProduct = ref(false)

// Product form
const emptyForm = {
  name: '',
  category_id: '',
  sku: '',
  description: '',
  long_description: '',
  price: 0,
  sale_price: null,
  stock: 0,
  low_stock_threshold: 10,
  weight: null,
  is_active: true,
  is_featured: false,
  images: []
}

const productForm = ref({ ...emptyForm })

const categories = computed(() => categoryStore.categories)

const filteredProducts = computed(() => {
  let filtered = productStore.products

  // Search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(p =>
      p.name.toLowerCase().includes(searchLower) ||
      p.sku.toLowerCase().includes(searchLower)
    )
  }

  // Category filter
  if (filterCategory.value) {
    filtered = filtered.filter(p => p.category_id === parseInt(filterCategory.value))
  }

  // Stock filter
  if (filterStock.value === 'in_stock') {
    filtered = filtered.filter(p => p.in_stock && !p.is_low_stock)
  } else if (filterStock.value === 'low_stock') {
    filtered = filtered.filter(p => p.is_low_stock)
  } else if (filterStock.value === 'out_of_stock') {
    filtered = filtered.filter(p => p.is_out_of_stock)
  }

  return filtered
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

// Quick edit price
const saveProduct = async (product) => {
  try {
    await adminService.updateProduct(product.id, {
      price: product.price,
      sale_price: product.sale_price
    })
    editingProduct.value = null
    toast.success('Precio actualizado')
    await productStore.fetchProducts()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error al actualizar precio')
    await productStore.fetchProducts() // Recargar para restaurar valores
  }
}

// Quick edit stock
const saveStock = async (product) => {
  try {
    await adminService.updateProduct(product.id, {
      stock: product.stock
    })
    editingStock.value = null
    toast.success('Stock actualizado')
    await productStore.fetchProducts()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error al actualizar stock')
    await productStore.fetchProducts() // Recargar para restaurar valores
  }
}

// Modal functions
const openCreateModal = () => {
  editingProductData.value = null
  productForm.value = { ...emptyForm, images: [] }
  showModal.value = true
}

const editProduct = (product) => {
  editingProductData.value = product
  productForm.value = {
    name: product.name,
    category_id: product.category_id,
    sku: product.sku || '',
    description: product.description,
    long_description: product.long_description || '',
    price: product.price,
    sale_price: product.sale_price || null,
    stock: product.stock,
    weight: product.weight || null,
    is_active: product.is_active,
    is_featured: product.is_featured || false,
    images: product.images?.length > 0
      ? product.images.map(img => img.image_url)
      : []
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingProductData.value = null
  productForm.value = { ...emptyForm }
}

const saveProductForm = async () => {
  savingProduct.value = true
  try {
    const productData = {
      category_id: productForm.value.category_id,
      name: productForm.value.name,
      sku: productForm.value.sku || null,
      description: productForm.value.description,
      long_description: productForm.value.long_description || null,
      price: productForm.value.price,
      sale_price: productForm.value.sale_price || null,
      stock: productForm.value.stock,
      weight: productForm.value.weight || null,
      is_active: productForm.value.is_active,
      is_featured: productForm.value.is_featured,
      images: productForm.value.images.filter(url => url && url.trim() !== '')
    }

    if (editingProductData.value) {
      // Update existing product
      await adminService.updateProduct(editingProductData.value.id, productData)
      toast.success('Producto actualizado exitosamente')
    } else {
      // Create new product
      await adminService.createProduct(productData)
      toast.success('Producto creado exitosamente')
    }

    closeModal()
    await productStore.fetchProducts()
  } catch (error) {
    const errorMsg = error.response?.data?.message || error.response?.data?.errors
    if (typeof errorMsg === 'object') {
      // Mostrar el primer error de validación
      const firstError = Object.values(errorMsg)[0]
      toast.error(Array.isArray(firstError) ? firstError[0] : firstError)
    } else {
      toast.error(errorMsg || 'Error al guardar producto')
    }
  } finally {
    savingProduct.value = false
  }
}

const deleteProduct = async (product) => {
  if (confirm(`¿Estás seguro de eliminar "${product.name}"?\n\nEsta acción no se puede deshacer.`)) {
    try {
      await adminService.deleteProduct(product.id)
      toast.success('Producto eliminado exitosamente')
      await productStore.fetchProducts()
    } catch (error) {
      toast.error(error.response?.data?.message || 'Error al eliminar producto')
    }
  }
}

const loadData = async () => {
  loading.value = true
  try {
    await Promise.all([
      productStore.fetchProducts(),
      categoryStore.fetchCategories()
    ])
  } catch (error) {
    toast.error('Error al cargar productos')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>
