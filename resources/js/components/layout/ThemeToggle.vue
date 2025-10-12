<template>
  <div class="relative">
    <button
      @click="toggleDropdown"
      class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700"
    >
      <Sun v-if="theme === 'light'" class="h-5 w-5 rotate-0 scale-100 transition-all" />
      <Moon v-else-if="theme === 'dark'" class="h-5 w-5 rotate-0 scale-100 transition-all" />
      <Monitor v-else class="h-5 w-5 rotate-0 scale-100 transition-all" />
      <span class="sr-only">Toggle theme</span>
    </button>
    
    <!-- Dropdown Menu -->
    <div
      v-if="isDropdownOpen"
      @click="closeDropdown"
      class="fixed inset-0 z-10"
    ></div>
    
    <div
      v-if="isDropdownOpen"
      class="absolute right-0 top-full mt-2 w-32 rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800 z-20"
    >
      <button
        @click="setTheme('light')"
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"
      >
        <Sun class="h-4 w-4" />
        Light
      </button>
      <button
        @click="setTheme('dark')"
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"
      >
        <Moon class="h-4 w-4" />
        Dark
      </button>
      <button
        @click="setTheme('system')"
        class="flex w-full items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"
      >
        <Monitor class="h-4 w-4" />
        System
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Sun, Moon, Monitor } from 'lucide-vue-next'

const theme = ref('system')
const isDropdownOpen = ref(false)

const setTheme = (newTheme) => {
  theme.value = newTheme
  localStorage.setItem('theme', newTheme)
  
  if (newTheme === 'system') {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    if (prefersDark) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  } else if (newTheme === 'dark') {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  
  isDropdownOpen.value = false
}

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const closeDropdown = () => {
  isDropdownOpen.value = false
}

const handleSystemThemeChange = (e) => {
  if (theme.value === 'system') {
    if (e.matches) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }
}

onMounted(() => {
  // Check for saved theme preference or default to 'system'
  const savedTheme = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  
  if (savedTheme) {
    theme.value = savedTheme
  }
  
  // Apply theme
  if (theme.value === 'system') {
    if (prefersDark) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  } else if (theme.value === 'dark') {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  
  // Listen for system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', handleSystemThemeChange)
})

onUnmounted(() => {
  window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', handleSystemThemeChange)
})
</script>
