<template>
  <div class="min-h-screen bg-background dark:bg-slate-900">
    <div class="container py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="mb-6 flex items-center gap-4">
          <div class="rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 p-3">
            <Bookmark class="h-7 w-7 text-white" />
          </div>
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
              Bookmarks
            </h1>
            <p class="text-slate-600 dark:text-slate-400">
              You can view and manage your bookmarks here.
            </p>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="space-y-4">
        <div v-for="i in 3" :key="i" class="animate-pulse">
          <div class="h-48 rounded-lg bg-slate-200 dark:bg-slate-800"></div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="rounded-lg bg-red-50 p-6 text-center text-red-600 dark:bg-red-900/20 dark:text-red-400">
        <p>{{ error }}</p>
        <button @click="loadBookmarks" class="mt-4 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">
          Try Again
        </button>
      </div>

      <!-- Bookmarks List -->
      <div v-else class="space-y-6">
        <div v-if="bookmarks.length === 0" class="rounded-lg bg-slate-50 p-12 text-center dark:bg-slate-800">
          <Bookmark class="mx-auto h-12 w-12 text-slate-400" />
          <p class="mt-4 text-slate-600 dark:text-slate-400">No bookmarks yet. Start bookmarking posts to see them here!</p>
        </div>

        <FeedCard 
          v-for="bookmark in bookmarks" 
          :key="bookmark.id" 
          :item="bookmark.post"
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

        <div v-if="!hasMore && bookmarks.length > 0" class="text-center text-slate-600 dark:text-slate-400">
          You've reached the end
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Bookmark } from 'lucide-vue-next'
import { bookmarkApi, feedApi } from '../../api'
import FeedCard from '../feed/FeedCard.vue'

const loading = ref(false)
const loadingMore = ref(false)
const error = ref(null)
const bookmarks = ref([])
const currentPage = ref(1)
const hasMore = ref(true)

const loadBookmarks = async (page = 1) => {
  try {
    if (page === 1) {
      loading.value = true
    } else {
      loadingMore.value = true
    }
    error.value = null

    const response = await bookmarkApi.list({ page, page_size: 10 })

    if (response.data.status === 'success') {
      if (page === 1) {
        bookmarks.value = response.data.data.list
      } else {
        bookmarks.value.push(...response.data.data.list)
      }
      
      currentPage.value = page
      hasMore.value = response.data.data.list.length === 10
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load bookmarks'
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

const loadMore = () => {
  loadBookmarks(currentPage.value + 1)
}

const handleReaction = async ({ id, reaction, isActive }) => {
  try {
    if (isActive) {
      await feedApi.removeReaction(id, reaction)
    } else {
      await feedApi.setReaction(id, reaction)
    }
    await loadBookmarks(1)
  } catch (err) {
    console.error('Failed to update reaction:', err)
  }
}

const handleComment = (item) => {
  window.location.href = `/post/${item.id}`
}

const handleBookmark = async (item) => {
  try {
    await bookmarkApi.delete(item.id)
    // Reload bookmarks
    await loadBookmarks(1)
  } catch (err) {
    console.error('Failed to remove bookmark:', err)
  }
}

const handleReport = async (item) => {
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

onMounted(() => {
  loadBookmarks(1)
})
</script>
