import { ref, watch, onMounted } from 'vue'

const THEME_KEY = 'petunia-theme'
const DARK_CLASS = 'dark'

// Estado compartido entre todos los componentes
const isDark = ref(false)
const isInitialized = ref(false)

export function useTheme() {
  const initTheme = () => {
    if (isInitialized.value) return

    // Leer preferencia guardada o usar preferencia del sistema
    const savedTheme = localStorage.getItem(THEME_KEY)

    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    } else {
      // Detectar preferencia del sistema
      isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    // Aplicar tema inicial
    applyTheme(isDark.value)
    isInitialized.value = true
  }

  const applyTheme = (dark) => {
    const root = document.documentElement

    if (dark) {
      root.classList.add(DARK_CLASS)
      root.setAttribute('data-theme', 'dark')
    } else {
      root.classList.remove(DARK_CLASS)
      root.setAttribute('data-theme', 'light')
    }
  }

  const toggleTheme = () => {
    isDark.value = !isDark.value
    applyTheme(isDark.value)
    localStorage.setItem(THEME_KEY, isDark.value ? 'dark' : 'light')
  }

  const setTheme = (dark) => {
    isDark.value = dark
    applyTheme(dark)
    localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light')
  }

  // Escuchar cambios en la preferencia del sistema
  onMounted(() => {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')

    const handleChange = (e) => {
      // Solo actualizar si el usuario no ha establecido una preferencia manual
      if (!localStorage.getItem(THEME_KEY)) {
        isDark.value = e.matches
        applyTheme(e.matches)
      }
    }

    // Usar addEventListener en lugar de addListener (deprecated)
    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', handleChange)
    } else {
      // Fallback para navegadores antiguos
      mediaQuery.addListener(handleChange)
    }

    // Cleanup
    return () => {
      if (mediaQuery.removeEventListener) {
        mediaQuery.removeEventListener('change', handleChange)
      } else {
        mediaQuery.removeListener(handleChange)
      }
    }
  })

  return {
    isDark,
    toggleTheme,
    setTheme,
    initTheme
  }
}
