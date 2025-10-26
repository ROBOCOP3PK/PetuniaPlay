<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
          Preguntas Frecuentes
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
          Encuentra respuestas rápidas a las preguntas más comunes
        </p>
      </div>

      <!-- Search Box -->
      <div class="mb-8">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar en preguntas frecuentes..."
            class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          />
          <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
      </div>

      <!-- Categories -->
      <div class="flex flex-wrap gap-3 mb-8">
        <button
          v-for="cat in categories"
          :key="cat"
          @click="selectedCategory = cat"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition-colors',
            selectedCategory === cat
              ? 'bg-primary text-white'
              : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- FAQ Accordion -->
      <div class="space-y-4">
        <div
          v-for="(faq, index) in filteredFAQs"
          :key="index"
          class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700"
        >
          <button
            @click="toggleFAQ(index)"
            class="w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            <span class="font-semibold text-gray-900 dark:text-white pr-4">
              {{ faq.question }}
            </span>
            <svg
              :class="['w-5 h-5 text-primary dark:text-primary-light transform transition-transform', openFAQs.includes(index) ? 'rotate-180' : '']"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-96 opacity-100"
            leave-from-class="max-h-96 opacity-100"
            leave-to-class="max-h-0 opacity-0"
          >
            <div v-show="openFAQs.includes(index)" class="px-6 pb-4">
              <div class="text-gray-600 dark:text-gray-300 leading-relaxed" v-html="faq.answer"></div>
            </div>
          </transition>
        </div>
      </div>

      <!-- No Results -->
      <div v-if="filteredFAQs.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">
          No se encontraron preguntas que coincidan con tu búsqueda
        </p>
        <p class="text-gray-400 dark:text-gray-500 text-sm">
          Intenta con otros términos o revisa todas las categorías
        </p>
      </div>

      <!-- Still Have Questions -->
      <div class="mt-12 bg-gradient-to-r from-primary to-primary-light dark:from-primary-dark dark:to-primary text-white rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold mb-4">¿Aún tienes preguntas?</h2>
        <p class="mb-6 text-white text-opacity-90">
          Si no encontraste la respuesta que buscabas, no dudes en contactarnos directamente.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/contact"
            class="inline-block bg-white text-primary dark:text-primary-dark px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition"
          >
            Contáctanos
          </router-link>
          <a
            href="mailto:contacto@petuniaplay.com"
            class="inline-block bg-white bg-opacity-20 text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-30 transition border-2 border-white"
          >
            Enviar Email
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedCategory = ref('Todas')
const openFAQs = ref([])

const categories = [
  'Todas',
  'Pedidos y Envíos',
  'Pagos',
  'Productos',
  'Devoluciones',
  'Cuenta'
]

