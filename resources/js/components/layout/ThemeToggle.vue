<template>
  <button
    @click="toggleTheme"
    class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
  >
    <Sun v-if="theme === 'light'" class="h-5 w-5" />
    <Moon v-else class="h-5 w-5" />
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Sun, Moon } from 'lucide-vue-next'

const theme = ref('light')

const toggleTheme = () => {
  if (theme.value === 'light') {
    theme.value = 'dark'
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    theme.value = 'light'
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

onMounted(() => {
  // Check for saved theme preference or default to 'light'
  const savedTheme = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  
  if (savedTheme) {
    theme.value = savedTheme
    if (savedTheme === 'dark') {
      document.documentElement.classList.add('dark')
    }
  } else if (prefersDark) {
    theme.value = 'dark'
    document.documentElement.classList.add('dark')
  }
})
</script>
