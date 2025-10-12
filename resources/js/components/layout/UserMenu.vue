<template>
  <div class="relative">
    <button 
      @click="showMenu = !showMenu"
      class="flex items-center space-x-2 text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
    >
      <img 
        :src="profilePicture" 
        :alt="user.name" 
        class="h-8 w-8 rounded-full"
      />
      <span>{{ user.name }}</span>
    </button>

    <!-- Dropdown Menu -->
    <div 
      v-if="showMenu"
      class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 z-50 border border-slate-200 dark:border-slate-700"
    >
      <router-link 
        to="/profile" 
        class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700"
      >
        Profile
      </router-link>
      <router-link 
        to="/bookmarks" 
        class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700"
      >
        Bookmarks
      </router-link>
      <button 
        @click="signOut"
        class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700"
      >
        Sign Out
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const router = useRouter()
const authStore = useAuthStore()
const showMenu = ref(false)

const profilePicture = computed(() => {
  return props.user.image || '/default-avatar.png'
})

const signOut = () => {
  authStore.logout()
  router.push('/login')
}
</script>
