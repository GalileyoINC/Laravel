<template>
  <div class="flex items-center gap-2">
    <component :is="NavigationComponent" :href="href">
      <div class="relative">
        <div
          :class="[
            'rounded-full bg-slate-200 text-slate-900 dark:bg-slate-700 dark:text-white flex items-center justify-center',
            size === 'small' ? 'h-8 w-8' : size === 'large' ? 'h-16 w-16' : 'h-12 w-12'
          ]"
        >
          <img
            v-if="image"
            :src="image"
            :alt="name"
            class="h-full w-full rounded-full object-cover"
          />
          <span v-else class="select-none text-sm font-medium">
            {{ name
              .split(" ")
              .map((n) => n[0])
              .join("")
              .toUpperCase()
              .slice(0, 2) }}
          </span>
        </div>
        <span v-if="isVerified" class="absolute right-0 top-0">
          <span class="sr-only">Verified</span>
          <div class="h-4 w-4 rounded-full bg-cyan-500 text-white flex items-center justify-center">
            <span class="text-xs">✓</span>
          </div>
        </span>
      </div>
    </component>
    
    <div v-if="!onlyAvatar" class="flex-1">
      <div class="mb-1 flex items-center gap-2">
        <component :is="NavigationComponent" :href="href">
          <h3 class="font-semibold text-slate-900 dark:text-white">
            {{ name }}
          </h3>
        </component>
        <span v-if="isInfluencer" class="rounded bg-purple-500/20 px-2 py-0.5 text-xs font-medium text-purple-400">
          Influencer
        </span>
      </div>
      <slot />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  image: {
    type: String,
    default: null
  },
  isVerified: {
    type: Boolean,
    default: false
  },
  isInfluencer: {
    type: Boolean,
    default: false
  },
  href: {
    type: String,
    default: undefined
  },
  onlyAvatar: {
    type: Boolean,
    default: false
  },
  size: {
    type: String,
    default: 'medium',
    validator: (value) => ['small', 'medium', 'large'].includes(value)
  }
})

const NavigationComponent = computed(() => {
  if (props.href && props.href !== "" && !props.href.includes("undefined") && !props.href.includes("null")) {
    return RouterLink
  }
  return 'div'
})
</script>
