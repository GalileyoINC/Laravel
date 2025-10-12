<template>
  <div class="">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
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

      <Suspense>
        <template #default>
          <BookmarksList />
        </template>
        <template #fallback>
          <FeedCardSkeleton />
        </template>
      </Suspense>
    </div>
  </div>
</template>

<script setup>
import { Suspense } from 'vue'
import { Bookmark } from 'lucide-vue-next'
import BookmarksList from '../feed/BookmarksList.vue'
import FeedCardSkeleton from '../feed/FeedCardSkeleton.vue'
</script>