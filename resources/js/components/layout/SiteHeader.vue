<template>
  <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-sm transition-colors dark:border-slate-800 dark:bg-slate-950/95">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 py-8 sm:px-6 lg:px-8">
      <!-- Left side -->
      <div class="flex items-center gap-3 shrink-0">
        <!-- Mobile Menu Button (TODO) -->
        <button
          class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors h-9 w-9 p-1 lg:hidden text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800"
          type="button"
          @click="isMobileNavOpen = !isMobileNavOpen"
          :aria-expanded="isMobileNavOpen ? 'true' : 'false'"
          aria-controls="mobile-nav"
          aria-label="Toggle navigation"
        >
          <svg class="pointer-events-none" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path v-if="!isMobileNavOpen" d="M3 6h18M3 12h18M3 18h18"></path>
            <path v-else d="M6 6l12 12M18 6l-12 12"></path>
          </svg>
        </button>

        <!-- Logo -->
        <div class="flex items-center gap-6">
          <router-link to="/" class="flex w-20">
            <img src="/galileyo_new_logo.png" alt="Galileyo" class="object-contain" />
          </router-link>
        </div>
      </div>

      <!-- Middle area - Navigation for public users -->
      <div v-if="!isAuthenticated" class="flex items-center gap-3 shrink-0">
        <div class="flex items-center gap-6">
          <nav class="relative z-10 flex max-w-max flex-1 items-center justify-center max-md:hidden">
            <ul class="group flex flex-1 list-none items-center justify-center space-x-1 gap-2">
              <li>
                <router-link to="/" class="py-1.5 font-medium text-muted-foreground hover:text-primary">
                  Home
                </router-link>
              </li>
              <li>
                <router-link to="/faq" class="py-1.5 font-medium text-muted-foreground hover:text-primary">
                  FAQ
                </router-link>
              </li>
              <li>
                <router-link to="/contact" class="py-1.5 font-medium text-muted-foreground hover:text-primary">
                  Contact
                </router-link>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Middle area - Search for authenticated users -->
      <div v-else class="grow min-w-0">
        <div class="relative mx-auto w-full max-w-full sm:max-w-sm md:max-w-md lg:max-w-lg overflow-hidden min-w-0">
          <CommandMenu />
        </div>
      </div>

      <!-- Right side -->
      <div class="flex items-center gap-3 shrink-0">
        <!-- Public Navigation (not logged in) -->
        <template v-if="!isAuthenticated">
          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Sign In Button -->
          <router-link
            to="/login"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-400 hover:bg-slate-100 hover:text-slate-900 h-9 px-4 py-2 text-slate-700 dark:text-white dark:hover:bg-slate-800"
          >
            Sign In
          </router-link>
        </template>

        <!-- Authenticated Navigation (logged in) -->
        <template v-else>
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

          <!-- Push Notification Toggle (for testing) -->
          <PushNotificationToggle />

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
        </template>
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
  <!-- Mobile Drawer Nav -->
  <Teleport to="body">
    <div v-if="isMobileNavOpen" class="fixed inset-0 z-[60] md:hidden">
      <div class="absolute inset-0 bg-black/40" @click="isMobileNavOpen = false"></div>
      <aside
        class="absolute left-0 top-0 h-full w-64 bg-white dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 shadow-xl p-4 transform transition-transform duration-200 ease-out -translate-x-full"
        :class="{ 'translate-x-0': isMobileNavOpen }"
      >
        <div class="mb-4 flex items-center justify-between">
          <span class="text-sm font-medium text-slate-500">Menu</span>
          <button class="rounded p-2 hover:bg-slate-100 dark:hover:bg-slate-800" @click="isMobileNavOpen = false" aria-label="Close menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 6l12 12M18 6l-12 12"></path>
            </svg>
          </button>
        </div>
        <nav>
          <ul class="flex flex-col gap-1">
            <li>
              <router-link @click="isMobileNavOpen = false" to="/" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Home</router-link>
            </li>
            <template v-if="!isAuthenticated">
              <li>
                <router-link @click="isMobileNavOpen = false" to="/faq" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">FAQ</router-link>
              </li>
              <li>
                <router-link @click="isMobileNavOpen = false" to="/contact" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Contact</router-link>
              </li>
            </template>
            <template v-else>
              <li>
                <router-link @click="isMobileNavOpen = false" to="/dashboard" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Dashboard</router-link>
              </li>
              <li>
                <router-link @click="isMobileNavOpen = false" to="/profile" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Profile</router-link>
              </li>
              <li>
                <router-link @click="isMobileNavOpen = false" to="/alerts-map" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Alerts Map</router-link>
              </li>
              <li>
                <router-link @click="isMobileNavOpen = false" to="/payment" class="block rounded px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800">Payment</router-link>
              </li>
            </template>
          </ul>
        </nav>
      </aside>
    </div>
  </Teleport>

  <!-- Chat Widget (Floating) -->
  <Teleport to="body">
    <ChatWidget v-if="isAuthenticated" />
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { useRouter } from 'vue-router'
import { MapIcon, PlusIcon } from 'lucide-vue-next'
import CommandMenu from './CommandMenu.vue'
import ThemeToggle from './ThemeToggle.vue'
import UserMenu from './UserMenu.vue'
import CreatePostModal from '../feed/CreatePostModal.vue'
import PushNotificationToggle from './PushNotificationToggle.vue'
import ChatWidget from '../chat/ChatWidget.vue'
import { useAuthStore } from '../../stores/auth'

const props = defineProps({
  showMap: {
    type: Boolean,
    default: false
  }
})

const authStore = useAuthStore()
const router = useRouter()
const clientUser = ref(null)
const isCreatePostOpen = ref(false)
const isMobileNavOpen = ref(false)

const isAuthenticated = computed(() => {
  return !!authStore.token
})

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
  // Close drawer on route change
  router.afterEach(() => {
    isMobileNavOpen.value = false
  })
})

// Lock body scroll when drawer is open
watch(isMobileNavOpen, (open) => {
  if (typeof document !== 'undefined') {
    document.body.classList.toggle('overflow-hidden', open)
  }
})

onBeforeUnmount(() => {
  if (typeof document !== 'undefined') {
    document.body.classList.remove('overflow-hidden')
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
