<template>
  <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 px-4 backdrop-blur-sm transition-colors dark:border-slate-800 dark:bg-slate-950/95 md:px-6">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 py-8 sm:px-6 lg:px-8">
      <!-- Left side -->
      <div class="flex flex-1 items-center gap-2">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link
            to="/dashboard"
            class="w-20 text-cyan-500 hover:text-cyan-600"
          >
            <img src="/galileyo-icon.svg" alt="Galileyo" class="h-8 w-8" />
          </router-link>
        </div>
      </div>
      
      <!-- Middle area -->
      <div class="grow">
        <div class="relative mx-auto w-full max-w-xs">
          <CommandMenu />
        </div>
      </div>
      
      <!-- Right side -->
      <div class="flex flex-1 items-center justify-end gap-2">
        <!-- Map Button -->
        <router-link
          v-if="showMap"
          to="/alerts-map"
          class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          <MapIcon class="h-5 w-5" />
        </router-link>
        
        <!-- Theme Toggle -->
        <ThemeToggle />
        
        <!-- Create Post Button -->
        <button
          @click="openCreatePost"
          class="flex items-center gap-2 rounded-lg bg-cyan-500 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-600"
        >
          <PlusIcon class="h-4 w-4" />
          <span class="hidden sm:inline">Post</span>
        </button>
        
        <!-- User Menu -->
        <UserMenu :user="clientUser" @create-post="openCreatePost" />
      </div>
    </div>

    <!-- Create Post Modal -->
    <Teleport to="body">
      <CreatePostModal
        :is-open="isCreatePostOpen"
        @close="closeCreatePost"
        @created="handlePostCreated"
      />
    </Teleport>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { MapIcon, PlusIcon } from 'lucide-vue-next'
import CommandMenu from './CommandMenu.vue'
import ThemeToggle from './ThemeToggle.vue'
import UserMenu from './UserMenu.vue'
import CreatePostModal from '../feed/CreatePostModal.vue'

const props = defineProps({
  showMap: {
    type: Boolean,
    default: false
  }
})

const clientUser = ref(null)
const isCreatePostOpen = ref(false)

onMounted(() => {
  if (typeof window !== 'undefined') {
    const userProfile = localStorage.getItem('user_profile')
    if (userProfile) {
      try {
        const parsed = JSON.parse(userProfile)
        clientUser.value = {
          name: parsed.first_name || parsed.name || 'User',
          email: parsed.email || ''
        }
      } catch (error) {
        console.error('Error parsing user profile:', error)
      }
    }
  }
})

const openCreatePost = () => {
  isCreatePostOpen.value = true
}

const closeCreatePost = () => {
  isCreatePostOpen.value = false
}

const handlePostCreated = () => {
  isCreatePostOpen.value = false
  // Refresh the page to show new post
  window.location.reload()
}
</script>