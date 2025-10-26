import { ref } from 'vue'

const THEME_KEY = 'petunia-theme'
const DARK_CLASS = 'dark'

// Estado compartido entre todos los componentes
const isDark = ref(false)
const isInitialized = ref(false)

// Función para aplicar el tema al DOM
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

export function useTheme() {
  const initTheme = () => {
    if (isInitialized.value) return

    // Leer preferencia guardada, por defecto siempre modo claro
    const savedTheme = localStorage.getItem(THEME_KEY)

    // Solo activar dark mode si el usuario lo seleccionó explícitamente
    isDark.value = savedTheme === 'dark'

    // Aplicar tema inicial
    applyTheme(isDark.value)
    isInitialized.value = true
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

  return {
    isDark,
    toggleTheme,
    setTheme,
    initTheme
  }
}
