<template>
  <div class="space-y-4">
    <!-- Mode Toggle -->
    <div class="flex gap-3">
      <button
        @click="mode = 'manual'"
        :class="[
          'flex-1 py-2 px-4 rounded-lg font-semibold transition',
          mode === 'manual'
            ? 'bg-primary text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        ✍️ Escribir Dirección
      </button>
      <button
        @click="mode = 'map'"
        :class="[
          'flex-1 py-2 px-4 rounded-lg font-semibold transition',
          mode === 'map'
            ? 'bg-primary text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        📍 Seleccionar en Mapa
      </button>
    </div>

    <!-- Manual Address Input -->
    <div v-if="mode === 'manual'" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold mb-2">Dirección Completa *</label>
        <input
          v-model="manualAddress.address"
          @input="emitManualAddress"
          type="text"
          required
          class="input-field"
          placeholder="Calle 123 #45-67"
        />
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2">Ciudad *</label>
          <input
            v-model="manualAddress.city"
            @input="emitManualAddress"
            type="text"
            required
            class="input-field"
            placeholder="Bogotá"
          />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Departamento *</label>
          <input
            v-model="manualAddress.state"
            @input="emitManualAddress"
            type="text"
            required
            class="input-field"
            placeholder="Cundinamarca"
          />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">Código Postal</label>
          <input
            v-model="manualAddress.zipCode"
            @input="emitManualAddress"
            type="text"
            class="input-field"
            placeholder="110111"
          />
        </div>
      </div>
    </div>

    <!-- Map Mode -->
    <div v-else-if="mode === 'map'" class="space-y-4">
      <!-- Map Container -->
      <div class="relative">
        <div
          ref="mapContainer"
          class="w-full h-96 rounded-lg border-2 border-gray-300"
        ></div>

        <!-- Loading Overlay -->
        <div
          v-if="loadingMap"
          class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded-lg"
        >
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-2"></div>
            <p class="text-gray-600">Cargando mapa...</p>
          </div>
        </div>
      </div>

      <!-- Map Instructions -->
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex gap-3">
          <div class="text-blue-600 text-xl">ℹ️</div>
          <div>
            <p class="font-semibold text-blue-900 mb-1">Cómo usar el mapa:</p>
            <ul class="text-sm text-blue-800 space-y-1">
              <li>• Arrastra el marcador rojo 📍 a tu ubicación</li>
              <li>• O haz clic en el mapa para mover el marcador</li>
              <li>• La dirección se completará automáticamente</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Geocoded Address Display -->
      <div v-if="geocodedAddress.address" class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <div class="text-green-600 text-xl">✅</div>
          <div class="flex-1">
            <p class="font-semibold text-green-900 mb-2">Dirección Seleccionada:</p>
            <div class="text-sm text-green-800 space-y-1">
              <p><strong>Dirección:</strong> {{ geocodedAddress.address }}</p>
              <p><strong>Ciudad:</strong> {{ geocodedAddress.city }}</p>
              <p><strong>Departamento:</strong> {{ geocodedAddress.state }}</p>
              <p v-if="geocodedAddress.zipCode"><strong>Código Postal:</strong> {{ geocodedAddress.zipCode }}</p>
              <p class="text-xs text-green-700 mt-2">
                📍 Coordenadas: {{ selectedCoordinates.lat.toFixed(6) }}, {{ selectedCoordinates.lng.toFixed(6) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading Geocoding -->
      <div v-if="loadingGeocode" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-yellow-600"></div>
          <p class="text-yellow-800">Obteniendo dirección...</p>
        </div>
      </div>

      <!-- Delivery Area Warning -->
      <div v-if="!isWithinDeliveryArea && geocodedAddress.address" class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <div class="text-orange-600 text-xl">⚠️</div>
          <div>
            <p class="font-semibold text-orange-900 mb-1">Ubicación fuera del área de cobertura</p>
            <p class="text-sm text-orange-800">
              La ubicación seleccionada está fuera de nuestra área de entrega actual.
              Nuestro servicio cubre principalmente Bogotá y sus alrededores (hasta 50 km del centro).
              Puedes continuar con tu pedido y nos contactaremos contigo para coordinar el envío.
            </p>
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <div class="text-red-600 text-xl">⚠️</div>
          <div>
            <p class="font-semibold text-red-900 mb-1">Error</p>
            <p class="text-sm text-red-800">{{ errorMessage }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Loader } from '@googlemaps/js-api-loader'

const emit = defineEmits(['update:address'])

const mode = ref('manual')
const mapContainer = ref(null)
const loadingMap = ref(false)
const loadingGeocode = ref(false)
const errorMessage = ref('')

// Google Maps instances
let map = null
let marker = null
let geocoder = null

// Address data
const manualAddress = ref({
  address: '',
  city: '',
  state: '',
  zipCode: ''
})

const geocodedAddress = ref({
  address: '',
  city: '',
  state: '',
  zipCode: ''
})

const selectedCoordinates = ref({
  lat: 4.7110, // Bogotá default
  lng: -74.0721
})

const isWithinDeliveryArea = ref(true)

// Delivery area configuration (Bogotá center)
const DELIVERY_CENTER = {
  lat: 4.7110,
  lng: -74.0721
}
const DELIVERY_RADIUS_KM = 50 // 50 km radius from Bogotá center

// Calculate distance between two coordinates using Haversine formula
const calculateDistance = (lat1, lon1, lat2, lon2) => {
  const R = 6371 // Radius of the Earth in km
  const dLat = (lat2 - lat1) * Math.PI / 180
  const dLon = (lon2 - lon1) * Math.PI / 180
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2)
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
  return R * c // Distance in km
}

// Validate if location is within delivery area
const validateDeliveryArea = (lat, lng) => {
  const distance = calculateDistance(
    DELIVERY_CENTER.lat,
    DELIVERY_CENTER.lng,
    lat,
    lng
  )
  isWithinDeliveryArea.value = distance <= DELIVERY_RADIUS_KM
  return isWithinDeliveryArea.value
}

// Initialize Google Maps
const initMap = async () => {
  if (!mapContainer.value || map) return

  loadingMap.value = true
  errorMessage.value = ''

  try {
    const loader = new Loader({
      apiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || 'YOUR_API_KEY_HERE',
      version: 'weekly',
      libraries: ['places']
    })

    const google = await loader.load()

    // Create map
    map = new google.maps.Map(mapContainer.value, {
      center: selectedCoordinates.value,
      zoom: 15,
      mapTypeControl: true,
      streetViewControl: true,
      fullscreenControl: true
    })

    // Create marker
    marker = new google.maps.Marker({
      position: selectedCoordinates.value,
      map: map,
      draggable: true,
      title: 'Arrastra para seleccionar tu ubicación'
    })

    // Initialize geocoder
    geocoder = new google.maps.Geocoder()

    // Add marker drag listener
    marker.addListener('dragend', () => {
      const position = marker.getPosition()
      selectedCoordinates.value = {
        lat: position.lat(),
        lng: position.lng()
      }
      reverseGeocode()
    })

    // Add map click listener
    map.addListener('click', (event) => {
      const position = event.latLng
      selectedCoordinates.value = {
        lat: position.lat(),
        lng: position.lng()
      }
      marker.setPosition(position)
      reverseGeocode()
    })

    // Try to get user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          }
          selectedCoordinates.value = userLocation
          map.setCenter(userLocation)
          marker.setPosition(userLocation)
          reverseGeocode()
        },
        (error) => {
          console.log('Geolocation error:', error)
          // Use default location (Bogotá)
        }
      )
    }

  } catch (error) {
    console.error('Error loading Google Maps:', error)
    errorMessage.value = 'Error al cargar el mapa. Por favor, intenta con la opción de escritura manual.'
  } finally {
    loadingMap.value = false
  }
}

