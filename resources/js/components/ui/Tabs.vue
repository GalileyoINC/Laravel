<template>
  <div class="w-full">
    <slot />
  </div>
</template>

<script setup>
import { provide, ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const activeTab = ref(props.modelValue)

watch(() => props.modelValue, (newValue) => {
  activeTab.value = newValue
})

watch(activeTab, (newValue) => {
  emit('update:modelValue', newValue)
})

provide('activeTab', activeTab)
provide('setActiveTab', (value) => {
  activeTab.value = value
})
</script>
