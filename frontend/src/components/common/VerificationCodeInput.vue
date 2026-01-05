<template>
  <div class="verification-code-input">
    <div class="flex justify-center gap-2 sm:gap-3">
      <input
        v-for="(digit, index) in digits"
        :key="index"
        :ref="el => inputs[index] = el"
        v-model="digits[index]"
        type="text"
        inputmode="numeric"
        maxlength="1"
        :disabled="disabled"
        class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-fuchsia-400
          bg-white dark:bg-gray-700
          text-gray-900 dark:text-white
          border-gray-300 dark:border-gray-600
          focus:border-fuchsia-500 dark:focus:border-fuchsia-400
          disabled:opacity-50 disabled:cursor-not-allowed"
        @input="handleInput(index, $event)"
        @keydown="handleKeydown(index, $event)"
        @paste="handlePaste"
        @focus="$event.target.select()"
      />
    </div>

    <!-- Timer and resend -->
    <div class="mt-4 text-center">
      <p v-if="timeLeft > 0" class="text-sm text-gray-600 dark:text-gray-400">
        El código expira en <span class="font-semibold text-fuchsia-600 dark:text-fuchsia-400">{{ formattedTime }}</span>
      </p>
      <p v-else class="text-sm text-red-600 dark:text-red-400 font-semibold">
        El código ha expirado
      </p>

      <button
        v-if="showResend"
        @click="$emit('resend')"
        :disabled="resendDisabled || disabled"
        class="mt-2 text-sm font-semibold transition-colors"
        :class="resendDisabled || disabled
          ? 'text-gray-400 dark:text-gray-500 cursor-not-allowed'
          : 'text-fuchsia-600 dark:text-fuchsia-400 hover:text-fuchsia-700 dark:hover:text-fuchsia-300'"
      >
        {{ resendDisabled ? `Reenviar en ${resendCountdown}s` : 'Reenviar código' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  expirationMinutes: {
    type: Number,
    default: 10
  },
  showResend: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['update:modelValue', 'complete', 'resend'])

const digits = ref(['', '', '', '', '', ''])
const inputs = ref([])
const timeLeft = ref(props.expirationMinutes * 60)
const resendCountdown = ref(0)
const resendDisabled = ref(false)

let timerInterval = null
let resendInterval = null

const formattedTime = computed(() => {
  const minutes = Math.floor(timeLeft.value / 60)
  const seconds = timeLeft.value % 60
  return `${minutes}:${seconds.toString().padStart(2, '0')}`
})

const code = computed(() => digits.value.join(''))

watch(code, (newCode) => {
  emit('update:modelValue', newCode)
  if (newCode.length === 6 && !newCode.includes('')) {
    emit('complete', newCode)
  }
})

watch(() => props.modelValue, (newValue) => {
  if (newValue && newValue.length === 6) {
    digits.value = newValue.split('')
  }
})

const handleInput = (index, event) => {
  const value = event.target.value.replace(/\D/g, '')

  if (value) {
    digits.value[index] = value.slice(-1)

    // Move to next input
    if (index < 5) {
      inputs.value[index + 1]?.focus()
    }
  }
}

const handleKeydown = (index, event) => {
  if (event.key === 'Backspace') {
    if (!digits.value[index] && index > 0) {
      inputs.value[index - 1]?.focus()
    }
    digits.value[index] = ''
  } else if (event.key === 'ArrowLeft' && index > 0) {
    inputs.value[index - 1]?.focus()
  } else if (event.key === 'ArrowRight' && index < 5) {
    inputs.value[index + 1]?.focus()
  }
}

const handlePaste = (event) => {
  event.preventDefault()
  const pastedData = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6)

  if (pastedData) {
    for (let i = 0; i < 6; i++) {
      digits.value[i] = pastedData[i] || ''
    }

    // Focus last filled input or first empty
    const lastIndex = Math.min(pastedData.length, 5)
    inputs.value[lastIndex]?.focus()
  }
}

const startTimer = () => {
  timerInterval = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      clearInterval(timerInterval)
    }
  }, 1000)
}

const startResendCooldown = () => {
  resendCountdown.value = 60
  resendDisabled.value = true

  resendInterval = setInterval(() => {
    if (resendCountdown.value > 0) {
      resendCountdown.value--
    } else {
      resendDisabled.value = false
      clearInterval(resendInterval)
    }
  }, 1000)
}

const reset = () => {
  digits.value = ['', '', '', '', '', '']
  timeLeft.value = props.expirationMinutes * 60
  inputs.value[0]?.focus()
}

const resetTimer = () => {
  timeLeft.value = props.expirationMinutes * 60
  startResendCooldown()
}

defineExpose({ reset, resetTimer })

onMounted(() => {
  startTimer()
  inputs.value[0]?.focus()
})

onUnmounted(() => {
  clearInterval(timerInterval)
  clearInterval(resendInterval)
})
</script>
