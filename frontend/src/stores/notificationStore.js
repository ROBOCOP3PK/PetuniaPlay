import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import notificationService from '../services/notificationService'

export const useNotificationStore = defineStore('notification', () => {
  // State
  const notifications = ref([])
  const unreadCount = ref(0)
  const loading = ref(false)
  const isOpen = ref(false)

  // Getters
  const unreadNotifications = computed(() =>
    notifications.value.filter(n => !n.read)
  )

  const hasUnread = computed(() => unreadCount.value > 0)

  // Actions
  async function fetchNotifications(params = {}) {
    loading.value = true
    try {
      const response = await notificationService.getNotifications(params)
      notifications.value = response.data.data
      return { success: true }
    } catch (error) {
      console.error('Error fetching notifications:', error)
      return { success: false, message: error.response?.data?.message || 'Error al cargar notificaciones' }
    } finally {
      loading.value = false
    }
  }

  async function fetchUnreadCount() {
    try {
      const response = await notificationService.getUnreadCount()
      unreadCount.value = response.data.count
    } catch (error) {
      console.error('Error fetching unread count:', error)
    }
  }

  async function markAsRead(id) {
    try {
      await notificationService.markAsRead(id)

      // Actualizar localmente
      const notification = notifications.value.find(n => n.id === id)
      if (notification) {
        notification.read = true
        notification.read_at = new Date().toISOString()
      }

      // Actualizar contador
      await fetchUnreadCount()

      return { success: true }
    } catch (error) {
      console.error('Error marking notification as read:', error)
      return { success: false, message: error.response?.data?.message || 'Error al marcar como leída' }
    }
  }

  async function markAllAsRead() {
    try {
      await notificationService.markAllAsRead()

      // Actualizar todas localmente
      notifications.value.forEach(n => {
        n.read = true
        n.read_at = new Date().toISOString()
      })

      unreadCount.value = 0

      return { success: true }
    } catch (error) {
      console.error('Error marking all as read:', error)
      return { success: false, message: error.response?.data?.message || 'Error al marcar todas como leídas' }
    }
  }

  async function deleteNotification(id) {
    try {
      await notificationService.deleteNotification(id)

      // Eliminar localmente
      notifications.value = notifications.value.filter(n => n.id !== id)

      // Actualizar contador
      await fetchUnreadCount()

      return { success: true }
    } catch (error) {
      console.error('Error deleting notification:', error)
      return { success: false, message: error.response?.data?.message || 'Error al eliminar notificación' }
    }
  }

  async function deleteReadNotifications() {
    try {
      await notificationService.deleteReadNotifications()

      // Eliminar localmente
      notifications.value = notifications.value.filter(n => !n.read)

      return { success: true }
    } catch (error) {
      console.error('Error deleting read notifications:', error)
      return { success: false, message: error.response?.data?.message || 'Error al eliminar notificaciones leídas' }
    }
  }

  function togglePanel() {
    isOpen.value = !isOpen.value
  }

  function closePanel() {
    isOpen.value = false
  }

  function openPanel() {
    isOpen.value = true
  }

  // Inicializar contador al cargar el store
  function init() {
    fetchUnreadCount()
    // Actualizar contador cada 60 segundos
    setInterval(() => {
      fetchUnreadCount()
    }, 60000)
  }

  return {
    // State
    notifications,
    unreadCount,
    loading,
    isOpen,

    // Getters
    unreadNotifications,
    hasUnread,

    // Actions
    fetchNotifications,
    fetchUnreadCount,
    markAsRead,
    markAllAsRead,
    deleteNotification,
    deleteReadNotifications,
    togglePanel,
    closePanel,
    openPanel,
    init
  }
})
