import { ref } from 'vue'

export function useConfirm() {
  const confirmDialog = ref({
    isOpen: false,
    title: '',
    message: '',
    type: 'warning',
    confirmText: 'Confirmar',
    cancelText: 'Cancelar',
    onConfirm: () => {},
    onCancel: () => {}
  })

  /**
   * Muestra un dialog de confirmación
   * @param {Object} options - Opciones del dialog
   * @returns {Promise<boolean>} true si confirma, false si cancela
   */
  const showConfirm = ({
    title,
    message,
    type = 'warning',
    confirmText = 'Confirmar',
    cancelText = 'Cancelar'
  }) => {
    return new Promise((resolve) => {
      confirmDialog.value = {
        isOpen: true,
        title,
        message,
        type,
        confirmText,
        cancelText,
        onConfirm: () => {
          confirmDialog.value.isOpen = false
          resolve(true)
        },
        onCancel: () => {
          confirmDialog.value.isOpen = false
          resolve(false)
        }
      }
    })
  }

  return {
    confirmDialog,
    showConfirm
  }
}
