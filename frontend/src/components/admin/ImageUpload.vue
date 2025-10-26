<template>
  <div class="space-y-4">
    <!-- Upload Area -->
    <div
      @drop.prevent="handleDrop"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      :class="[
        'border-2 border-dashed rounded-lg p-6 text-center transition',
        isDragging ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-300'
      ]"
    >
      <input
        ref="fileInput"
        type="file"
        :accept="accept"
        :multiple="multiple"
        @change="handleFileSelect"
        class="hidden"
      />

      <div class="space-y-2">
        <div class="flex justify-center">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
        </div>
        <div>
          <p class="text-sm text-gray-600">
            <button
              type="button"
              @click="$refs.fileInput.click()"
              class="text-primary font-semibold hover:underline"
            >
              Haz clic para subir
            </button>
            o arrastra y suelta aquí
          </p>
          <p class="text-xs text-gray-500 mt-1">
            {{ accept }} (máx. {{ maxSizeMB }}MB {{ multiple ? 'cada una' : '' }})
          </p>
        </div>
      </div>
    </div>

    <!-- Uploading Progress -->
    <div v-if="uploading" class="space-y-2">
      <div class="flex items-center space-x-2">
        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
        <span class="text-sm text-gray-600">Subiendo {{ uploadingCount }} imagen(es)...</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div
          class="bg-primary h-2 rounded-full transition-all duration-300"
          :style="{ width: uploadProgress + '%' }"
        ></div>
      </div>
    </div>

    <!-- Image Previews -->
    <div v-if="imageUrls.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="(url, index) in imageUrls"
        :key="index"
        class="relative group aspect-square"
      >
        <img
          :src="getFullUrl(url)"
          :alt="`Imagen ${index + 1}`"
          class="w-full h-full object-cover rounded-lg border-2 border-gray-200"
        />
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center rounded-lg">
          <button
            type="button"
            @click="removeImage(index)"
            class="opacity-0 group-hover:opacity-100 transition bg-red-500 text-white rounded-full p-2 hover:bg-red-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
        <div
          v-if="index === 0 && multiple"
          class="absolute top-2 left-2 bg-primary text-white text-xs px-2 py-1 rounded-full font-semibold"
        >
          Principal
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import api from '../../services/api'
import { useToast } from 'vue-toastification'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => []
  },
  multiple: {
    type: Boolean,
    default: true
  },
  accept: {
    type: String,
    default: 'image/*'
  },
  maxSize: {
    type: Number,
    default: 5 * 1024 * 1024 // 5MB en bytes
  },
  maxFiles: {
    type: Number,
    default: 10
  },
  type: {
    type: String,
    default: 'product' // product, category, blog
  }
})

const emit = defineEmits(['update:modelValue', 'upload-success', 'upload-error'])

const toast = useToast()
const fileInput = ref(null)
const isDragging = ref(false)
const uploading = ref(false)
const uploadingCount = ref(0)
const uploadProgress = ref(0)

const imageUrls = computed({
  get: () => props.modelValue || [],
  set: (value) => emit('update:modelValue', value)
})

const maxSizeMB = computed(() => props.maxSize / (1024 * 1024))

const getFullUrl = (url) => {
  // Si ya es una URL completa, devolverla tal cual
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }
  // Si es una ruta relativa de storage, construir URL completa
  if (url.startsWith('/storage/')) {
    return `http://127.0.0.1:8000${url}`
  }
  return url
}

const validateFile = (file) => {
  // Validar tamaño
  if (file.size > props.maxSize) {
    toast.error(`${file.name} excede el tamaño máximo de ${maxSizeMB.value}MB`)
    return false
  }

  // Validar tipo
  const acceptedTypes = props.accept.split(',').map(t => t.trim())
  const isValidType = acceptedTypes.some(type => {
    if (type === 'image/*') {
      return file.type.startsWith('image/')
    }
    return file.type === type
  })

  if (!isValidType) {
    toast.error(`${file.name} no es un tipo de archivo válido`)
    return false
  }

  return true
}

const uploadFile = async (file) => {
  const formData = new FormData()
  formData.append('image', file)
  formData.append('type', props.type)

  try {
    const response = await api.post('/upload/image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    return response.data.data.url
  } catch (error) {
    throw error
  }
}

const handleFileSelect = async (event) => {
  const files = Array.from(event.target.files)
  await processFiles(files)
  // Limpiar input para permitir seleccionar el mismo archivo nuevamente
  event.target.value = ''
}

const handleDrop = async (event) => {
  isDragging.value = false
  const files = Array.from(event.dataTransfer.files)
  await processFiles(files)
}

const processFiles = async (files) => {
  // Validar número máximo de archivos
  const currentCount = imageUrls.value.length
  const newCount = files.length

  if (!props.multiple && newCount > 1) {
    toast.warning('Solo puedes subir una imagen')
    return
  }

  if (currentCount + newCount > props.maxFiles) {
    toast.warning(`Solo puedes subir un máximo de ${props.maxFiles} imágenes`)
    return
  }

  // Validar cada archivo
  const validFiles = files.filter(validateFile)

  if (validFiles.length === 0) return

  // Subir archivos
  uploading.value = true
  uploadingCount.value = validFiles.length
  uploadProgress.value = 0

  const uploadedUrls = []
  const errors = []

  for (let i = 0; i < validFiles.length; i++) {
    try {
      const url = await uploadFile(validFiles[i])
      uploadedUrls.push(url)
      uploadProgress.value = ((i + 1) / validFiles.length) * 100
    } catch (error) {
      errors.push(validFiles[i].name)
      console.error(`Error uploading ${validFiles[i].name}:`, error)
    }
  }

  uploading.value = false
  uploadProgress.value = 0

  // Actualizar URLs
  if (uploadedUrls.length > 0) {
    if (props.multiple) {
      imageUrls.value = [...imageUrls.value, ...uploadedUrls]
    } else {
      imageUrls.value = [uploadedUrls[0]]
    }
    emit('upload-success', uploadedUrls)
    toast.success(`${uploadedUrls.length} imagen(es) subida(s) exitosamente`)
  }

  // Reportar errores
  if (errors.length > 0) {
    emit('upload-error', errors)
    toast.error(`Error al subir ${errors.length} archivo(s)`)
  }
}

const removeImage = (index) => {
  const newUrls = [...imageUrls.value]
  newUrls.splice(index, 1)
  imageUrls.value = newUrls
}
</script>
