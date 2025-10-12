<template>
  <div class="container mx-auto flex max-w-3xl flex-col gap-4 pt-4">
    <h1 class="text-2xl font-bold">Sign Up</h1>
    <form @submit.prevent="handleSubmit" class="flex w-full flex-col gap-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">First Name</label>
          <input
            v-model="form.first_name"
            type="text"
            placeholder="First Name"
            required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Name</label>
          <input
            v-model="form.last_name"
            type="text"
            placeholder="Last Name"
            required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
          />
        </div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Email"
          required
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
        />
      </div>
      
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
          <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            required
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
          />
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <input
          v-model="form.accept_terms"
          type="checkbox"
          required
          class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500"
        />
        <label class="text-sm text-slate-700 dark:text-slate-300">
          I accept the
          <router-link to="/terms-of-service" class="underline hover:text-cyan-500">Terms of Service</router-link>
          and
          <router-link to="/privacy-policy" class="underline hover:text-cyan-500">Privacy Policy</router-link>
        </label>
      </div>
      
      <div class="flex items-center gap-3">
        <input
          v-model="form.after_eighteen"
          type="checkbox"
          required
          class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500"
        />
        <label class="text-sm text-slate-700 dark:text-slate-300">I am at least 18 years old</label>
      </div>
      
      <button
        type="submit"
        :disabled="isSubmitting"
        class="w-full rounded-lg bg-cyan-500 px-4 py-2 font-medium text-white hover:bg-cyan-600 disabled:opacity-50"
      >
        {{ isSubmitting ? 'Creating...' : 'Create Account' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { profileApi } from '../../api'

const router = useRouter()

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: '',
  accept_terms: false,
  after_eighteen: false
})

const isSubmitting = ref(false)

const handleSubmit = async () => {
  isSubmitting.value = true
  
  try {
    const response = await profileApi.signup(form.value)
    
    if (response.data.status === 'success') {
      alert('Account created successfully! Please login.')
      router.push('/login')
    } else {
      alert('Failed to create account. Please try again.')
    }
  } catch (error) {
    console.error('Signup error:', error)
    alert('Failed to create account. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>