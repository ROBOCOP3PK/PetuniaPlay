import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import categoryService from '../services/categoryService'

export const useCategoryStore = defineStore('category', () => {
  // State
  const categories = ref([])
  const currentCategory = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Getters
  const hasCategories = computed(() => categories.value.length > 0)

  const parentCategories = computed(() => {
    return categories.value.filter(cat => !cat.parent_id)
  })

  const getCategoriesByParent = computed(() => {
    return (parentId) => {
      return categories.value.filter(cat => cat.parent_id === parentId)
    }
  })

  // Actions
  async function fetchCategories() {
    loading.value = true
    error.value = null
    try {
      const response = await categoryService.getAll()
      categories.value = response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar categorías'
      console.error('Error fetching categories:', err)
    } finally {
      loading.value = false
    }
  }

  async function fetchCategoryBySlug(slug) {
    loading.value = true
    error.value = null
    try {
      const response = await categoryService.getBySlug(slug)
      currentCategory.value = response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al cargar categoría'
      console.error('Error fetching category:', err)
    } finally {
      loading.value = false
    }
  }

  function clearCategories() {
    categories.value = []
    currentCategory.value = null
  }

  return {
    // State
    categories,
    currentCategory,
    loading,
    error,
    // Getters
    hasCategories,
    parentCategories,
    getCategoriesByParent,
    // Actions
    fetchCategories,
    fetchCategoryBySlug,
    clearCategories
  }
})