// Reverse geocode coordinates to address
const reverseGeocode = async () => {
  if (!geocoder) return

  loadingGeocode.value = true
  errorMessage.value = ''

  try {
    const response = await geocoder.geocode({
      location: selectedCoordinates.value
    })

    if (response.results && response.results.length > 0) {
      const result = response.results[0]

      // Extract address components
      let address = ''
      let city = ''
      let state = ''
      let zipCode = ''

      // Get formatted address
      address = result.formatted_address

      // Parse address components
      result.address_components.forEach(component => {
        const types = component.types

        if (types.includes('locality')) {
          city = component.long_name
        } else if (types.includes('administrative_area_level_1')) {
          state = component.long_name
        } else if (types.includes('postal_code')) {
          zipCode = component.long_name
        }
      })

      geocodedAddress.value = {
        address: address || '',
        city: city || 'Bogotá',
        state: state || 'Cundinamarca',
        zipCode: zipCode || ''
      }

      // Validate delivery area
      validateDeliveryArea(selectedCoordinates.value.lat, selectedCoordinates.value.lng)

      // Emit to parent
      emit('update:address', {
        ...geocodedAddress.value,
        latitude: selectedCoordinates.value.lat,
        longitude: selectedCoordinates.value.lng,
        withinDeliveryArea: isWithinDeliveryArea.value
      })

    } else {
      errorMessage.value = 'No se pudo obtener la dirección para esta ubicación'
    }
  } catch (error) {
    console.error('Geocoding error:', error)
    errorMessage.value = 'Error al obtener la dirección. Por favor, intenta nuevamente.'
  } finally {
    loadingGeocode.value = false
  }
}

// Emit manual address
const emitManualAddress = () => {
  emit('update:address', {
    ...manualAddress.value,
    latitude: null,
    longitude: null
  })
}

// Watch mode changes
watch(mode, (newMode) => {
  if (newMode === 'map') {
    setTimeout(() => {
      initMap()
    }, 100)
  }
})

onMounted(() => {
  if (mode.value === 'map') {
    initMap()
  }
})
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  transition: border-color 0.2s;
}

.input-field:focus {
  outline: none;
  border-color: #a97447;
  box-shadow: 0 0 0 3px rgba(169, 116, 71, 0.1);
}
</style>
