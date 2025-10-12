<template>
  <div class="space-y-4">
    <div v-if="loading" class="space-y-4">
      <FeedCardSkeleton />
      <FeedCardSkeleton />
      <FeedCardSkeleton />
    </div>

    <div v-else-if="error" class="text-center text-red-500">
      Error: {{ error }}
      <button 
        @click="fetchBookmarks"
        class="ml-2 rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
      >
        Retry
      </button>
    </div>

    <div v-else-if="bookmarks.length === 0" class="text-center text-slate-500">
      No bookmarks found
    </div>

    <div v-else class="space-y-4">
      <FeedCard
        v-for="bookmark in bookmarks"
        :key="getUniqueId(bookmark.post)"
        :item="bookmark.post"
        :get-query-keys="getQueryKeys"
        :get-query-keys-on-error="getQueryKeysOnError"
        @open-comments-modal="handleOpenCommentsModal"
      />
      
      <div class="grid w-full grid-cols-1 gap-4">
        <button
          ref="loadMoreRef"
          @click="loadMore"
          :disabled="loadingMore || !hasMore"
          class="p-4 text-center text-gray-500"
        >
          {{ loadingMore ? "Loading..." : hasMore ? "Load More" : "No more bookmarks" }}
        </button>
      </div>
    </div>

    <!-- Comments Modal -->
    <CommentsModal
      v-if="post"
      :is-open="isOpen"
      @close="() => setIsOpen(false)"
      :post="post"
    />

    <!-- Report Modal -->
    <ReportModal />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { bookmarkApi } from '../../api'
import FeedCard from './FeedCard.vue'
import FeedCardSkeleton from './FeedCardSkeleton.vue'
import CommentsModal from './CommentsModal.vue'
import ReportModal from './ReportModal.vue'

const bookmarks = ref([])
const loading = ref(false)
const loadingMore = ref(false)
const error = ref(null)
const currentPage = ref(1)
const hasMore = ref(true)
const isOpen = ref(false)
const post = ref(null)
const loadMoreRef = ref(null)

const fetchBookmarks = async (page = 1) => {
  try {
    if (page === 1) {
      loading.value = true
    } else {
      loadingMore.value = true
    }
    error.value = null

    const response = await bookmarkApi.list({
      page,
      page_size: 10
    })

    if (response.data.status === 'success') {
      if (page === 1) {
        bookmarks.value = response.data.data.list || []
      } else {
        bookmarks.value.push(...(response.data.data.list || []))
      }
      
      currentPage.value = page
      hasMore.value = (response.data.data.list || []).length === 10
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load bookmarks'
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

const loadMore = () => {
  if (hasMore.value && !loadingMore.value) {
    fetchBookmarks(currentPage.value + 1)
  }
}

const handleOpenCommentsModal = (postItem) => {
  post.value = postItem
  isOpen.value = true
}

const getQueryKeys = () => {
  return ['bookmark', 'list']
}

const getQueryKeysOnError = () => {
  return ['bookmark', 'list', 'error']
}

const getUniqueId = (item) => {
  return item.id || `${item.type}-${item.created_at}`
}

onMounted(() => {
  fetchBookmarks(1)
})
</script>
