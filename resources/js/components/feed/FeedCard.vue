<template>
  <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
    <!-- Author Info -->
    <div class="mb-4 flex items-center gap-3">
      <img
        :src="item.author?.photo || '/default-avatar.png'"
        :alt="item.author?.name"
        class="h-12 w-12 rounded-full object-cover"
      />
      <div class="flex-1">
        <h3 class="font-semibold text-slate-900 dark:text-white">
          {{ item.author?.name || 'Unknown' }}
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-400">
          {{ formatDate(item.created_at) }}
        </p>
      </div>
      <button class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
        <MoreVertical class="h-5 w-5" />
      </button>
    </div>

    <!-- Content -->
    <div class="mb-4">
      <p class="text-slate-900 dark:text-white whitespace-pre-wrap">{{ item.text }}</p>
      
      <!-- Media -->
      <div v-if="item.media && item.media.length" class="mt-4 grid gap-2" :class="getMediaGridClass(item.media.length)">
        <img
          v-for="(media, index) in item.media"
          :key="index"
          :src="media.url"
          :alt="`Media ${index + 1}`"
          class="rounded-lg object-cover w-full h-64"
        />
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-6 border-t border-slate-200 pt-4 dark:border-slate-700">
      <button
        @click="$emit('reaction', { id: item.id, reaction: '1', isActive: item.user_reaction === '1' })"
        :class="[
          'flex items-center gap-2 text-sm transition-colors',
          item.user_reaction === '1'
            ? 'text-cyan-500'
            : 'text-slate-600 hover:text-cyan-500 dark:text-slate-400'
        ]"
      >
        <ThumbsUp class="h-5 w-5" :class="{ 'fill-current': item.user_reaction === '1' }" />
        <span>{{ item.reactions_count || 0 }}</span>
      </button>

      <button
        @click="$emit('comment', item)"
        class="flex items-center gap-2 text-sm text-slate-600 transition-colors hover:text-cyan-500 dark:text-slate-400"
      >
        <MessageCircle class="h-5 w-5" />
        <span>{{ item.comments_count || 0 }}</span>
      </button>

      <button
        @click="$emit('bookmark', item)"
        :class="[
          'flex items-center gap-2 text-sm transition-colors',
          item.is_bookmarked
            ? 'text-cyan-500'
            : 'text-slate-600 hover:text-cyan-500 dark:text-slate-400'
        ]"
      >
        <Bookmark class="h-5 w-5" :class="{ 'fill-current': item.is_bookmarked }" />
      </button>

      <button
        @click="$emit('report', item)"
        class="ml-auto flex items-center gap-2 text-sm text-slate-600 transition-colors hover:text-red-500 dark:text-slate-400"
      >
        <Flag class="h-5 w-5" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ThumbsUp, MessageCircle, Bookmark, Flag, MoreVertical } from 'lucide-vue-next'

defineProps({
  item: {
    type: Object,
    required: true,
  },
})

defineEmits(['reaction', 'comment', 'bookmark', 'report'])

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = Math.floor((now - date) / 1000) // difference in seconds

  if (diff < 60) return 'Just now'
  if (diff < 3600) return `${Math.floor(diff / 60)}m ago`
  if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`
  if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`

  return date.toLocaleDateString()
}

const getMediaGridClass = (count) => {
  if (count === 1) return 'grid-cols-1'
  if (count === 2) return 'grid-cols-2'
  return 'grid-cols-2 md:grid-cols-3'
}
</script>

