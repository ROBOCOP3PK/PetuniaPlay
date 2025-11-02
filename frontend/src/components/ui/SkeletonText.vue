<template>
  <div class="animate-pulse space-y-3">
    <div
      v-for="line in lines"
      :key="`line-${line}`"
      class="h-4 bg-gray-300 dark:bg-gray-700 rounded"
      :style="getLineWidth(line)"
    ></div>
  </div>
</template>

<script setup>
/**
 * SkeletonText - Componente skeleton para loading state de texto genérico
 *
 * @props {number} lines - Número de líneas de texto skeleton (default: 3)
 * @props {number|string} width - Ancho en % o string CSS (default: 100)
 * @props {boolean} varyWidth - Variar ancho de última línea para más realismo (default: true)
 *
 * @example
 * <SkeletonText />
 * <SkeletonText :lines="5" />
 * <SkeletonText :lines="2" :width="80" />
 * <SkeletonText :lines="4" width="300px" />
 * <SkeletonText :lines="3" :vary-width="false" />
 */
const props = defineProps({
  lines: {
    type: Number,
    default: 3,
    description: 'Número de líneas de texto skeleton a mostrar'
  },
  width: {
    type: [Number, String],
    default: 100,
    description: 'Ancho del texto skeleton (número = %, string = CSS value)'
  },
  varyWidth: {
    type: Boolean,
    default: true,
    description: 'Variar el ancho de la última línea para apariencia más natural'
  }
})

/**
 * Calcula el ancho de cada línea
 * La última línea puede ser más corta para mayor realismo
 */
const getLineWidth = (lineNumber) => {
  const baseWidth = typeof props.width === 'number'
    ? `${props.width}%`
    : props.width

  // Si es la última línea y varyWidth está activo, reducir ancho
  if (props.varyWidth && lineNumber === props.lines) {
    const reducedWidth = typeof props.width === 'number'
      ? `${Math.max(60, props.width - 30)}%`
      : baseWidth
    return { width: reducedWidth }
  }

  return { width: baseWidth }
}
</script>
