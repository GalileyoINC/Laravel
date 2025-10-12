<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="$emit('close')">
    <div class="w-full max-w-2xl rounded-lg bg-white p-6 dark:bg-slate-800">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Create Post</h2>
        <button @click="$emit('close')" class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
          <X class="h-6 w-6" />
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit">
        <!-- Content -->
        <div class="mb-4">
          <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-white">
            Content
          </label>
          <textarea
            v-model="form.content"
            rows="4"
            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            placeholder="What's on your mind?"
            required
          ></textarea>
        </div>

        <!-- Satellite Content -->
        <div class="mb-4">
          <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-white">
            Satellite Content (Optional)
          </label>
          <textarea
            v-model="form.satelliteContent"
            rows="2"
            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            placeholder="Shorter version for satellite devices"
          ></textarea>
        </div>

        <!-- Schedule -->
        <div class="mb-6 flex items-center gap-4">
          <label class="flex items-center gap-2">
            <input
              v-model="form.isScheduled"
              type="checkbox"
              class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500"
            />
            <span class="text-sm font-medium text-slate-900 dark:text-white">Schedule post</span>
          </label>

          <input
            v-if="form.isScheduled"
            v-model="form.scheduledFor"
            type="datetime-local"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
          />
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-4 rounded-lg bg-red-50 p-4 text-red-600 dark:bg-red-900/20 dark:text-red-400">
          {{ error }}
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
          <button
            type="button"
            @click="$emit('close')"
            class="flex-1 rounded-lg border border-slate-300 px-6 py-3 font-medium text-slate-900 transition-colors hover:bg-slate-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading || !form.content"
            class="flex-1 rounded-lg bg-cyan-500 px-6 py-3 font-medium text-white transition-colors hover:bg-cyan-400 disabled:opacity-50"
          >
            {{ loading ? 'Creating...' : 'Create Post' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { X } from 'lucide-vue-next'
import { feedApi } from '../../api'

const emit = defineEmits(['close', 'created'])

const form = ref({
  content: '',
  satelliteContent: '',
  isScheduled: false,
  scheduledFor: null,
})

const loading = ref(false)
const error = ref(null)

const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = null

    await feedApi.createPost({
      content: form.value.content,
      satelliteContent: form.value.satelliteContent,
      media: [],
      scheduledFor: form.value.scheduledFor ? new Date(form.value.scheduledFor) : null,
      isScheduled: form.value.isScheduled,
      profileId: null,
    })

    emit('created')
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to create post'
  } finally {
    loading.value = false
  }
}
</script>

