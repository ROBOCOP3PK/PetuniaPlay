<script setup>
import { RouterView } from 'vue-router'
import TheHeader from './components/layout/TheHeader.vue'
import TheFooter from './components/layout/TheFooter.vue'
</script>

<template>
  <div class="app min-h-screen flex flex-col">
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
