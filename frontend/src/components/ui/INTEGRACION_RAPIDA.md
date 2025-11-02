# Integración Rápida - Skeleton Components en PetuniaPlay

## Guía paso a paso para integrar skeletons en el proyecto actual

### 1. HomeView.vue - Vista Principal

**Ubicación**: `frontend/src/views/HomeView.vue`

**Cambio sugerido**:

```vue
<script setup>
import { ref, onMounted } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import { SkeletonCard } from '@/components/ui'  // ← AGREGAR
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()
const isLoading = ref(true)  // ← AGREGAR
const products = ref([])

onMounted(async () => {
  isLoading.value = true  // ← AGREGAR
  await productStore.fetchProducts()
  products.value = productStore.products
  isLoading.value = false  // ← AGREGAR
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Productos Destacados</h1>

    <!-- ⚡ AGREGAR SKELETON STATE -->
    <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <SkeletonCard v-for="n in 8" :key="`skeleton-${n}`" />
    </div>

    <!-- CONTENIDO ORIGINAL -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </div>
</template>
```

**Beneficios**:
- Feedback visual inmediato al cargar la página
- Mejor UX en conexiones lentas
- Evita "flash of empty content"

---

### 2. ProductDetailView.vue - Detalle de Producto

**Ubicación**: `frontend/src/views/ProductDetailView.vue`

**Cambio sugerido**:

```vue
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { SkeletonCard, SkeletonText } from '@/components/ui'  // ← AGREGAR
import { useProductStore } from '@/stores/productStore'

const route = useRoute()
const productStore = useProductStore()
const isLoading = ref(true)  // ← AGREGAR
const product = ref(null)

onMounted(async () => {
  isLoading.value = true  // ← AGREGAR
  await productStore.fetchProductBySlug(route.params.slug)
  product.value = productStore.currentProduct
  isLoading.value = false  // ← AGREGAR
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <!-- ⚡ SKELETON STATE -->
    <div v-if="isLoading" class="grid md:grid-cols-2 gap-8">
      <!-- Imagen -->
      <SkeletonCard height="500px" />

      <!-- Información -->
      <div class="space-y-4">
        <SkeletonText :lines="1" :width="80" />
        <SkeletonText :lines="1" :width="30" />
        <SkeletonText :lines="1" :width="40" />
        <div class="mt-6">
          <SkeletonText :lines="5" />
        </div>
        <SkeletonText :lines="3" :width="70" />
      </div>
    </div>

    <!-- CONTENIDO REAL -->
    <div v-else class="grid md:grid-cols-2 gap-8">
      <!-- Contenido original del producto aquí -->
    </div>
  </div>
</template>
```

---

### 3. CartView.vue - Carrito de Compras

**Ubicación**: `frontend/src/views/CartView.vue`

**Cambio sugerido**:

```vue
<script setup>
import { ref, computed } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import { SkeletonText } from '@/components/ui'  // ← AGREGAR

const cartStore = useCartStore()
const isLoading = ref(true)  // ← AGREGAR

// Simular carga inicial (si tienes sincronización con backend)
onMounted(async () => {
  isLoading.value = true
  await cartStore.syncWithBackend()  // Si aplica
  isLoading.value = false
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Carrito de Compras</h1>

    <!-- ⚡ SKELETON STATE -->
    <div v-if="isLoading" class="space-y-4">
      <div v-for="n in 3" :key="`skeleton-${n}`" class="card p-4">
        <div class="flex gap-4">
          <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded animate-pulse"></div>
          <div class="flex-1 space-y-2">
            <SkeletonText :lines="2" :width="80" />
            <SkeletonText :lines="1" :width="30" />
          </div>
        </div>
      </div>
    </div>

    <!-- CONTENIDO REAL -->
    <div v-else>
      <!-- Items del carrito -->
    </div>
  </div>
</template>
```

---

### 4. WishlistView.vue - Lista de Deseos

**Ubicación**: `frontend/src/views/WishlistView.vue`

**Ya está parcialmente implementado**. Verificar líneas actuales y agregar:

```vue
<script setup>
import { SkeletonCard } from '@/components/ui'  // ← AGREGAR si no existe
</script>

<template>
  <!-- Grid de wishlist items -->
  <div v-if="wishlistStore.loading" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <SkeletonCard v-for="n in 8" :key="`skeleton-${n}`" />
  </div>

  <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <ProductCard
      v-for="item in wishlistStore.items"
      :key="item.id"
      :product="item.product"
    />
  </div>
</template>
```

---

### 5. Admin Views - Panel de Administración

**Para tablas de productos/categorías/usuarios**:

```vue
<script setup>
import { SkeletonTable } from '@/components/ui'
</script>

<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Gestión de Productos</h2>

    <!-- ⚡ SKELETON STATE -->
    <SkeletonTable
      v-if="isLoading"
      :rows="10"
      :columns="6"
      :show-header="true"
    />

    <!-- TABLA REAL -->
    <table v-else>
      <!-- ... -->
    </table>
  </div>
</template>
```

---

### 6. CheckoutView.vue - Checkout

**Ubicación**: `frontend/src/views/CheckoutView.vue`

```vue
<script setup>
import { SkeletonText } from '@/components/ui'
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Resumen del pedido -->
    <div class="card p-6">
      <h3 class="text-xl font-bold mb-4">Resumen del Pedido</h3>

      <!-- ⚡ SKELETON STATE -->
      <div v-if="isLoading" class="space-y-3">
        <SkeletonText :lines="5" />
      </div>

      <!-- CONTENIDO REAL -->
      <div v-else>
        <!-- Items y totales -->
      </div>
    </div>
  </div>
</template>
```

