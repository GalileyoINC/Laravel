<template>
  <main class="container py-4">
    <div class="mb-4 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Feed</h1>
      <div class="flex items-center gap-2">
        <button
          @click="showCreatePost = true"
          class="flex items-center gap-2 rounded-lg bg-cyan-500 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-600"
        >
          <Plus class="h-4 w-4" />
          Post
        </button>
        <LogoutButton />
      </div>
    </div>

    <Suspense>
      <template #default>
        <FeedListRest />
      </template>
      <template #fallback>
        <div class="space-y-4">
          <FeedCardSkeleton />
          <FeedCardSkeleton />
          <FeedCardSkeleton />
          <FeedCardSkeleton />
          <FeedCardSkeleton />
        </div>
      </template>
    </Suspense>

    <!-- Create Post Modal -->
    <Teleport to="body">
      <CreatePostModal
        :is-open="showCreatePost"
        @close="showCreatePost = false"
        @created="handlePostCreated"
      />
    </Teleport>
  </main>
</template>

<script setup>
import { ref } from 'vue'
import { Suspense } from 'vue'
import { Plus } from 'lucide-vue-next'
import LogoutButton from '../layout/LogoutButton.vue'
import FeedListRest from '../feed/FeedListRest.vue'
import FeedCardSkeleton from '../feed/FeedCardSkeleton.vue'
import CreatePostModal from '../feed/CreatePostModal.vue'

const showCreatePost = ref(false)

const handlePostCreated = () => {
  showCreatePost.value = false
  // Refresh the feed
  window.location.reload()
}
</script>