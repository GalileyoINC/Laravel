<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-lg bg-white dark:bg-slate-900">
      <div class="flex items-center justify-between border-b border-slate-200 p-4 dark:border-slate-700">
        <h3 class="text-lg font-semibold">Comments</h3>
        <button
          @click="$emit('close')"
          class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          <X class="h-5 w-5" />
        </button>
      </div>
      
      <div class="p-4">
        <div v-if="loading" class="space-y-4">
          <div v-for="i in 3" :key="i" class="animate-pulse">
            <div class="flex gap-3">
              <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
              <div class="flex-1 space-y-2">
                <div class="h-4 w-24 rounded bg-slate-200 dark:bg-slate-700"></div>
                <div class="h-4 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else-if="comments.length === 0" class="text-center text-slate-500">
          No comments yet
        </div>
        
        <div v-else class="space-y-4">
          <div v-for="comment in comments" :key="comment.id" class="flex gap-3">
            <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            <div class="flex-1">
              <div class="flex items-center gap-2">
                <span class="text-sm font-medium">{{ comment.user?.name || 'Anonymous' }}</span>
                <span class="text-xs text-slate-500">{{ formatDate(comment.created_at) }}</span>
              </div>
              <p class="mt-1 text-sm">{{ comment.content }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { X } from 'lucide-vue-next'
import { commentApi } from '../../api'

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

const loading = ref(false)
const comments = ref([])

const loadComments = async () => {
  if (!props.post?.id) return
  
  loading.value = true
  try {
    const response = await commentApi.getCommentsForNews(props.post.id)
    if (response.data.status === 'success') {
      comments.value = response.data.data.comments || []
    }
  } catch (error) {
    console.error('Failed to load comments:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    loadComments()
  }
})

onMounted(() => {
  if (props.isOpen) {
    loadComments()
  }
})
</script>
