<template>
  <div class="animate-pulse">
    <!-- Table header skeleton -->
    <div v-if="showHeader" class="bg-gray-100 dark:bg-gray-800 rounded-t-lg p-4 mb-2">
      <div class="flex gap-4">
        <div
          v-for="col in columns"
          :key="`header-${col}`"
          class="h-4 bg-gray-300 dark:bg-gray-700 rounded flex-1"
        ></div>
      </div>
    </div>

    <!-- Table rows skeleton -->
    <div class="space-y-2">
      <div
        v-for="row in rows"
        :key="`row-${row}`"
        class="bg-white dark:bg-gray-900 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700"
      >
        <div class="flex gap-4 items-center">
          <div
            v-for="col in columns"
            :key="`row-${row}-col-${col}`"
            class="h-4 rounded flex-1"
            :class="getColumnClass(col)"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
/**
 * SkeletonTable - Componente skeleton para loading state de tablas
 *
 * @props {number} rows - Número de filas a mostrar (default: 5)
 * @props {number} columns - Número de columnas a mostrar (default: 4)
 * @props {boolean} showHeader - Mostrar skeleton del header (default: true)
 *
 * @example
 * <SkeletonTable />
 * <SkeletonTable :rows="10" :columns="6" />
 * <SkeletonTable :rows="3" :show-header="false" />
 */
const props = defineProps({
  rows: {
    type: Number,
    default: 5,
    description: 'Número de filas skeleton a mostrar'
  },
  columns: {
    type: Number,
    default: 4,
    description: 'Número de columnas skeleton a mostrar'
  },
  showHeader: {
    type: Boolean,
    default: true,
    description: 'Mostrar skeleton del header de la tabla'
  }
})

/**
 * Alterna colores entre columnas para mejor visualización
 */
const getColumnClass = (col) => {
  return col % 2 === 0
    ? 'bg-gray-300 dark:bg-gray-700'
    : 'bg-gray-200 dark:bg-gray-600'
}
</script>
