<template>
  <AdminLayout>
    <div>
      <h1 class="text-3xl font-bold text-dark dark:text-white mb-8">Gestión de Usuarios</h1>

      <!-- Search and Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por nombre o email..."
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          />
          <select
            v-model="filterRole"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Todos los roles</option>
            <option value="customer">Clientes</option>
            <option value="manager">Gestores</option>
            <option value="admin">Administradores</option>
          </select>
          <select
            v-model="filterStatus"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Todos los estados</option>
            <option value="active">Activos</option>
            <option value="inactive">Inactivos</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-300">Cargando usuarios...</p>
      </div>

      <!-- Users Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Rol</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Registro</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="user in filteredUsers"
                :key="user.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                      <p class="font-semibold text-dark dark:text-white">{{ user.name }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.phone || 'Sin teléfono' }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700 dark:text-gray-300">{{ user.email }}</p>
                </td>
                <td class="px-6 py-4">
                  <select
                    v-model="user.role"
                    @change="updateRole(user)"
                    :disabled="user.id === currentUserId"
                    class="px-3 py-1 text-xs rounded-full font-semibold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary dark:border-gray-600"
                    :class="getRoleClass(user.role)"
                  >
                    <option value="customer">Cliente</option>
                    <option value="manager">Gestor</option>
                    <option value="admin">Admin</option>
                  </select>
                </td>
                <td class="px-6 py-4 text-center">
                  <button
                    @click="toggleStatus(user)"
                    :disabled="user.id === currentUserId"
                    class="px-3 py-1 text-xs rounded-full font-semibold transition"
                    :class="user.is_active
                      ? 'bg-green-100 text-green-800 hover:bg-green-200'
                      : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                    "
                  >
                    {{ user.is_active ? 'Activo' : 'Inactivo' }}
                  </button>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(user.created_at) }}</p>
                </td>
                <td class="px-6 py-4 text-right">
                  <button
                    @click="viewUser(user)"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold text-sm"
                  >
                    Ver detalles
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredUsers.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-300">No se encontraron usuarios</p>
        </div>
      </div>

      <!-- User Details Modal -->
      <div
        v-if="selectedUser"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="selectedUser = null"
      >
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full">
          <div class="p-6 border-b dark:border-gray-700">
            <div class="flex justify-between items-center">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles del Usuario</h2>
              <button @click="selectedUser = null" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <div class="p-6 space-y-4">
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white font-bold text-2xl">
                {{ selectedUser.name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ selectedUser.name }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ selectedUser.email }}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-gray-700">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Rol</p>
                <p class="font-semibold text-gray-900 dark:text-white capitalize">{{ selectedUser.role }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Estado</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedUser.is_active ? 'Activo' : 'Inactivo' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Teléfono</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedUser.phone || 'No especificado' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Documento</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ selectedUser.document || 'No especificado' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de registro</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ formatDate(selectedUser.created_at) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Última actualización</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ formatDate(selectedUser.updated_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'
import { adminService } from '../../services/adminService'
import { useAuthStore } from '../../stores/authStore'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()

const loading = ref(false)
const users = ref([])
const search = ref('')
const filterRole = ref('')
const filterStatus = ref('')
const selectedUser = ref(null)

const currentUserId = computed(() => authStore.user?.id)

const filteredUsers = computed(() => {
  let filtered = users.value

  // Search
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    filtered = filtered.filter(u =>
      u.name.toLowerCase().includes(searchLower) ||
      u.email.toLowerCase().includes(searchLower)
    )
  }

  // Role filter
  if (filterRole.value) {
    filtered = filtered.filter(u => u.role === filterRole.value)
  }

  // Status filter
  if (filterStatus.value === 'active') {
    filtered = filtered.filter(u => u.is_active)
  } else if (filterStatus.value === 'inactive') {
    filtered = filtered.filter(u => !u.is_active)
  }

  return filtered
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getRoleClass = (role) => {
  const classes = {
    customer: 'bg-blue-100 text-blue-800 border-blue-200',
    manager: 'bg-purple-100 text-purple-800 border-purple-200',
    admin: 'bg-red-100 text-red-800 border-red-200'
  }
  return classes[role] || 'bg-gray-100 text-gray-800 border-gray-200'
}

const updateRole = async (user) => {
  try {
    await adminService.updateUserRole(user.id, user.role)
    toast.success('Rol actualizado exitosamente')
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error al actualizar rol')
    loadUsers() // Recargar para restaurar el rol anterior
  }
}

const toggleStatus = async (user) => {
  try {
    const response = await adminService.toggleUserStatus(user.id)
    user.is_active = response.data.data.is_active
    toast.success(response.data.message)
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error al cambiar estado')
  }
}

const viewUser = (user) => {
  selectedUser.value = user
}

const loadUsers = async () => {
  loading.value = true
  try {
    const response = await adminService.getUsers()
    users.value = response.data.data
  } catch (error) {
    toast.error('Error al cargar usuarios')
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadUsers()
})
</script>
