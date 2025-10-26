<template>
  <div class="flex items-center gap-1">
    <!-- Stars -->
    <div class="flex items-center">
      <button
        v-for="star in 5"
        :key="star"
        type="button"
        :disabled="!interactive"
        @click="selectRating(star)"
        @mouseenter="interactive && (hoveredRating = star)"
        @mouseleave="interactive && (hoveredRating = 0)"
        :class="[
          'transition-colors duration-150',
          interactive ? 'cursor-pointer hover:scale-110 transform' : 'cursor-default',
          getStarClass(star)
        ]"
      >
        <svg
          class="w-5 h-5"
          :class="sizeClass"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
          />
        </svg>
      </button>
    </div>

    <!-- Rating Text -->
    <span v-if="showRating" class="text-sm text-gray-600 ml-1">
      {{ displayRating }}
    </span>

    <!-- Total Reviews -->
    <span v-if="totalReviews !== null" class="text-sm text-gray-500">
      ({{ totalReviews }})
    </span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  // Current rating value (0-5, can be decimal for display)
  modelValue: {
    type: Number,
    default: 0
  },
  // Whether the stars can be clicked to change rating
  interactive: {
    type: Boolean,
    default: false
  },
  // Show numeric rating next to stars
  showRating: {
    type: Boolean,
    default: false
  },
  // Total number of reviews (optional)
  totalReviews: {
    type: Number,
    default: null
  },
  // Size of stars
  size: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
})

const emit = defineEmits(['update:modelValue'])

const hoveredRating = ref(0)

const sizeClass = computed(() => {
  const sizes = {
    sm: 'w-4 h-4',
    md: 'w-5 h-5',
    lg: 'w-6 h-6'
  }
  return sizes[props.size]
})

const displayRating = computed(() => {
  return props.modelValue.toFixed(1)
})

const getStarClass = (star) => {
  const currentRating = props.interactive && hoveredRating.value > 0
    ? hoveredRating.value
    : props.modelValue

  if (currentRating >= star) {
    return 'text-yellow-400'
  } else if (currentRating >= star - 0.5) {
    return 'text-yellow-300'
  } else {
    return 'text-gray-300'
  }
}

const selectRating = (rating) => {
  if (props.interactive) {
    emit('update:modelValue', rating)
  }
}
</script>

<style scoped>
button:disabled {
  cursor: default;
}
</style>
