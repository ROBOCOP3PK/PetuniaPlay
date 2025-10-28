<template>
  <button
    @click="toggleTheme"
    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
    :title="isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
    aria-label="Alternar tema"
  >
    <!-- Sun Icon (mostrar en dark mode) -->
    <i v-if="isDark" class="pi pi-sun text-xl text-gray-700 dark:text-gray-200"></i>

    <!-- Moon Icon (mostrar en light mode) -->
    <i v-else class="pi pi-moon text-xl text-gray-700 dark:text-gray-200"></i>
  </button>
</template>

<script setup>
import { useTheme } from '../composables/useTheme'

const { isDark, toggleTheme } = useTheme()
</script>
