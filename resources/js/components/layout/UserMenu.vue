<template>
  <div class="relative">
    <button
      @click="isDropdownOpen = !isDropdownOpen"
      class="flex items-center gap-2 rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
    >
      <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
        <span class="text-sm font-medium">{{ user?.name?.charAt(0) || 'U' }}</span>
      </div>
      <div class="hidden flex-col items-start text-left md:flex">
        <span class="text-sm font-medium">{{ user?.name || 'User' }}</span>
        <span class="text-xs text-slate-500">{{ user?.email }}</span>
      </div>
      <ChevronDown class="h-4 w-4" />
    </button>

    <!-- Dropdown Menu -->
    <div v-if="isDropdownOpen" class="absolute right-0 top-12 z-10 w-80 rounded-lg border bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800">
      <!-- User Info Header -->
      <div class="flex flex-col gap-1 p-3">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
            <span class="text-sm font-medium">{{ user?.name?.charAt(0) || 'U' }}</span>
          </div>
          <div class="flex flex-col">
            <span class="text-sm font-medium">{{ user?.name || 'User' }}</span>
            <span class="text-xs text-slate-500">{{ user?.email }}</span>
          </div>
        </div>
      </div>

      <hr class="border-slate-200 dark:border-slate-700" />

      <!-- Menu Items -->
      <div class="p-2">
        <!-- Create Post -->
        <button
          @click="$emit('create-post')"
          class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 w-full text-left"
        >
          <Plus class="h-4 w-4" />
          Create Post
        </button>

        <!-- View Profile -->
        <router-link
          to="/profile"
          class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
        >
          <User class="h-4 w-4" />
          View Profile
        </router-link>

        <!-- Account Settings -->
        <router-link
          to="/profile/settings"
          class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
        >
          <Settings class="h-4 w-4" />
          Account Settings
        </router-link>

        <!-- Bookmarks -->
        <router-link
          to="/bookmarks"
          class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
        >
          <Bookmark class="h-4 w-4" />
          Bookmarks
        </router-link>
      </div>

      <hr class="border-slate-200 dark:border-slate-700" />

      <!-- Sign Out -->
      <div class="p-2">
        <button
          @click="signOut"
          class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
        >
          <LogOut class="h-4 w-4" />
          Sign Out
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import {
  Bookmark,
  ChevronDown,
  LogOut,
  Plus,
  Settings,
  User,
} from 'lucide-vue-next'
import { useAuthStore } from '../../stores/auth'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const router = useRouter()
const authStore = useAuthStore()
const isDropdownOpen = ref(false)

const signOut = async () => {
  authStore.logout()
  router.push('/login')
}
</script>