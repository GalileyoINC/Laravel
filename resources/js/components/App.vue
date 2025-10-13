<template>
  <div id="app" class="font-inter relative z-10 flex min-h-screen flex-col">
    <SiteHeader v-if="isAuthenticated" :show-map="true" />
    <main class="flex flex-1 flex-col">
      <RouterView />
    </main>
    <SiteFooter v-if="isAuthenticated" />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { RouterView } from 'vue-router'
import SiteHeader from './layout/SiteHeader.vue'
import SiteFooter from './layout/SiteFooter.vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const isAuthenticated = computed(() => {
  return !!authStore.token
})

// Check authentication status on app initialization
onMounted(() => {
  authStore.checkAuth()
})
</script>
