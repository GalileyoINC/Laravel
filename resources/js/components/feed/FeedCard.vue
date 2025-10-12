<template>
  <div class="transform border-slate-200 bg-white/50 transition-all duration-300 hover:scale-[1.01] hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-slate-600 rounded-lg border p-6">
    <!-- Header -->
    <div class="flex items-start justify-between">
      <div class="flex items-start gap-3">
        <UserAvatar
          :name="item.title"
          :image="getUserAvatarIcon(item)"
          :is-verified="false"
          :is-influencer="isInfluencer"
          :href="profileLink"
        >
          <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <span>{{ item.created_at }}</span>
            <span v-if="item.location">•</span>
            <div v-if="item.location" class="flex items-center gap-1">
              <MapPin class="h-3 w-3" />
              <span>{{ item.location }}</span>
            </div>
          </div>
        </UserAvatar>
      </div>

      <div v-if="!item.is_owner" class="relative">
        <button
          @click="showDropdown = !showDropdown"
          class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          <MoreHorizontal class="h-5 w-5" />
        </button>
        
        <div v-if="showDropdown" class="absolute right-0 top-8 z-10 w-48 rounded-lg border bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800">
          <button
            @click="handleSubscription"
            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
          >
            {{ item.is_subscribed ? "Unfollow" : "Follow" }}
          </button>
          <button
            @click="handleMute"
            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-100 dark:hover:bg-slate-700"
          >
            Mute
          </button>
          <hr class="my-1 border-slate-200 dark:border-slate-700" />
          <button
            @click="handleReport"
            class="w-full px-4 py-2 text-left text-sm text-red-500 hover:bg-slate-100 dark:hover:bg-slate-700"
          >
            Report Post
          </button>
        </div>
      </div>
    </div>

    <!-- Post Type Badge -->
    <div v-if="getPostTypeIcon(item.type, item.emergency_level)" class="mt-2">
      <component :is="getPostTypeIcon(item.type, item.emergency_level)" />
    </div>

    <!-- Post Content -->
    <div v-if="item.type !== 'financial'" class="mt-4">
      <p class="leading-relaxed">{{ item.body }}</p>
    </div>

    <!-- Post Image -->
    <div v-if="item.images && item.images.length > 0" class="mx-auto mt-4 max-w-md overflow-hidden rounded-lg">
      <img
        :src="item.images[0]?.sizes?.[0]?.url || item.images[0]"
        alt="Post content"
        class="h-auto w-full object-cover"
      />
    </div>

    <!-- Financial Content -->
    <div v-if="item.type === 'financial'" class="mt-4">
      <div class="text-2xl font-bold text-slate-900 dark:text-white">
        {{ formatPrice(item.price) }}
      </div>
      <div
        :class="[
          'mt-1 flex items-center gap-1 text-sm font-medium',
          parseFloat(item.percent || '0') >= 0 ? 'text-green-400' : 'text-red-400'
        ]"
      >
        <TrendingUp v-if="parseFloat(item.percent || '0') >= 0" class="h-4 w-4" />
        <TrendingDown v-else class="h-4 w-4" />
        <span>
          {{ parseFloat(item.percent || '0') >= 0 ? '+' : '' }}
          {{ parseFloat(item.percent || '0').toFixed(2) }}
        </span>
        <span>
          ({{ parseFloat(item.percent || '0') >= 0 ? '+' : '' }}
          {{ parseFloat(item.percent || '0').toFixed(2) }}%)
        </span>
      </div>
    </div>

    <!-- Post Actions -->
    <div v-if="hasActions" class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-700">
      <div class="grid grid-cols-3 items-baseline justify-between">
        <div class="col-span-3 flex">
          <div v-if="item.reactions && item.reactions.length > 0" class="mt-1 flex items-center gap-1">
            <div
              v-for="reaction in item.reactions"
              :key="reaction.id"
              class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400"
            >
              <span>{{ getReactionEmoji(reaction.id) }}</span>
              <span>{{ formatNumber(reaction.cnt) }}</span>
            </div>
          </div>
        </div>
        
        <div class="col-span-2 flex items-center gap-6">
          <!-- Reaction Button -->
          <button
            @click="handleReactionClick"
            class="flex items-center gap-2 text-slate-500 transition-colors dark:text-slate-400"
          >
            <Heart v-if="!userReaction" class="h-5 w-5" />
            <div v-else class="flex max-h-5 max-w-5 items-center gap-1 text-xl">
              {{ getReactionEmoji(userReaction.id) }}
            </div>
            <div class="text-sm font-medium">
              {{ formatNumber(getTotalReactions()) }}
            </div>
          </button>

          <!-- Comments Button -->
          <button
            @click="handleOpenCommentsModal"
            class="flex items-center gap-2 text-slate-500 transition-colors dark:text-slate-400"
          >
            <MessageCircle class="h-5 w-5" />
            <span class="text-sm font-medium">
              {{ formatNumber(item.comment_quantity || 0) }}
            </span>
          </button>

          <!-- Share Button -->
          <button class="flex items-center gap-2 text-slate-500 transition-colors dark:text-slate-400">
            <Share class="h-5 w-5" />
            <span class="text-sm font-medium">{{ formatNumber(0) }}</span>
          </button>
        </div>

        <div class="flex items-end justify-end gap-2">
          <!-- Bookmark Button -->
          <button
            v-if="item.id"
            @click="handleBookmark"
            :class="[
              'p-2 transition-colors',
              item.is_bookmarked
                ? 'text-yellow-400 hover:text-yellow-300'
                : 'text-slate-500 hover:text-yellow-400 dark:text-slate-400'
            ]"
          >
            <Bookmark :class="['h-5 w-5', item.is_bookmarked ? 'fill-current' : '']" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  AlertTriangle,
  Bookmark,
  Heart,
  MapPin,
  MessageCircle,
  MoreHorizontal,
  Satellite,
  Share,
  TrendingDown,
  TrendingUp,
} from 'lucide-vue-next'
import UserAvatar from './UserAvatar.vue'
import { feedApi, bookmarkApi } from '../../api'

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  getQueryKeys: {
    type: Function,
    default: () => []
  },
  getQueryKeysOnError: {
    type: Function,
    default: () => []
  }
})