const faqs = [
  // Pedidos y Envíos
  {
    category: 'Pedidos y Envíos',
    question: '¿Cuánto tiempo tarda en llegar mi pedido?',
    answer: 'Los tiempos de entrega varían según tu ubicación:<br><br><ul class="list-disc list-inside space-y-1"><li><strong>Bogotá:</strong> 2-3 días hábiles</li><li><strong>Ciudades principales:</strong> 3-5 días hábiles</li><li><strong>Otras ciudades:</strong> 5-7 días hábiles</li></ul><br>Recibirás un número de seguimiento por email una vez que tu pedido sea enviado.'
  },
  {
    category: 'Pedidos y Envíos',
    question: '¿Puedo rastrear mi pedido?',
    answer: 'Sí, una vez que tu pedido sea enviado, recibirás un correo electrónico con un número de seguimiento. Puedes usar este número en nuestra <a href="/tracking" class="text-primary dark:text-primary-light font-semibold hover:underline">página de rastreo</a> o directamente en el sitio web de la transportadora.'
  },
  {
    category: 'Pedidos y Envíos',
    question: '¿Tienen envío gratis?',
    answer: 'Sí, ofrecemos envío gratis en pedidos superiores a <strong>$100,000 COP</strong> a nivel nacional. Para pedidos de menor valor, se aplica una tarifa de envío que se calcula automáticamente al finalizar la compra según tu ubicación.'
  },
  {
    category: 'Pedidos y Envíos',
    question: '¿Puedo cambiar la dirección de envío después de hacer el pedido?',
    answer: 'Puedes cambiar la dirección de envío solo si tu pedido aún no ha sido enviado. Contáctanos lo antes posible a través de <a href="/contact" class="text-primary dark:text-primary-light font-semibold hover:underline">nuestro formulario de contacto</a> o por email a contacto@petuniaplay.com con tu número de pedido.'
  },

  // Pagos
  {
    category: 'Pagos',
    question: '¿Qué métodos de pago aceptan?',
    answer: 'Aceptamos los siguientes métodos de pago:<br><br><ul class="list-disc list-inside space-y-1"><li>Tarjetas de crédito (Visa, Mastercard, American Express)</li><li>Tarjetas débito</li><li>PSE (Pagos Seguros en Línea)</li><li>Efectivo (mediante Efecty o Baloto)</li></ul><br>Todos los pagos se procesan de forma segura a través de pasarelas de pago certificadas.'
  },
  {
    category: 'Pagos',
    question: '¿Es seguro pagar en su sitio web?',
    answer: 'Absolutamente. Utilizamos tecnología de encriptación SSL y procesamos todos los pagos a través de pasarelas certificadas PCI-DSS. Nunca almacenamos información completa de tarjetas de crédito en nuestros servidores. Tu seguridad es nuestra prioridad.'
  },
  {
    category: 'Pagos',
    question: '¿Puedo obtener una factura de mi compra?',
    answer: 'Sí, recibirás automáticamente una factura electrónica por email después de completar tu compra. También puedes descargar tus facturas en cualquier momento desde tu cuenta, en la sección "Mis Pedidos".'
  },
  {
    category: 'Pagos',
    question: '¿Ofrecen cupones de descuento?',
    answer: 'Sí, periódicamente ofrecemos cupones de descuento a nuestros clientes. Puedes encontrar cupones activos en:<br><br><ul class="list-disc list-inside space-y-1"><li>Nuestro newsletter (suscríbete para recibirlos)</li><li>Redes sociales</li><li>Promociones especiales en fechas importantes</li></ul><br>Para usar un cupón, ingrésalo en el campo "Código de descuento" durante el proceso de checkout.'
  },

  // Productos
  {
    category: 'Productos',
    question: '¿Todos los productos son aptos para mascotas?',
    answer: 'Sí, todos nuestros productos están cuidadosamente seleccionados y verificados para ser seguros para mascotas. Trabajamos solo con marcas reconocidas y cumplimos con todos los estándares de calidad y seguridad.'
  },
  {
    category: 'Productos',
    question: '¿Cómo sé qué talla comprar para mi mascota?',
    answer: 'Cada producto incluye una guía de tallas detallada en su página. Te recomendamos medir a tu mascota siguiendo nuestras instrucciones y comparar con la tabla de tallas. Si tienes dudas, nuestro equipo de atención al cliente puede ayudarte a elegir la talla correcta.'
  },
  {
    category: 'Productos',
    question: '¿Los productos tienen garantía?',
    answer: 'Sí, todos nuestros productos tienen garantía contra defectos de fabricación:<br><br><ul class="list-disc list-inside space-y-1"><li><strong>Juguetes y accesorios:</strong> 30 días</li><li><strong>Ropa y camas:</strong> 60 días</li><li><strong>Productos electrónicos:</strong> 1 año</li></ul><br>La garantía no cubre daños por uso inadecuado o desgaste normal.'
  },
  {
    category: 'Productos',
    question: '¿Tienen productos para gatos y perros?',
    answer: 'Sí, tenemos una amplia variedad de productos tanto para gatos como para perros, incluyendo:<br><br><ul class="list-disc list-inside space-y-1"><li>Alimentos y snacks</li><li>Juguetes</li><li>Ropa y accesorios</li><li>Camas y casas</li><li>Productos de higiene</li><li>Productos de salud</li></ul>'
  },

  // Devoluciones
  {
    category: 'Devoluciones',
    question: '¿Cuál es su política de devoluciones?',
    answer: 'Aceptamos devoluciones dentro de los <strong>30 días</strong> posteriores a la recepción de tu pedido. El producto debe estar sin usar, en su empaque original y con todas las etiquetas. Para más detalles, consulta nuestra <a href="/returns" class="text-primary dark:text-primary-light font-semibold hover:underline">Política de Devoluciones completa</a>.'
  },
  {
    category: 'Devoluciones',
    question: '¿Cómo inicio una devolución?',
    answer: 'Para iniciar una devolución:<br><br><ol class="list-decimal list-inside space-y-1"><li>Inicia sesión en tu cuenta</li><li>Ve a "Mis Pedidos"</li><li>Selecciona el pedido que deseas devolver</li><li>Haz clic en "Solicitar Devolución"</li><li>Selecciona el motivo y sigue las instrucciones</li></ol><br>También puedes contactarnos directamente a través de <a href="/contact" class="text-primary dark:text-primary-light font-semibold hover:underline">nuestro formulario de contacto</a>.'
  },
  {
    category: 'Devoluciones',
    question: '¿Quién paga el envío de la devolución?',
    answer: 'Si la devolución es por un defecto del producto o error nuestro, cubrimos todos los costos de envío. Si es por cambio de opinión, el costo del envío de devolución corre por cuenta del cliente. Los costos de envío originales no son reembolsables.'
  },
  {
    category: 'Devoluciones',
    question: '¿Cuánto tarda el reembolso?',
    answer: 'Una vez que recibamos y procesemos tu devolución, el reembolso se procesará en <strong>5-7 días hábiles</strong>. Dependiendo de tu banco, puede tomar 3-5 días adicionales para que el crédito aparezca en tu cuenta. Recibirás un email de confirmación cuando se procese el reembolso.'
  },

  // Cuenta
  {
    category: 'Cuenta',
    question: '¿Necesito crear una cuenta para comprar?',
    answer: 'No, puedes realizar compras como invitado. Sin embargo, crear una cuenta te permite:<br><br><ul class="list-disc list-inside space-y-1"><li>Rastrear tus pedidos fácilmente</li><li>Guardar direcciones de envío</li><li>Ver tu historial de compras</li><li>Agregar productos a tu lista de deseos</li><li>Recibir ofertas exclusivas</li></ul>'
  },
  {
    category: 'Cuenta',
    question: '¿Olvidé mi contraseña, qué hago?',
    answer: 'Puedes restablecer tu contraseña fácilmente:<br><br><ol class="list-decimal list-inside space-y-1"><li>Ve a la <a href="/login" class="text-primary dark:text-primary-light font-semibold hover:underline">página de inicio de sesión</a></li><li>Haz clic en "¿Olvidaste tu contraseña?"</li><li>Ingresa tu correo electrónico</li><li>Revisa tu email y sigue el enlace para crear una nueva contraseña</li></ol><br>Si tienes problemas, contáctanos a contacto@petuniaplay.com.'
  },
  {
    category: 'Cuenta',
    question: '¿Puedo cambiar mi información de cuenta?',
    answer: 'Sí, puedes actualizar tu información en cualquier momento:<br><br><ol class="list-decimal list-inside space-y-1"><li>Inicia sesión en tu cuenta</li><li>Ve a "Mi Cuenta"</li><li>Selecciona la sección que deseas editar (perfil, direcciones, etc.)</li><li>Realiza los cambios y guarda</li></ol>'
  },
  {
    category: 'Cuenta',
    question: '¿Cómo elimino mi cuenta?',
    answer: 'Si deseas eliminar tu cuenta, contáctanos a través de <a href="/contact" class="text-primary dark:text-primary-light font-semibold hover:underline">nuestro formulario de contacto</a> o envía un email a contacto@petuniaplay.com. Ten en cuenta que:<br><br><ul class="list-disc list-inside space-y-1"><li>No podrás recuperar tu cuenta una vez eliminada</li><li>Se perderá tu historial de pedidos</li><li>Las compras pendientes deben completarse antes de eliminar la cuenta</li></ul>'
  }
]

const filteredFAQs = computed(() => {
  let filtered = faqs

  // Filter by category
  if (selectedCategory.value !== 'Todas') {
    filtered = filtered.filter(faq => faq.category === selectedCategory.value)
  }

  // Filter by search query
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(faq =>
      faq.question.toLowerCase().includes(query) ||
      faq.answer.toLowerCase().includes(query)
    )
  }

  return filtered
})

const toggleFAQ = (index) => {
  const globalIndex = faqs.findIndex(faq => faq === filteredFAQs.value[index])
  const idx = openFAQs.value.indexOf(globalIndex)
  if (idx > -1) {
    openFAQs.value.splice(idx, 1)
  } else {
    openFAQs.value.push(globalIndex)
  }
}
</script>
