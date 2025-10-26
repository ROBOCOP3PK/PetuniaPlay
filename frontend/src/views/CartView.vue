<template>
  <div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
      <h1 class="text-4xl font-bold mb-8">Carrito de Compras</h1>

      <!-- Empty Cart -->
      <div class="text-center py-12">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold mb-2">Tu carrito está vacío</h2>
        <p class="text-gray-600 mb-6">
          Agrega productos a tu carrito para continuar comprando
        </p>
        <router-link to="/products" class="btn-primary inline-block">
          Explorar Productos
        </router-link>
      </div>

      <!-- TODO: Implementar carrito completo en SEMANA 4 -->
    </div>
  </div>
</template>

<script setup>
// TODO: Implementar funcionalidad de carrito en SEMANA 4
</script>
