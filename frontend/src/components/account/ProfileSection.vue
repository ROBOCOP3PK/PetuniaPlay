<template>
  <div>
    <!-- Profile Info Form -->
    <form @submit.prevent="updateProfile" class="space-y-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Nombre Completo</label>
          <input
            v-model="profileForm.name"
            type="text"
            required
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Email</label>
          <input
            v-model="profileForm.email"
            type="email"
            required
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Teléfono</label>
          <input
            v-model="profileForm.phone"
            type="tel"
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Documento</label>
          <input
            v-model="profileForm.document"
            type="text"
            class="input-field"
            :disabled="loading"
          />
        </div>
      </div>

      <!-- Email Notifications Preference -->
      <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Preferencias de Notificaciones</h3>
        <div class="flex items-start space-x-3">
          <input
            id="email_notifications_enabled"
            v-model="profileForm.email_notifications_enabled"
            type="checkbox"
            class="mt-1 w-4 h-4 text-primary bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary focus:ring-2"
            :disabled="loading"
          />
          <label for="email_notifications_enabled" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
            <span class="font-medium text-gray-900 dark:text-white">Recibir notificaciones por email</span>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
              Recibe notificaciones sobre preguntas en productos, actualizaciones de pedidos y otras alertas importantes en tu correo electrónico.
              Las notificaciones del sistema siempre estarán disponibles en la campana de notificaciones.
            </p>
          </label>
        </div>
      </div>

      <div class="flex justify-end mt-6">
        <button
          type="submit"
          :disabled="loading"
          class="btn-primary"
          :class="{ 'opacity-50 cursor-not-allowed': loading }"
        >
          {{ loading ? 'Guardando...' : 'Guardar Cambios' }}
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
      <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Cambiar Contraseña</h3>

      <form @submit.prevent="changePassword" class="space-y-4 max-w-md">
        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Contraseña Actual</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            required
            class="input-field"
            :disabled="loadingPassword"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Nueva Contraseña</label>
          <input
            v-model="passwordForm.password"
            type="password"
            required
            minlength="8"
            class="input-field"
            :disabled="loadingPassword"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2 text-gray-900 dark:text-white">Confirmar Nueva Contraseña</label>
          <input
            v-model="passwordForm.password_confirmation"
            type="password"
            required
            class="input-field"
            :disabled="loadingPassword"
          />
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            :disabled="loadingPassword"
            class="btn-secondary"
            :class="{ 'opacity-50 cursor-not-allowed': loadingPassword }"
          >
            {{ loadingPassword ? 'Cambiando...' : 'Cambiar Contraseña' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/authStore'
import { useToast } from 'vue-toastification'
import authService from '../../services/authService'

const authStore = useAuthStore()
const toast = useToast()

const loading = ref(false)
const loadingPassword = ref(false)

const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  document: '',
  email_notifications_enabled: true
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const loadUserData = () => {
  if (authStore.user) {
    profileForm.name = authStore.user.name || ''
    profileForm.email = authStore.user.email || ''
    profileForm.phone = authStore.user.phone || ''
    profileForm.document = authStore.user.document || ''
    profileForm.email_notifications_enabled = authStore.user.email_notifications_enabled ?? true
  }
}

const updateProfile = async () => {
  loading.value = true

  // Actualizar perfil
  const profileResult = await authStore.updateProfile({
    name: profileForm.name,
    email: profileForm.email,
    phone: profileForm.phone,
    document: profileForm.document
  })

  if (!profileResult.success) {
    loading.value = false
    toast.error(profileResult.message)
    return
  }

  // Actualizar preferencias de notificación
  try {
    await authService.updateNotificationPreferences({
      email_notifications_enabled: profileForm.email_notifications_enabled
    })

    // Actualizar el usuario en el store
    await authStore.fetchUser()

    toast.success('Perfil y preferencias actualizados exitosamente')
  } catch (error) {
    toast.error('Perfil actualizado pero hubo un error al guardar las preferencias de notificación')
  } finally {
    loading.value = false
  }
}

const changePassword = async () => {
  if (passwordForm.password !== passwordForm.password_confirmation) {
    toast.warning('Las contraseñas no coinciden')
    return
  }

  loadingPassword.value = true

  const result = await authStore.changePassword({
    current_password: passwordForm.current_password,
    password: passwordForm.password,
    password_confirmation: passwordForm.password_confirmation
  })

  loadingPassword.value = false

  if (result.success) {
    toast.success('Contraseña cambiada exitosamente')
    // Limpiar formulario
    passwordForm.current_password = ''
    passwordForm.password = ''
    passwordForm.password_confirmation = ''
  } else {
    toast.error(result.message)
  }
}

onMounted(() => {
  loadUserData()
})
</script>