const emit = defineEmits(['openCommentsModal'])

const showDropdown = ref(false)

const reactionOptions = [
  { type: "like", emoji: "👍", label: "Like", id: "1" },
  { type: "dislike", emoji: "👎", label: "Dislike", id: "2" },
  { type: "laugh", emoji: "🤣", label: "Laugh", id: "3" },
  { type: "love", emoji: "❤️", label: "Love", id: "4" },
  { type: "fire", emoji: "🔥", label: "Fire", id: "5" },
  { type: "disgust", emoji: "🤢", label: "Disgust", id: "6" },
]

const isInfluencer = computed(() => props.item.type === "influencer")

const hasActions = computed(() => {
  return props.item.type !== "financial" && props.item.type !== "not_sended_yet"
})

const userReaction = computed(() => {
  return props.item.reactions?.find((reaction) => reaction.selected)
})

const profileLink = computed(() => {
  if (props.item.type === "financial") {
    return undefined
  }

  if (props.item.id_follower_list) {
    return `/profile/by-follower-list/${props.item.id_follower_list}`
  }

  if (props.item.id_subscription) {
    return `/profile/by-subscription/${props.item.id_subscription}`
  }

  return undefined
})

const formatPrice = (price) => {
  const priceNumber = typeof price === "string" ? parseFloat(price) : (price ?? 0)
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(priceNumber)
}

const formatNumber = (num) => {
  if (num === null || num === undefined) {
    return "0"
  }

  if (num >= 1000) {
    return (num / 1000).toFixed(1) + "k"
  }

  return num.toString()
}

const getReactionEmoji = (reactionId) => {
  return reactionOptions.find((r) => r.id === reactionId)?.emoji || "❤️"
}

const getTotalReactions = () => {
  return props.item.reactions?.reduce((acc, reaction) => acc + reaction.cnt, 0) || 0
}

const getUserAvatarIcon = (item) => {
  switch (item.type) {
    case "influencer":
      return item.image ?? null
    case "financial":
      return null
    default:
      return null
  }
}

const getPostTypeIcon = (type, emergencyLevel) => {
  switch (type) {
    case "emergency":
      return {
        component: 'div',
        props: {
          class: `flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium ${
            emergencyLevel === "critical"
              ? "bg-red-500/20 text-red-400"
              : emergencyLevel === "high"
                ? "bg-orange-500/20 text-orange-400"
                : emergencyLevel === "medium"
                  ? "bg-yellow-500/20 text-yellow-400"
                  : "bg-blue-500/20 text-blue-400"
          }`
        },
        children: [
          { component: AlertTriangle, props: { class: "h-3 w-3" } },
          "Emergency Alert"
        ]
      }
    case "satellite_update":
      return {
        component: 'div',
        props: {
          class: "flex items-center gap-1 rounded-full bg-cyan-500/20 px-2 py-1 text-xs font-medium text-cyan-400"
        },
        children: [
          { component: Satellite, props: { class: "h-3 w-3" } },
          "Network Update"
        ]
      }
    default:
      return null
  }
}

const handleSubscription = async () => {
  try {
    await feedApi.setSubscription(props.item.id, !props.item.is_subscribed)
    // Update local state or refetch
  } catch (error) {
    console.error('Failed to update subscription:', error)
  }
}

const handleMute = async () => {
  try {
    if (props.item.id_subscription) {
      await feedApi.muteSubscription(props.item.id_subscription)
    }
  } catch (error) {
    console.error('Failed to mute subscription:', error)
  }
}

const handleReport = () => {
  // Show report modal - implement this
  console.log('Report post:', props.item.id)
}

const handleReactionClick = async () => {
  try {
    const selectedReaction = userReaction.value?.id
      ? reactionOptions.find((r) => r.id === userReaction.value.id)
      : null

    if (selectedReaction?.type) {
      await feedApi.removeReaction(props.item.id, selectedReaction.id)
    } else {
      await feedApi.setReaction(props.item.id, "4") // Default to love
    }
  } catch (error) {
    console.error('Failed to update reaction:', error)
  }
}

const handleOpenCommentsModal = () => {
  emit('openCommentsModal', props.item)
}

const handleBookmark = async () => {
  try {
    if (props.item.is_bookmarked) {
      await bookmarkApi.delete(props.item.id)
    } else {
      await bookmarkApi.create(props.item.id)
    }
  } catch (error) {
    console.error('Failed to update bookmark:', error)
  }
}
</script>