import { useToast } from 'vue-toastification'
import { h } from 'vue'

export function useConfirm() {
  const toast = useToast()

  const confirm = (message, options = {}) => {
    return new Promise((resolve) => {
      const {
        confirmText = 'Confirmar',
        cancelText = 'Cancelar',
        type = 'warning',
        timeout = false
      } = options

      // Crear componente personalizado para el toast
      const content = {
        // Componente de Vue para el contenido del toast
        render() {
          return h('div', { class: 'confirm-toast' }, [
            h('p', { class: 'confirm-message mb-4' }, message),
            h('div', { class: 'confirm-buttons flex gap-2 justify-end' }, [
              h(
                'button',
                {
                  class: 'px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-semibold text-sm',
                  onClick: () => {
                    toast.dismiss()
                    resolve(false)
                  }
                },
                cancelText
              ),
              h(
                'button',
                {
                  class: 'px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold text-sm',
                  onClick: () => {
                    toast.dismiss()
                    resolve(true)
                  }
                },
                confirmText
              )
            ])
          ])
        }
      }

      toast(content, {
        timeout,
        closeOnClick: false,
        closeButton: false,
        draggable: false,
        type,
        position: 'top-center',
        toastClassName: 'confirm-toast-container'
      })
    })
  }

  return { confirm }
}
