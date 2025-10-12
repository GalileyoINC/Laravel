<template>
  <button
    @click="handleClick"
    :class="[
      'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 dark:ring-offset-slate-950 dark:focus-visible:ring-slate-300',
      isActive ? 'bg-white text-slate-950 shadow-sm dark:bg-slate-950 dark:text-slate-50' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-50'
    ]"
  >
    <slot />
  </button>
</template>

<script setup>
import { inject, computed } from 'vue'

const props = defineProps({
  value: {
    type: String,
    required: true
  }
})

const activeTab = inject('activeTab')
const setActiveTab = inject('setActiveTab')

const isActive = computed(() => activeTab.value === props.value)

const handleClick = () => {
  setActiveTab(props.value)
}
</script>
