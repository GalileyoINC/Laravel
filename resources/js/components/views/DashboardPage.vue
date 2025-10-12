<template>
  <div class="min-h-screen bg-background dark:bg-slate-900">
    <div class="container py-8">
      <!-- Header -->
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Feed</h1>
          <p class="text-slate-600 dark:text-slate-400">Stay up to date with the latest news and updates</p>
        </div>
        <button 
          @click="showCreatePost = true"
          class="flex items-center gap-2 rounded-lg bg-cyan-500 px-6 py-3 font-semibold text-white transition-colors hover:bg-cyan-400"
        >
          <Plus class="h-5 w-5" />
          Create Post
        </button>
      </div>

      <!-- Tabs -->
      <div class="mb-6 flex gap-4 border-b border-slate-200 dark:border-slate-700">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            'px-4 py-2 text-sm font-medium transition-colors',
            activeTab === tab.value
              ? 'border-b-2 border-cyan-500 text-cyan-500'
              : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Feed List -->
      <div v-if="loading" class="space-y-4">
        <div v-for="i in 3" :key="i" class="animate-pulse">
          <div class="h-48 rounded-lg bg-slate-200 dark:bg-slate-800"></div>
        </div>
      </div>

      <div v-else-if="error" class="rounded-lg bg-red-50 p-6 text-center text-red-600 dark:bg-red-900/20 dark:text-red-400">
        <p>{{ error }}</p>
        <button @click="loadFeed" class="mt-4 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">
          Try Again
        </button>
      </div>

      <div v-else class="space-y-6">
        <FeedCard 
          v-for="item in feedItems" 
          :key="item.id" 
          :item="item"
          @reaction="handleReaction"
          @comment="handleComment"
          @bookmark="handleBookmark"
          @report="handleReport"
        />

        <!-- Load More -->
        <div v-if="hasMore" class="text-center">
          <button
            @click="loadMore"
            :disabled="loadingMore"
            class="rounded-lg bg-slate-200 px-6 py-3 font-medium text-slate-900 transition-colors hover:bg-slate-300 disabled:opacity-50 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
          >
            {{ loadingMore ? 'Loading...' : 'Load More' }}
          </button>
        </div>

        <div v-if="!hasMore && feedItems.length > 0" class="text-center text-slate-600 dark:text-slate-400">
          You've reached the end
        </div>

        <div v-if="feedItems.length === 0" class="rounded-lg bg-slate-50 p-12 text-center dark:bg-slate-800">
          <p class="text-slate-600 dark:text-slate-400">No posts yet. Be the first to create one!</p>
        </div>
      </div>
    </div>

    <!-- Create Post Modal -->
    <CreatePostModal 
      v-if="showCreatePost" 
      @close="showCreatePost = false"
      @created="handlePostCreated"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Plus } from 'lucide-vue-next'
import { feedApi } from '../../api'
import FeedCard from '../feed/FeedCard.vue'
import CreatePostModal from '../feed/CreatePostModal.vue'

const activeTab = ref('subscriptions')
const loading = ref(false)
const loadingMore = ref(false)
const error = ref(null)
const feedItems = ref([])
const currentPage = ref(1)
const hasMore = ref(true)
const showCreatePost = ref(false)

const tabs = [
  { label: 'Subscriptions', value: 'subscriptions' },
  { label: 'Discover', value: 'discover' },
]

const loadFeed = async (page = 1) => {
  try {
    if (page === 1) {
      loading.value = true
    } else {
      loadingMore.value = true
    }
    error.value = null

    const response = await (activeTab.value === 'subscriptions' 
      ? feedApi.getLatestNews({ page, page_size: 10 })
      : feedApi.getNewsByInfluencers({ page, page_size: 10 }))

    if (response.data.status === 'success') {
      if (page === 1) {
        feedItems.value = response.data.data.list
      } else {
        feedItems.value.push(...response.data.data.list)
      }
      
      currentPage.value = page
      hasMore.value = response.data.data.list.length === 10
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load feed'
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

const loadMore = () => {
  loadFeed(currentPage.value + 1)
}

const handleReaction = async ({ id, reaction, isActive }) => {
  try {
    if (isActive) {
      await feedApi.removeReaction(id, reaction)
    } else {
      await feedApi.setReaction(id, reaction)
    }
    // Reload the current item
    await loadFeed(1)
  } catch (err) {
    console.error('Failed to update reaction:', err)
  }
}

const handleComment = (item) => {
  // Navigate to post detail page
  window.location.href = `/post/${item.id}`
}

const handleBookmark = async (item) => {
  try {
    const { bookmarkApi } = await import('../../api')
    if (item.is_bookmarked) {
      await bookmarkApi.delete(item.id)
    } else {
      await bookmarkApi.create(item.id)
    }
    // Reload the current item
    await loadFeed(1)
  } catch (err) {
    console.error('Failed to update bookmark:', err)
  }
}

const handleReport = async (item) => {
  // Show report modal
  const reason = prompt('Enter reason for reporting:')
  if (reason) {
    try {
      await feedApi.reportPost(item.id, reason)
      alert('Post reported successfully')
    } catch (err) {
      console.error('Failed to report post:', err)
    }
  }
}

const handlePostCreated = () => {
  showCreatePost.value = false
  loadFeed(1)
}

watch(activeTab, () => {
  feedItems.value = []
  currentPage.value = 1
  hasMore.value = true
  loadFeed(1)
})

onMounted(() => {
  loadFeed(1)
})
</script>
