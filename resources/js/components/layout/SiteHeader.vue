<template>
  <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container flex h-16 items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center space-x-2">
        <router-link to="/" class="flex items-center space-x-2">
          <img src="/galileyo-icon.svg" alt="Galileyo" class="h-8 w-8" />
          <span class="text-xl font-bold text-slate-900 dark:text-white">Galileyo</span>
        </router-link>
      </div>

      <!-- Navigation -->
      <nav class="hidden md:flex items-center space-x-6">
        <router-link 
          v-for="item in navigationItems" 
          :key="item.name"
          :to="item.href" 
          class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white transition-colors"
        >
          {{ item.name }}
        </router-link>
      </nav>

      <!-- User Menu -->
      <div class="flex items-center space-x-4">
        <!-- Theme Toggle -->
        <button
          @click="toggleTheme"
          class="p-2 rounded-md text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white transition-colors"
        >
          <Sun v-if="theme === 'dark'" class="h-5 w-5" />
          <Moon v-else class="h-5 w-5" />
        </button>

        <template v-if="isAuthenticated">
          <UserMenu :user="user" />
        </template>
        <template v-else>
          <router-link 
            to="/login" 
            class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
          >
            Login
          </router-link>
          <router-link 
            to="/sign-up" 
            class="bg-cyan-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-cyan-600 transition-colors"
          >
            Sign Up
          </router-link>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import UserMenu from './UserMenu.vue'
import { Sun, Moon } from 'lucide-vue-next'

const authStore = useAuthStore()

const isAuthenticated = computed(() => !!authStore.user)
const user = computed(() => authStore.user)

const theme = ref('light')

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
  document.documentElement.classList.toggle('dark')
  localStorage.setItem('theme', theme.value)
}

onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    theme.value = savedTheme
    if (savedTheme === 'dark') {
      document.documentElement.classList.add('dark')
    }
  } else {
    // Check system preference
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      theme.value = 'dark'
      document.documentElement.classList.add('dark')
    }
  }
})

const navigationItems = [
  { name: 'Home', href: '/' },
  { name: 'Blog', href: '/blog' },
  { name: 'Contact', href: '/contact' },
  { name: 'FAQ', href: '/faq' },
]
</script>
