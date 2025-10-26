<template>
  <div>
    <!-- Profile Info Form -->
    <form @submit.prevent="updateProfile" class="space-y-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2">Nombre Completo</label>
          <input
            v-model="profileForm.name"
            type="text"
            required
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Email</label>
          <input
            v-model="profileForm.email"
            type="email"
            required
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Teléfono</label>
          <input
            v-model="profileForm.phone"
            type="tel"
            class="input-field"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Documento</label>
          <input
            v-model="profileForm.document"
            type="text"
            class="input-field"
            :disabled="loading"
          />
        </div>
      </div>

      <div class="flex justify-end">
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
    <div class="border-t pt-8">
      <h3 class="text-xl font-bold mb-6">Cambiar Contraseña</h3>

      <form @submit.prevent="changePassword" class="space-y-4 max-w-md">
        <div>
          <label class="block text-sm font-semibold mb-2">Contraseña Actual</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            required
            class="input-field"
            :disabled="loadingPassword"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-2">Nueva Contraseña</label>
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
          <label class="block text-sm font-semibold mb-2">Confirmar Nueva Contraseña</label>
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

const authStore = useAuthStore()
const toast = useToast()

const loading = ref(false)
const loadingPassword = ref(false)

const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  document: ''
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
  }
}

const updateProfile = async () => {
  loading.value = true

  const result = await authStore.updateProfile({
    name: profileForm.name,
    email: profileForm.email,
    phone: profileForm.phone,
    document: profileForm.document
  })

  loading.value = false

  if (result.success) {
    toast.success('Perfil actualizado exitosamente')
  } else {
    toast.error(result.message)
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
