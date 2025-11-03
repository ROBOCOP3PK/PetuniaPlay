<template>
  <div class="min-h-screen py-8 dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 dark:text-white">Contáctanos</h1>
        <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
          ¿Tienes alguna pregunta o comentario? Estamos aquí para ayudarte.
          Envíanos un mensaje y te responderemos lo antes posible.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
          <h2 class="text-2xl font-bold mb-6 dark:text-white">Envíanos un Mensaje</h2>

          <form @submit.prevent="handleSubmit">
            <!-- Name -->
            <div class="mb-6">
              <label for="name" class="block text-sm font-semibold mb-2 dark:text-gray-200">
                Nombre Completo *
              </label>
              <input
                v-model="form.name"
                type="text"
                id="name"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Juan Pérez"
              />
            </div>

            <!-- Email -->
            <div class="mb-6">
              <label for="email" class="block text-sm font-semibold mb-2 dark:text-gray-200">
                Correo Electrónico *
              </label>
              <input
                v-model="form.email"
                type="email"
                id="email"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="juan@ejemplo.com"
              />
            </div>

            <!-- Phone -->
            <div class="mb-6">
              <label for="phone" class="block text-sm font-semibold mb-2 dark:text-gray-200">
                Teléfono
              </label>
              <input
                v-model="form.phone"
                type="tel"
                id="phone"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="+57 301 234 5678"
              />
            </div>

            <!-- Subject -->
            <div class="mb-6">
              <label for="subject" class="block text-sm font-semibold mb-2 dark:text-gray-200">
                Asunto *
              </label>
              <select
                v-model="form.subject"
                id="subject"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
              >
                <option value="">Selecciona un asunto</option>
                <option value="product">Consulta sobre producto</option>
                <option value="order">Estado de mi pedido</option>
                <option value="shipping">Información de envío</option>
                <option value="return">Devoluciones y cambios</option>
                <option value="other">Otro</option>
              </select>
            </div>

            <!-- Message -->
            <div class="mb-6">
              <label for="message" class="block text-sm font-semibold mb-2 dark:text-gray-200">
                Mensaje *
              </label>
              <textarea
                v-model="form.message"
                id="message"
                required
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                placeholder="Escribe tu mensaje aquí..."
              ></textarea>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="submitting"
              class="w-full bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition text-lg"
              :class="{ 'opacity-50 cursor-not-allowed': submitting }"
            >
              {{ submitting ? 'Enviando...' : 'Enviar Mensaje' }}
            </button>

            <!-- Success Message -->
            <div v-if="success" class="mt-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-100 rounded-lg">
              ✅ ¡Mensaje enviado exitosamente! Te responderemos pronto.
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-100 rounded-lg">
              ❌ {{ error }}
            </div>
          </form>
        </div>

        <!-- Contact Information -->
        <div>
          <!-- Info Cards -->
          <div class="space-y-6 mb-8">
            <!-- Email Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <div class="flex items-start space-x-4">
                <div class="bg-primary text-white p-3 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-lg mb-1 dark:text-white">Correo Electrónico</h3>
                  <p class="text-gray-600 dark:text-gray-300">contacto@petuniaplay.com</p>
                  <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Respuesta en 24 horas</p>
                </div>
              </div>
            </div>

            <!-- Phone Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <div class="flex items-start space-x-4">
                <div class="bg-primary text-white p-3 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-lg mb-1 dark:text-white">Teléfono</h3>
                  <p class="text-gray-600 dark:text-gray-300">+57 305 759 4088</p>
                  <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Lun - Vie: 9:00 AM - 6:00 PM</p>
                </div>
              </div>
            </div>

            <!-- Location Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <div class="flex items-start space-x-4">
                <div class="bg-primary text-white p-3 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-lg mb-1 dark:text-white">Ubicación</h3>
                  <p class="text-gray-600 dark:text-gray-300">Bogotá, Colombia</p>
                  <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Envíos a todo el país</p>
                </div>
              </div>
            </div>

            <!-- Social Media Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <div class="flex items-start space-x-4">
                <div class="bg-primary text-white p-3 rounded-lg">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-lg mb-1 dark:text-white">Redes Sociales</h3>
                  <div class="space-y-1">
                    <a href="#" class="text-primary hover:underline block">Facebook</a>
                    <a href="#" class="text-primary hover:underline block">Instagram</a>
                    <a href="#" class="text-primary hover:underline block">Twitter</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- FAQ Link -->
          <div class="bg-beige dark:bg-gray-800 rounded-lg p-6 text-center">
            <h3 class="font-bold text-lg mb-2 dark:text-white">¿Tienes una pregunta rápida?</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Revisa nuestras preguntas frecuentes, tal vez encuentres tu respuesta allí.
            </p>
            <router-link to="/faq" class="inline-block px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-semibold transition dark:border-primary dark:text-primary dark:hover:bg-primary dark:hover:text-white">
              Ver FAQ
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { contactService } from '../services/contactService'
import { useToast } from 'vue-toastification'

const toast = useToast()

const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
})

const submitting = ref(false)
const success = ref(false)
const error = ref('')

const handleSubmit = async () => {
  submitting.value = true
  success.value = false
  error.value = ''

  try {
    const response = await contactService.send({
      name: form.name,
      email: form.email,
      phone: form.phone,
      subject: form.subject,
      message: form.message
    })

    // Show success message
    success.value = true
    toast.success(response.data.message || 'Mensaje enviado exitosamente')

    // Reset form
    form.name = ''
    form.email = ''
    form.phone = ''
    form.subject = ''
    form.message = ''

    // Hide success message after 5 seconds
    setTimeout(() => {
      success.value = false
    }, 5000)
  } catch (err) {
    error.value = err.response?.data?.message || 'Hubo un error al enviar tu mensaje. Por favor intenta nuevamente.'
    toast.error(error.value)
  } finally {
    submitting.value = false
  }
}
</script>
