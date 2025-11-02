<template>
  <div
    class="card animate-pulse"
    :style="{ height: height, width: width }"
  >
    <!-- Image skeleton -->
    <div class="relative overflow-hidden aspect-square bg-gray-300 dark:bg-gray-700">
      <!-- Placeholder icon in center -->
      <div class="absolute inset-0 flex items-center justify-center">
        <i class="pi pi-image text-gray-400 dark:text-gray-600 text-5xl"></i>
      </div>
    </div>

    <!-- Content skeleton -->
    <div class="p-4 space-y-3">
      <!-- Category -->
      <div class="h-3 bg-gray-300 dark:bg-gray-700 rounded w-1/4"></div>

      <!-- Title (2 lines) -->
      <div class="space-y-2">
        <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-4/5"></div>
      </div>

      <!-- Description (2 lines) -->
      <div class="space-y-2">
        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4"></div>
      </div>

      <!-- Rating -->
      <div class="flex items-center gap-2">
        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-24"></div>
        <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-12"></div>
      </div>

      <!-- Price and button -->
      <div class="flex items-center justify-between pt-2">
        <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-24"></div>
        <div class="h-10 w-10 bg-gray-300 dark:bg-gray-700 rounded-lg"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * SkeletonCard - Componente skeleton para loading state de ProductCard
 *
 * @props {string} height - Altura personalizada del skeleton (opcional)
 * @props {string} width - Ancho personalizado del skeleton (opcional)
 *
 * @example
 * <SkeletonCard />
 * <SkeletonCard height="500px" width="300px" />
 */
defineProps({
  height: {
    type: String,
    default: 'auto',
    description: 'Altura personalizada del skeleton (ej: "400px", "100%")'
  },
  width: {
    type: String,
    default: '100%',
    description: 'Ancho personalizado del skeleton (ej: "300px", "100%")'
  }
})
</script>