---

## Store Integration - ProductStore

**Ubicación**: `frontend/src/stores/productStore.js`

**Agregar estado de loading**:

```js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useProductStore = defineStore('product', () => {
  const products = ref([])
  const currentProduct = ref(null)
  const isLoading = ref(false)  // ← AGREGAR
  const error = ref(null)       // ← AGREGAR

  const fetchProducts = async (params = {}) => {
    isLoading.value = true  // ← AGREGAR
    error.value = null

    try {
      const response = await api.get('/products', { params })
      products.value = response.data.data || response.data
      return products.value
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      isLoading.value = false  // ← AGREGAR
    }
  }

  const fetchProductBySlug = async (slug) => {
    isLoading.value = true  // ← AGREGAR
    error.value = null

    try {
      const response = await api.get(`/products/${slug}`)
      currentProduct.value = response.data.data || response.data
      return currentProduct.value
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      isLoading.value = false  // ← AGREGAR
    }
  }

  return {
    products,
    currentProduct,
    isLoading,     // ← EXPONER
    error,         // ← EXPONER
    fetchProducts,
    fetchProductBySlug
  }
})
```

**Uso en componentes**:

```vue
<script setup>
import { SkeletonCard } from '@/components/ui'
import { useProductStore } from '@/stores/productStore'

const productStore = useProductStore()

// Ahora puedes usar directamente:
// productStore.isLoading
// productStore.error
</script>

<template>
  <div v-if="productStore.isLoading">
    <SkeletonCard v-for="n in 8" :key="n" />
  </div>

  <div v-else-if="productStore.error">
    Error: {{ productStore.error }}
  </div>

  <div v-else>
    <ProductCard v-for="p in productStore.products" :key="p.id" :product="p" />
  </div>
</template>
```

---

## Checklist de Integración

### Fase 1: Componentes Base
- [x] Crear SkeletonCard.vue
- [x] Crear SkeletonTable.vue
- [x] Crear SkeletonText.vue
- [x] Crear index.js para exports

### Fase 2: Vistas Públicas
- [ ] Integrar en HomeView.vue
- [ ] Integrar en ProductDetailView.vue
- [ ] Integrar en CartView.vue
- [ ] Integrar en WishlistView.vue
- [ ] Integrar en CheckoutView.vue

### Fase 3: Admin Dashboard
- [ ] Integrar en ProductsAdminView
- [ ] Integrar en CategoriesAdminView
- [ ] Integrar en OrdersAdminView
- [ ] Integrar en UsersAdminView

### Fase 4: Stores
- [ ] Agregar isLoading a productStore
- [ ] Agregar isLoading a categoryStore
- [ ] Agregar isLoading a cartStore
- [ ] Agregar isLoading a wishlistStore

### Fase 5: Pulir
- [ ] Agregar transiciones fade
- [ ] Probar en dark mode
- [ ] Probar responsive
- [ ] Optimizar cantidad de skeletons mostrados
- [ ] Agregar timeouts para errores

---

## Quick Start - Prueba Rápida

**1. Importar en cualquier vista**:
```vue
<script setup>
import { SkeletonCard } from '@/components/ui'
</script>
```

**2. Agregar estado de loading**:
```vue
const isLoading = ref(true)

onMounted(async () => {
  isLoading.value = true
  await fetchData()
  isLoading.value = false
})
```

**3. Usar en template**:
```vue
<template>
  <div v-if="isLoading">
    <SkeletonCard v-for="n in 4" :key="n" />
  </div>
  <div v-else>
    <!-- Contenido real -->
  </div>
</template>
```

**4. Abrir navegador y ver resultado!**

---

## Debugging Tips

### El skeleton no aparece
```js
// Verificar que isLoading esté en true al inicio
console.log('isLoading:', isLoading.value)  // debe ser true

// Verificar timing
setTimeout(() => {
  console.log('After load:', isLoading.value)  // debe ser false
}, 2000)
```

### El skeleton aparece pero sin animación
```vue
<!-- Verificar que la clase animate-pulse esté presente -->
<div class="animate-pulse">  ← debe estar aquí
  ...
</div>
```

### El skeleton no desaparece
```js
// Asegurarse de poner isLoading = false en finally
try {
  await fetchData()
} finally {
  isLoading.value = false  // ← IMPORTANTE
}
```

### Dark mode no funciona
```html
<!-- Verificar que html tenga clase dark -->
<html class="dark">
  <!-- Los skeletons usan dark:bg-gray-700 -->
</html>
```

---

## Performance Tips

1. **Cantidad de skeletons**: No renderizar más de 12-16 skeletons simultáneos
2. **Lazy loading**: Considerar usar Intersection Observer para skeletons fuera de viewport
3. **Transiciones**: Usar transiciones CSS en lugar de JS para mejor performance
4. **Reutilización**: Los skeletons son muy livianos, reutilizar sin problema

---

## Next Steps

Una vez integrados los skeletons básicos:

1. Agregar transiciones fade entre skeleton y contenido
2. Implementar skeleton para búsqueda/filtros
3. Agregar skeleton para componentes de reviews
4. Crear skeleton personalizado para OrderCard
5. Documentar patrones específicos del proyecto
