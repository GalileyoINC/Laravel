<template>
  <div>
    <!-- Tabs -->
    <Tabs
      v-model="activeTab"
      @update:model-value="handleTabChange"
      class="mb-4 w-full"
    >
      <TabsList class="grid w-full grid-cols-2 rounded-xl border border-slate-200 bg-white/50 p-1 dark:border-slate-700 dark:bg-slate-800/50">
        <TabsTrigger
          value="subscriptions"
          class="rounded-lg font-medium transition-all duration-200 data-[state=active]:bg-gradient-to-r data-[state=active]:from-cyan-500 data-[state=active]:to-blue-500 data-[state=active]:text-white data-[state=active]:shadow-lg data-[state=active]:shadow-cyan-500/25"
        >
          <Users class="mr-2 h-4 w-4" />
          Subscriptions
        </TabsTrigger>
        <TabsTrigger
          value="discover"
          class="rounded-lg font-medium transition-all duration-200 data-[state=active]:bg-gradient-to-r data-[state=active]:from-purple-500 data-[state=active]:to-pink-500 data-[state=active]:text-white data-[state=active]:shadow-lg data-[state=active]:shadow-purple-500/25"
        >
          <Sparkles class="mr-2 h-4 w-4" />
          Discover
        </TabsTrigger>
      </TabsList>
    </Tabs>

    <!-- Feed Content -->
    <div class="space-y-4">
      <div v-if="loading" class="space-y-4">
        <FeedCardSkeleton />
        <FeedCardSkeleton />
        <FeedCardSkeleton />
      </div>

      <div v-else-if="error" class="text-center text-red-500">
        Error: {{ error }}
        <button 
          @click="fetchNews"
          class="ml-2 rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
        >
          Retry
        </button>
      </div>

      <div v-else-if="newsData?.data?.list" class="space-y-4">
        <FeedCard
          v-for="item in newsData.data.list"
          :key="getUniqueId(item)"
          :item="item"
          :get-query-keys="getQueryKeys"
          :get-query-keys-on-error="getQueryKeysOnError"
          @open-comments-modal="handleOpenCommentsModal"
        />
        
        <div class="grid w-full grid-cols-1 gap-4">
          <button
            ref="loadMoreRef"
            @click="fetchNews"
            :disabled="loading"
            class="p-4 text-center text-gray-500"
          >
            {{ loading ? "Loading..." : "Load More" }}
          </button>
        </div>
      </div>

      <div v-else class="text-center text-gray-500">
        No news items found
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
import { useRouter } from 'vue-router'
import { Sparkles, Users } from 'lucide-vue-next'
import { Tabs, TabsList, TabsTrigger } from '../ui'
import { feedApi } from '../../api'
import FeedCard from './FeedCard.vue'
import FeedCardSkeleton from './FeedCardSkeleton.vue'
import CommentsModal from './CommentsModal.vue'
import ReportModal from './ReportModal.vue'

const router = useRouter()
const activeTab = ref('subscriptions')
const isOpen = ref(false)
const post = ref(null)
const isAuthenticated = ref(false)
const newsData = ref(null)
const loading = ref(false)
const error = ref(null)
const loadMoreRef = ref(null)

// Check if user is authenticated
onMounted(() => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      router.push('/login')
      return
    }
    isAuthenticated.value = true
    fetchNews()
  }
})

// Fetch news data
const fetchNews = async () => {
  if (!isAuthenticated.value) return
  
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('auth_token')
    let url = `${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/news/last`
    if (activeTab.value === "discover") {
      url = `${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/news/by-influencers`
    }

    const response = await fetch(url, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        page: 1,
        page_size: 10,
      }),
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    newsData.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to fetch news'
  } finally {
    loading.value = false
  }
}

watch(() => activeTab.value, () => {
  fetchNews()
})

const handleTabChange = (value) => {
  activeTab.value = value
}

const handleOpenCommentsModal = (postItem) => {
  post.value = postItem
  isOpen.value = true
}

const getQueryKeys = () => {
  return ['feed', 'latest-news', activeTab.value]
}

const getQueryKeysOnError = () => {
  return ['feed', 'latest-news', activeTab.value, 'error']
}

const getUniqueId = (item) => {
  return item.id || `${item.type}-${item.created_at}`
}
</script>
