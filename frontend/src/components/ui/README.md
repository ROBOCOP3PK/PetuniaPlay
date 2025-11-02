# UI Components - Skeleton Loaders

Componentes skeleton reutilizables para mostrar estados de carga elegantes.

## Componentes disponibles

### 1. SkeletonCard.vue

Skeleton para el loading state de `ProductCard`.

**Props:**
- `height` (String, default: 'auto') - Altura personalizada
- `width` (String, default: '100%') - Ancho personalizado

**Ejemplos de uso:**

```vue
<!-- Uso básico -->
<SkeletonCard />

<!-- Con dimensiones personalizadas -->
<SkeletonCard height="500px" width="300px" />

<!-- Grid de skeletons -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
  <SkeletonCard v-for="n in 8" :key="n" />
</div>
```

### 2. SkeletonTable.vue

Skeleton para tablas del panel de administración.

**Props:**
- `rows` (Number, default: 5) - Número de filas
- `columns` (Number, default: 4) - Número de columnas
- `showHeader` (Boolean, default: true) - Mostrar header skeleton

**Ejemplos de uso:**

```vue
<!-- Uso básico -->
<SkeletonTable />

<!-- Tabla personalizada -->
<SkeletonTable :rows="10" :columns="6" />

<!-- Sin header -->
<SkeletonTable :rows="3" :show-header="false" />
```

### 3. SkeletonText.vue

Skeleton para texto genérico.

**Props:**
- `lines` (Number, default: 3) - Número de líneas
- `width` (Number|String, default: 100) - Ancho (% o CSS value)
- `varyWidth` (Boolean, default: true) - Variar ancho de última línea

**Ejemplos de uso:**

```vue
<!-- Uso básico -->
<SkeletonText />

<!-- Múltiples líneas -->
<SkeletonText :lines="5" />

<!-- Ancho personalizado en % -->
<SkeletonText :lines="2" :width="80" />

<!-- Ancho personalizado en px -->
<SkeletonText :lines="4" width="300px" />

<!-- Sin variación de ancho -->
<SkeletonText :lines="3" :vary-width="false" />
```

## Importación

### Opción 1: Importación individual
```vue
<script setup>
import SkeletonCard from '@/components/ui/SkeletonCard.vue'
</script>
```

### Opción 2: Importación desde index
```vue
<script setup>
import { SkeletonCard, SkeletonTable, SkeletonText } from '@/components/ui'
</script>
```

## Ejemplo completo con loading state

```vue
<template>
  <div>
    <!-- Mostrar skeleton mientras carga -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <SkeletonCard v-for="n in 8" :key="n" />
    </div>

    <!-- Mostrar productos cuando termina de cargar -->
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
const loading = ref(true)
const products = ref([])

onMounted(async () => {
  loading.value = true
  products.value = await productStore.fetchProducts()
  loading.value = false
})
</script>
```

## Características técnicas

- **Tailwind CSS**: Usa solo clases de Tailwind (ya configurado en el proyecto)
- **Animación**: `animate-pulse` nativa de Tailwind
- **Dark Mode**: Soporte completo para modo oscuro
- **Vue 3**: Composition API con `<script setup>`
- **Performance**: Componentes livianos sin dependencias externas
- **Accesibilidad**: Compatible con lectores de pantalla
- **Responsive**: Adaptable a diferentes tamaños de pantalla

## Paleta de colores

Los skeletons usan la siguiente paleta de grises:

- Light mode:
  - `bg-gray-300` - Elementos principales
  - `bg-gray-200` - Elementos secundarios
  - `bg-gray-100` - Fondos

- Dark mode:
  - `dark:bg-gray-700` - Elementos principales
  - `dark:bg-gray-600` - Elementos secundarios
  - `dark:bg-gray-800` - Fondos

## Buenas prácticas

1. **Mantener consistencia**: Usar el skeleton correspondiente al componente que está cargando
2. **Cantidad apropiada**: Mostrar cantidad similar a la esperada de elementos reales
3. **Transición suave**: Combinar con transiciones de Vue para cambios fluidos
4. **Estado de error**: Manejar estados de error además del loading
5. **Timeout**: Considerar timeout para evitar skeletons eternos

```vue
<!-- Ejemplo con manejo de estados -->
<template>
  <div>
    <!-- Loading -->
    <SkeletonCard v-if="loading" />

    <!-- Error -->
    <div v-else-if="error" class="text-center p-8">
      <p class="text-red-500">{{ error }}</p>
    </div>

    <!-- Success -->
    <ProductCard v-else :product="product" />
  </div>
</template>
```
