<template>
  <AdminLayout>
    <div>
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-dark">Gestión de Productos</h1>
        <button class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">
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
            <option value="low_stock">Bajo stock (≤10)</option>
            <option value="out_of_stock">Sin stock</option>
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
                  <span
                    v-else
                    class="font-bold cursor-pointer"
                    :class="{
                      'text-red-600': product.stock === 0,
                      'text-yellow-600': product.stock > 0 && product.stock <= 10,
                      'text-green-600': product.stock > 10
                    }"
                    @click="editingStock = product.id"
                  >
                    {{ product.stock }}
                  </span>
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { useProductStore } from '../../stores/productStore'
import { useCategoryStore } from '../../stores/categoryStore'
import { useToast } from 'vue-toastification'

const productStore = useProductStore()
const categoryStore = useCategoryStore()
const toast = useToast()

const loading = ref(false)
const search = ref('')
const filterCategory = ref('')
const filterStock = ref('')
const editingProduct = ref(null)
const editingStock = ref(null)

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
    filtered = filtered.filter(p => p.stock > 10)
  } else if (filterStock.value === 'low_stock') {
    filtered = filtered.filter(p => p.stock > 0 && p.stock <= 10)
  } else if (filterStock.value === 'out_of_stock') {
    filtered = filtered.filter(p => p.stock === 0)
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

const saveProduct = async (product) => {
  // TODO: Implement update product API call
  editingProduct.value = null
  toast.success('Precio actualizado')
}

const saveStock = async (product) => {
  // TODO: Implement update stock API call
  editingStock.value = null
  toast.success('Stock actualizado')
}

const editProduct = (product) => {
  // TODO: Open edit modal
  toast.info('Funcionalidad de edición completa próximamente')
}

const deleteProduct = async (product) => {
  if (confirm(`¿Estás seguro de eliminar "${product.name}"?`)) {
    // TODO: Implement delete API call
    toast.success('Producto eliminado')
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
