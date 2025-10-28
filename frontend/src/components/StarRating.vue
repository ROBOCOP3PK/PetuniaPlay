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
          interactive ? 'cursor-pointer hover:scale-110 transform' : 'cursor-default'
        ]"
      >
        <i
          :class="[
            getStarIcon(star),
            getStarClass(star),
            sizeClass
          ]"
        ></i>
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
    sm: 'text-base',
    md: 'text-xl',
    lg: 'text-2xl'
  }
  return sizes[props.size]
})

const displayRating = computed(() => {
  return props.modelValue.toFixed(1)
})

const getStarIcon = (star) => {
  const currentRating = props.interactive && hoveredRating.value > 0
    ? hoveredRating.value
    : props.modelValue

  if (currentRating >= star) {
    return 'pi pi-star-fill'
  } else if (currentRating >= star - 0.5) {
    return 'pi pi-star-half-fill'
  } else {
    return 'pi pi-star'
  }
}

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
