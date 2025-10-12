<template>
  <div class="flex flex-col gap-6">
    <form @submit.prevent="handleSubmit">
      <div class="flex flex-col gap-6">
        <div class="flex flex-col items-center gap-2">
          <div class="flex flex-col items-center gap-2 font-medium">
            <div class="flex size-8 items-center justify-center rounded-md">
              <img src="/galileyo-icon.svg" alt="Galileyo" class="h-8 w-8" />
            </div>
            <span class="sr-only">Galileyo</span>
          </div>
          <h1 class="text-xl font-bold">Welcome to Galileyo</h1>
          <div class="text-center text-sm">
            Don't have an account? 
            <router-link to="/sign-up" class="underline underline-offset-4">
              Sign up
            </router-link>
          </div>
        </div>
        
        <div class="flex flex-col gap-6">
          <div v-if="isSuccess" class="animate-fade-in rounded-lg border bg-white p-6 dark:bg-slate-800">
            <div class="flex flex-col items-center gap-2">
              <div class="animate-bounce-in mb-2 flex h-12 w-12 items-center justify-center rounded-full">
                <CheckCircle2 class="size-12 text-green-500" />
              </div>
              <h2 class="text-2xl font-bold">Successfully logged in!</h2>
            </div>
            <div class="mt-4 flex flex-col items-center gap-4">
              <p class="text-center text-base">
                Welcome back! Redirecting to dashboard...
              </p>
            </div>
          </div>
          
          <div v-else class="space-y-6">
            <div class="grid gap-3">
              <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                Email
              </label>
              <input
                id="email"
                v-model="email"
                type="email"
                placeholder="me@example.com"
                required
                :class="[
                  'w-full rounded-lg border px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white',
                  errors.email ? 'border-red-500' : 'border-slate-300'
                ]"
              />
              <p v-if="errors.email" class="text-sm text-red-500">{{ errors.email }}</p>
            </div>
            
            <div class="grid gap-3">
              <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                Password
              </label>
              <input
                id="password"
                v-model="password"
                type="password"
                placeholder="Enter your password"
                required
                :class="[
                  'w-full rounded-lg border px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white',
                  errors.password ? 'border-red-500' : 'border-slate-300'
                ]"
              />
              <p v-if="errors.password" class="text-sm text-red-500">{{ errors.password }}</p>
            </div>
            
            <button
              type="submit"
              :disabled="isLoading"
              class="w-full rounded-lg bg-cyan-500 px-4 py-2 font-medium text-white hover:bg-cyan-600 disabled:opacity-50"
            >
              {{ isLoading ? 'Logging in...' : 'Login' }}
            </button>
          </div>
        </div>
      </div>
    </form>
    
    <div class="text-balance text-center text-xs text-slate-500">
      By clicking continue, you agree to our
      <router-link to="/terms-of-service" class="underline underline-offset-4 hover:text-cyan-500">
        Terms of Service
      </router-link>
      and
      <router-link to="/privacy-policy" class="underline underline-offset-4 hover:text-cyan-500">
        Privacy Policy
      </router-link>
      .
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { CheckCircle2 } from 'lucide-vue-next'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const isLoading = ref(false)
const isSuccess = ref(false)
const errors = ref({
  email: '',
  password: ''
})

// Check if user is already logged in
onMounted(() => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('auth_token')
    if (token) {
      router.push('/dashboard')
    }
  }
})

const handleSubmit = async () => {
  isLoading.value = true
  errors.value = {}

  try {
    const result = await authStore.login(email.value, password.value)
    
    if (result.success) {
      isSuccess.value = true
      
      // Redirect to dashboard after a short delay
      setTimeout(() => {
        router.push('/dashboard')
      }, 1000)
    } else {
      errors.value.email = result.error || 'Login failed'
    }
  } catch (error) {
    errors.value.email = 'Network error'
  } finally {
    isLoading.value = false
  }
}
</script>
