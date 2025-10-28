<template>
  <div class="relative">
    <!-- Bell Icon Button -->
    <button
      @click="handleBellClick"
      class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
      :class="{ 'text-primary dark:text-primary-light': isOpen }"
    >
      <!-- Bell Icon -->
      <i class="pi pi-bell text-2xl"></i>

      <!-- Badge para contador de no leídas -->
      <span
        v-if="hasUnread"
        class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"
      >
        {{ unreadCount > 99 ? '99+' : unreadCount }}
      </span>
    </button>

    <!-- Dropdown Panel -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 border border-gray-200 dark:border-gray-700"
      >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Notificaciones
          </h3>
          <button
            v-if="hasUnread"
            @click="handleMarkAllAsRead"
            class="text-sm text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-primary transition"
          >
            Marcar todas como leídas
          </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
          <!-- Loading -->
          <div v-if="loading" class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          </div>

          <!-- Empty State -->
          <div
            v-else-if="notifications.length === 0"
            class="flex flex-col items-center justify-center py-8 px-4 text-center"
          >
            <i class="pi pi-bell text-6xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400">
              No tienes notificaciones
            </p>
          </div>

          <!-- Notifications -->
          <div v-else>
            <div
              v-for="notification in notifications"
              :key="notification.id"
              class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer"
              :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.read }"
              @click="handleNotificationClick(notification)"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1 mr-3">
                  <div class="flex items-center mb-1">
                    <span
                      v-if="!notification.read"
                      class="w-2 h-2 bg-primary rounded-full mr-2"
                    ></span>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                      {{ notification.title }}
                    </h4>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ notification.message }}
                  </p>
                  <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    {{ notification.time_ago }}
                  </p>
                </div>

                <!-- Botón eliminar -->
                <button
                  @click.stop="handleDelete(notification.id)"
                  class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition"
                >
                  <i class="pi pi-times text-xl"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div
          v-if="notifications.length > 0"
          class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-center"
        >
          <button
            @click="handleDeleteRead"
            class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition"
          >
            Eliminar notificaciones leídas
          </button>
        </div>
      </div>
    </transition>

    <!-- Overlay para cerrar al hacer click fuera -->
    <div
      v-if="isOpen"
      @click="closePanel"
      class="fixed inset-0 z-40"
    ></div>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { useNotificationStore } from '../../stores/notificationStore'
import { useAuthStore } from '../../stores/authStore'

const notificationStore = useNotificationStore()
const authStore = useAuthStore()

// Computed
const notifications = computed(() => notificationStore.notifications)
const unreadCount = computed(() => notificationStore.unreadCount)
const hasUnread = computed(() => notificationStore.hasUnread)
const loading = computed(() => notificationStore.loading)
const isOpen = computed(() => notificationStore.isOpen)

// Methods
const handleBellClick = async () => {
  notificationStore.togglePanel()

  // Si se abre el panel y no hay notificaciones, cargarlas
  if (notificationStore.isOpen && notifications.value.length === 0) {
    await notificationStore.fetchNotifications({ limit: 20 })
  }
}

const closePanel = () => {
  notificationStore.closePanel()
}

const handleNotificationClick = async (notification) => {
  // Marcar como leída si no lo está
  if (!notification.read) {
    await notificationStore.markAsRead(notification.id)
  }

  // Aquí puedes agregar navegación si la notificación tiene un link
  // if (notification.data?.link) {
  //   router.push(notification.data.link)
  // }
}

const handleMarkAllAsRead = async () => {
  await notificationStore.markAllAsRead()
}

const handleDelete = async (id) => {
  await notificationStore.deleteNotification(id)
}

const handleDeleteRead = async () => {
  await notificationStore.deleteReadNotifications()
}

// Lifecycle
onMounted(() => {
  if (authStore.isAuthenticated) {
    notificationStore.init()
  }
})

// Watch para inicializar cuando el usuario se autentique
watch(() => authStore.isAuthenticated, (isAuth) => {
  if (isAuth) {
    notificationStore.init()
  }
})
</script>
