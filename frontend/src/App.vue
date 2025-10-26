<script setup>
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import TheHeader from './components/layout/TheHeader.vue'
import TheFooter from './components/layout/TheFooter.vue'
import { useTheme } from './composables/useTheme'

const { initTheme } = useTheme()

// Inicializar el tema al montar la aplicación
onMounted(() => {
  initTheme()
})
</script>

<template>
  <div class="app min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <TheHeader />

    <main class="flex-grow">
      <RouterView />
    </main>

    <TheFooter />
  </div>
</template>

<style scoped>
.app {
  min-height: 100vh;
}
</style>
