<template>
  <a
    v-if="isEnabled"
    :href="whatsappUrl"
    target="_blank"
    rel="noopener noreferrer"
    class="whatsapp-float"
    aria-label="Contáctanos por WhatsApp"
    title="¿Tienes dudas? Escríbenos por WhatsApp"
  >
    <div class="whatsapp-icon">
      <svg viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 0C7.164 0 0 7.163 0 16c0 2.816.736 5.464 2.024 7.76L0 32l8.448-2.208A15.93 15.93 0 0016 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.448a13.39 13.39 0 01-6.832-1.856l-.488-.296-5.072 1.328 1.36-4.928-.32-.504A13.39 13.39 0 012.552 16c0-7.424 6.024-13.448 13.448-13.448S29.448 8.576 29.448 16 23.424 29.448 16 29.448z"/>
        <path d="M23.36 19.472c-.384-.192-2.272-1.12-2.624-1.248-.352-.128-.608-.192-.864.192-.256.384-1.024 1.248-1.248 1.504-.224.256-.448.288-.832.096-.384-.192-1.632-.6-3.104-1.92-1.152-1.024-1.92-2.288-2.144-2.672-.224-.384-.024-.592.168-.784.176-.176.384-.448.576-.672.192-.224.256-.384.384-.64.128-.256.064-.48-.032-.672-.096-.192-.864-2.08-1.184-2.848-.312-.752-.632-.648-.864-.664-.224-.016-.48-.016-.736-.016s-.672.096-1.024.48c-.352.384-1.344 1.312-1.344 3.2s1.376 3.712 1.568 3.968c.192.256 2.688 4.128 6.528 5.792.912.392 1.632.632 2.192.808.92.288 1.76.248 2.416.152.736-.112 2.272-.928 2.592-1.824.32-.896.32-1.664.224-1.824-.096-.16-.352-.256-.736-.448z"/>
      </svg>
    </div>
    <span class="whatsapp-text">¿Necesitas ayuda?</span>
  </a>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import siteConfigService from '../services/siteConfigService'

const config = ref(null)

// Computed para saber si el botón está habilitado
const isEnabled = computed(() => {
  return config.value?.whatsapp_enabled ?? false
})

// Computed para generar la URL de WhatsApp
const whatsappUrl = computed(() => {
  if (!config.value) return '#'

  const phoneNumber = config.value.whatsapp_number || '573057594088'
  const message = config.value.whatsapp_message || 'Buen día Tengo una duda con un producto'
  const encodedMessage = encodeURIComponent(message)

  return `https://wa.me/${phoneNumber}?text=${encodedMessage}`
})

// Cargar configuración al montar el componente
const loadConfig = async () => {
  try {
    const response = await siteConfigService.getConfig()
    if (response.data.success) {
      config.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading WhatsApp config:', error)
    // Usar valores por defecto si falla la carga
    config.value = {
      whatsapp_number: '573057594088',
      whatsapp_enabled: true,
      whatsapp_message: 'Buen día Tengo una duda con un producto'
    }
  }
}

onMounted(() => {
  loadConfig()
})
</script>

<style scoped>
.whatsapp-float {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 999;
  display: flex;
  align-items: center;
  gap: 12px;
  background: #25D366;
  color: white;
  padding: 12px 20px;
  border-radius: 50px;
  box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
}

.whatsapp-float:hover {
  background: #128C7E;
  box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
  transform: scale(1.05);
}

.whatsapp-float:active {
  transform: scale(0.98);
}

.whatsapp-icon {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.whatsapp-icon svg {
  width: 100%;
  height: 100%;
}

.whatsapp-text {
  display: inline-block;
  white-space: nowrap;
}

/* Animación de entrada */
@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.whatsapp-float {
  animation: slideIn 0.5s ease-out;
}

/* Animación de pulso */
@keyframes pulse {
  0% {
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
  }
  50% {
    box-shadow: 0 4px 20px rgba(37, 211, 102, 0.7);
  }
  100% {
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
  }
}

.whatsapp-float {
  animation: slideIn 0.5s ease-out, pulse 2s ease-in-out 1s infinite;
}

/* Responsive: En móviles, solo mostrar el icono */
@media (max-width: 640px) {
  .whatsapp-float {
    padding: 14px;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    bottom: 20px;
    right: 20px;
  }

  .whatsapp-text {
    display: none;
  }

  .whatsapp-icon {
    width: 28px;
    height: 28px;
  }
}

/* Para tablets, mostrar versión compacta */
@media (min-width: 641px) and (max-width: 1024px) {
  .whatsapp-float {
    padding: 12px 16px;
    font-size: 13px;
  }

  .whatsapp-icon {
    width: 28px;
    height: 28px;
  }
}
</style>
