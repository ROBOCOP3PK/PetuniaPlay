# Ejemplos de Uso - Skeleton Components

## Ejemplo 1: Loading State en Vista de Productos

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Productos</h1>

    <!-- Skeleton mientras carga -->
    <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <SkeletonCard v-for="n in 8" :key="n" />
    </div>

    <!-- Productos reales cuando carga -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import { SkeletonCard } from '@/components/ui'
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()
const isLoading = ref(true)
const products = ref([])

onMounted(async () => {
  isLoading.value = true
  try {
    products.value = await productStore.fetchProducts()
  } finally {
    isLoading.value = false
  }
})
</script>
```

## Ejemplo 2: Tabla de Administración con Loading

```vue
<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Gestión de Productos</h2>

    <!-- Skeleton para tabla -->
    <SkeletonTable
      v-if="isLoading"
      :rows="10"
      :columns="6"
      :show-header="true"
    />

    <!-- Tabla real -->
    <div v-else>
      <table class="w-full">
        <thead class="bg-gray-100 dark:bg-gray-800">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>${{ product.price }}</td>
            <td>{{ product.stock }}</td>
            <td>{{ product.category }}</td>
            <td>
              <button>Editar</button>
              <button>Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { SkeletonTable } from '@/components/ui'
import api from '@/services/api'

const isLoading = ref(true)
const products = ref([])

onMounted(async () => {
  isLoading.value = true
  try {
    const response = await api.get('/admin/products')
    products.value = response.data
  } finally {
    isLoading.value = false
  }
})
</script>
```

## Ejemplo 3: Detalle de Producto con Skeleton Text

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
      <!-- Imagen del producto -->
      <div>
        <SkeletonCard v-if="isLoading" height="500px" />
        <img v-else :src="product.image" :alt="product.name" />
      </div>

      <!-- Información del producto -->
      <div>
        <div v-if="isLoading" class="space-y-4">
          <!-- Título -->
          <SkeletonText :lines="1" />

          <!-- Categoría -->
          <SkeletonText :lines="1" :width="30" />

          <!-- Precio -->
          <SkeletonText :lines="1" :width="40" />

          <!-- Descripción -->
          <div class="mt-6">
            <SkeletonText :lines="5" />
          </div>

          <!-- Características -->
          <div class="mt-6">
            <SkeletonText :lines="3" :width="80" />
          </div>
        </div>

        <div v-else>
          <h1 class="text-3xl font-bold">{{ product.name }}</h1>
          <p class="text-gray-500">{{ product.category }}</p>
          <p class="text-2xl font-bold text-primary">${{ product.price }}</p>
          <p class="mt-6">{{ product.description }}</p>
          <ul class="mt-6">
            <li v-for="feature in product.features" :key="feature">
              {{ feature }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { SkeletonCard, SkeletonText } from '@/components/ui'
import api from '@/services/api'

const route = useRoute()
const isLoading = ref(true)
const product = ref(null)

onMounted(async () => {
  isLoading.value = true
  try {
    const response = await api.get(`/products/${route.params.slug}`)
    product.value = response.data
  } finally {
    isLoading.value = false
  }
})
</script>
```

## Ejemplo 4: Lista con Infinite Scroll

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Productos cargados -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />

      <!-- Skeletons al final para paginación/infinite scroll -->
      <SkeletonCard v-if="isLoadingMore" v-for="n in 4" :key="`skeleton-${n}`" />
    </div>

    <!-- Botón cargar más -->
    <div v-if="hasMore && !isLoadingMore" class="text-center mt-8">
      <button @click="loadMore" class="btn-primary">
        Cargar más productos
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import { SkeletonCard } from '@/components/ui'
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()
const products = ref([])
const isLoadingMore = ref(false)
const hasMore = ref(true)
const page = ref(1)

const loadMore = async () => {
  isLoadingMore.value = true
  try {
    const newProducts = await productStore.fetchProducts({ page: page.value + 1 })
    products.value.push(...newProducts)
    page.value++

    if (newProducts.length === 0) {
      hasMore.value = false
    }
  } finally {
    isLoadingMore.value = false
  }
}
</script>
```

## Ejemplo 5: Combinación de Múltiples Skeletons

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="isLoading">
      <!-- Header skeleton -->
      <div class="mb-8">
        <SkeletonText :lines="1" :width="50" />
        <SkeletonText :lines="2" :width="80" class="mt-2" />
      </div>

      <!-- Grid de productos -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <SkeletonCard v-for="n in 6" :key="n" />
      </div>

      <!-- Tabla de detalles -->
      <SkeletonTable :rows="5" :columns="3" />
    </div>

    <div v-else>
      <!-- Contenido real aquí -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { SkeletonCard, SkeletonTable, SkeletonText } from '@/components/ui'

const isLoading = ref(true)

onMounted(async () => {
  // Simular carga
  await new Promise(resolve => setTimeout(resolve, 2000))
  isLoading.value = false
})
</script>
```

## Ejemplo 6: Con Transiciones Suaves

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <Transition name="fade" mode="out-in">
      <!-- Skeleton -->
      <div v-if="isLoading" key="skeleton" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <SkeletonCard v-for="n in 8" :key="n" />
      </div>

      <!-- Contenido real -->
      <div v-else key="content" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="product"
        />
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import { SkeletonCard } from '@/components/ui'
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()
const isLoading = ref(true)
const products = ref([])

onMounted(async () => {
  isLoading.value = true
  try {
    products.value = await productStore.fetchProducts()
  } finally {
    isLoading.value = false
  }
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
```

## Ejemplo 7: Con Manejo de Errores

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Loading state -->
    <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <SkeletonCard v-for="n in 8" :key="n" />
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="text-center py-12">
      <i class="pi pi-exclamation-triangle text-red-500 text-5xl mb-4"></i>
      <h2 class="text-2xl font-bold mb-2">Error al cargar productos</h2>
      <p class="text-gray-600 mb-4">{{ error }}</p>
      <button @click="retry" class="btn-primary">
        Reintentar
      </button>
    </div>

    <!-- Empty state -->
    <div v-else-if="products.length === 0" class="text-center py-12">
      <i class="pi pi-inbox text-gray-400 text-5xl mb-4"></i>
      <h2 class="text-2xl font-bold mb-2">No hay productos</h2>
      <p class="text-gray-600">No se encontraron productos disponibles.</p>
    </div>

    <!-- Success state -->
    <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import { SkeletonCard } from '@/components/ui'
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()
const isLoading = ref(true)
const error = ref(null)
const products = ref([])

const loadProducts = async () => {
  isLoading.value = true
  error.value = null

  try {
    products.value = await productStore.fetchProducts()
  } catch (err) {
    error.value = err.message || 'Error desconocido'
  } finally {
    isLoading.value = false
  }
}

const retry = () => {
  loadProducts()
}

onMounted(() => {
  loadProducts()
})
</script>
```

## Mejores Prácticas

1. **Simular estructura real**: El skeleton debe imitar la estructura del contenido real
2. **Cantidad apropiada**: Mostrar cantidad similar a la esperada
3. **Transiciones suaves**: Usar transiciones CSS para cambios fluidos
4. **Timeout razonable**: No mostrar skeletons eternamente
5. **Manejo de errores**: Siempre tener estados de error y vacío
6. **Performance**: Los skeletons son ligeros, usar libremente
7. **Accesibilidad**: Mantener buena UX para todos los usuarios
