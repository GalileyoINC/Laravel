<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-slate-900">
      <h3 class="mb-4 text-lg font-semibold">Report Post</h3>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            Reason for reporting
          </label>
          <select
            v-model="selectedReason"
            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
          >
            <option value="">Select a reason</option>
            <option value="spam">Spam</option>
            <option value="harassment">Harassment</option>
            <option value="inappropriate">Inappropriate content</option>
            <option value="misinformation">Misinformation</option>
            <option value="other">Other</option>
          </select>
        </div>
        
        <div v-if="selectedReason === 'other'">
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            Additional details
          </label>
          <textarea
            v-model="additionalDetails"
            rows="3"
            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
            placeholder="Please provide more details..."
          ></textarea>
        </div>
      </div>
      
      <div class="mt-6 flex gap-3">
        <button
          @click="handleCancel"
          class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
        >
          Cancel
        </button>
        <button
          @click="handleSubmit"
          :disabled="!selectedReason || submitting"
          class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 disabled:opacity-50"
        >
          {{ submitting ? 'Reporting...' : 'Report' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { feedApi } from '../../api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  post: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close'])

const selectedReason = ref('')
const additionalDetails = ref('')
const submitting = ref(false)

const handleCancel = () => {
  selectedReason.value = ''
  additionalDetails.value = ''
  emit('close')
}

const handleSubmit = async () => {
  if (!selectedReason.value || !props.post?.id) return
  
  submitting.value = true
  try {
    await feedApi.reportPost(props.post.id, selectedReason.value, additionalDetails.value)
    alert('Post reported successfully')
    handleCancel()
  } catch (error) {
    console.error('Failed to report post:', error)
    alert('Failed to report post. Please try again.')
  } finally {
    submitting.value = false
  }
}
</script>
