<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 p-4 pt-20">
    <div class="max-h-[80vh] max-w-2xl w-full overflow-y-auto rounded-lg bg-white dark:bg-slate-900 shadow-xl">
      <!-- Header -->
      <div class="flex items-center gap-2 border-b border-slate-200 p-6 dark:border-slate-700">
        <CalendarDays class="h-5 w-5" />
        <h2 class="text-lg font-semibold">Create New Post</h2>
        <button
          @click="handleClose"
          class="ml-auto rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          <X class="h-5 w-5" />
        </button>
      </div>

      <div class="p-6 space-y-6">
        <!-- Profile Display -->
        <div v-if="isAdmin" class="space-y-3">
          <label class="text-sm font-medium text-slate-600 dark:text-slate-400">
            Posting as:
          </label>
          <div class="flex items-center gap-3 rounded-lg border bg-slate-50 p-3 dark:bg-slate-800">
            <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
              <span class="text-sm font-medium">{{ userProfile?.first_name?.charAt(0) || 'U' }}</span>
            </div>
            <div>
              <div class="text-sm font-medium">{{ userProfile?.first_name }} {{ userProfile?.last_name }}</div>
              <div class="text-xs text-slate-500">{{ userProfile?.email }}</div>
            </div>
          </div>
        </div>

        <!-- Content Input -->
        <div class="space-y-2">
          <label for="post-content" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            What's on your mind?
          </label>

          <!-- Emoji Selector -->
          <div class="space-y-2">
            <div class="flex items-center gap-2 rounded-lg border bg-slate-50 p-2 dark:bg-slate-800">
              <Smile class="h-4 w-4 text-slate-500" />
              <span class="text-sm text-slate-500">Add emoji:</span>

              <!-- Quick Emoji Picker -->
              <div class="flex flex-wrap gap-1">
                <button
                  v-for="emoji in recentEmojis"
                  :key="emoji"
                  @click="addEmoji(emoji)"
                  class="rounded p-1 text-lg transition-colors hover:bg-slate-200 dark:hover:bg-slate-700"
                  :title="`Add ${emoji}`"
                >
                  {{ emoji }}
                </button>
              </div>

              <!-- More Emojis Button -->
              <button
                @click="showEmojiPicker = !showEmojiPicker"
                class="ml-auto text-sm text-cyan-600 hover:underline"
              >
                More emojis
              </button>
            </div>

            <!-- Full Emoji Picker -->
            <div v-if="showEmojiPicker" class="rounded-lg border bg-white p-3 dark:bg-slate-800">
              <div class="space-y-3">
                <!-- Category Tabs -->
                <div class="flex gap-1 overflow-x-auto pb-2">
                  <button
                    v-for="category in Object.keys(emojiCategories)"
                    :key="category"
                    @click="activeEmojiCategory = category"
                    :class="[
                      'flex-shrink-0 whitespace-nowrap rounded-full px-3 py-1 text-xs transition-colors',
                      activeEmojiCategory === category
                        ? 'bg-cyan-500 text-white'
                        : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600'
                    ]"
                  >
                    {{ category.charAt(0).toUpperCase() + category.slice(1) }}
                  </button>
                </div>

                <!-- Emoji Grid -->
                <div class="grid max-h-64 grid-cols-8 gap-1 overflow-y-auto">
                  <button
                    v-for="emoji in emojiCategories[activeEmojiCategory]"
                    :key="emoji"
                    @click="addEmoji(emoji)"
                    class="rounded p-1 text-lg transition-colors hover:bg-slate-100 dark:hover:bg-slate-700"
                    :title="`Add ${emoji}`"
                  >
                    {{ emoji }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <textarea
            id="post-content"
            v-model="content"
            placeholder="Share your thoughts, news, or updates..."
            class="min-h-[120px] w-full resize-none rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
            maxlength="512"
          />
          <div class="text-right text-sm text-slate-500">
            {{ content.length }}/512
          </div>
        </div>

        <!-- Satellite Version Switch -->
        <div class="flex items-center justify-between rounded-lg border bg-slate-50 p-3 dark:bg-slate-800">
          <div class="flex items-center gap-2">
            <Satellite class="h-4 w-4 text-slate-500" />
            <label for="satellite-version" class="text-sm font-medium">Satellite Version</label>
            <span class="rounded bg-slate-200 px-2 py-1 text-xs text-slate-600 dark:bg-slate-700 dark:text-slate-400">
              For satellite devices only
            </span>
          </div>
          <div class="flex items-center gap-2">
            <span v-if="useSatelliteVersion" class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-600">
              Enabled
            </span>
            <button
              @click="useSatelliteVersion = !useSatelliteVersion"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                useSatelliteVersion ? 'bg-cyan-500' : 'bg-slate-200 dark:bg-slate-700'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  useSatelliteVersion ? 'translate-x-6' : 'translate-x-1'
                ]"
              />
            </button>
          </div>
        </div>

        <!-- Satellite Content Input -->
        <div v-if="useSatelliteVersion" class="space-y-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 dark:border-slate-600 dark:bg-slate-800">
          <div class="flex items-center gap-2">
            <label for="satellite-content" class="text-sm font-medium">
              Satellite Message
            </label>
          </div>

          <!-- Satellite Emoji Selector -->
          <div class="space-y-2">
            <div class="flex items-center gap-2 rounded-lg border bg-slate-100 p-2 dark:bg-slate-700">
              <Smile class="h-3 w-3 text-slate-500" />
              <span class="text-xs text-slate-500">Add emoji:</span>

              <!-- Quick Satellite Emoji Picker -->
              <div class="flex flex-wrap gap-1">
                <button
                  v-for="emoji in emergencyEmojis"
                  :key="emoji"
                  @click="addSatelliteEmoji(emoji)"
                  class="rounded p-1 text-sm transition-colors hover:bg-slate-200 dark:hover:bg-slate-600"
                  :title="`Add ${emoji}`"
                >
                  {{ emoji }}
                </button>
              </div>
            </div>
          </div>

          <textarea
            id="satellite-content"
            v-model="satelliteContent"
            placeholder="Shortened version for satellite transmission (max 140 characters)..."
            class="min-h-[80px] w-full resize-none rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
            maxlength="140"
          />
          <div class="flex justify-between text-xs text-slate-500">
            <span>This version will be sent to satellite devices</span>
            <span>{{ satelliteContent.length }}/140</span>
          </div>
          <div class="rounded bg-slate-100 p-2 text-xs text-slate-600 dark:bg-slate-700 dark:text-slate-400">
            💡 <strong>Tip:</strong> Use this for emergency alerts, critical updates, or when you need to reach users with limited connectivity.
          </div>
        </div>

        <!-- Media Upload -->
        <div class="space-y-3">
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Attach Media</label>
          <div
            @click="triggerFileInput"
            @dragover.prevent
            @drop.prevent="handleFileDrop"
            class="cursor-pointer rounded-lg border-2 border-dashed border-slate-300 p-6 text-center transition-colors hover:border-cyan-500 hover:bg-slate-50 dark:border-slate-600 dark:hover:bg-slate-800"
          >
            <input
              ref="fileInput"
              type="file"
              multiple
              accept="image/*,video/*"
              @change="handleFileSelect"
              class="hidden"
            />
            <Upload class="mx-auto mb-2 h-8 w-8 text-slate-500" />
            <p class="text-sm text-slate-500">
              Drag & drop images/videos here, or click to select
            </p>
            <p class="mt-1 text-xs text-slate-500">
              Supports: JPG, PNG, GIF, WebP, MP4, MOV, AVI, WebM (max 50MB each)
            </p>
          </div>
        </div>

        <!-- Media Preview -->
        <div v-if="media.length > 0" class="space-y-3">
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            Attached Media ({{ media.length }})
          </label>
          <div class="grid grid-cols-2 gap-3">
            <div v-for="file in media" :key="file.id" class="group relative">
              <img
                v-if="file.type === 'image'"
                :src="file.preview"
                alt="Preview"
                class="h-32 w-full rounded-lg object-cover"
              />
              <video
                v-else
                :src="file.preview"
                class="h-32 w-full rounded-lg object-cover"
                muted
                @mouseenter="playVideo"
                @mouseleave="pauseVideo"
              />
              <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                <button
                  @click="removeMedia(file.id)"
                  class="rounded-lg bg-red-600 p-2 text-white hover:bg-red-700"
                >
                  <Trash2 class="h-4 w-4" />
                </button>
              </div>
              <div class="absolute bottom-2 left-2 rounded bg-black/70 px-2 py-1 text-xs text-white">
                {{ formatFileSize(file.size) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Scheduling Options -->
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <label for="schedule-post" class="text-sm font-medium text-slate-700 dark:text-slate-300">
              Schedule Post
            </label>
            <button
              @click="isScheduled = !isScheduled"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                isScheduled ? 'bg-cyan-500' : 'bg-slate-200 dark:bg-slate-700'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  isScheduled ? 'translate-x-6' : 'translate-x-1'
                ]"
              />
            </button>
          </div>

          <div v-if="isScheduled" class="space-y-3 rounded-lg border bg-slate-50 p-4 dark:bg-slate-800">
            <div class="grid grid-cols-2 gap-4">
              <!-- Date Picker -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Date</label>
                <input
                  v-model="scheduledDate"
                  type="date"
                  :min="new Date().toISOString().split('T')[0]"
                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                />
              </div>

              <!-- Time Picker -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Time</label>
                <div class="flex gap-2">
                  <select
                    v-model="scheduledHour"
                    class="w-20 rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                  >
                    <option v-for="i in 24" :key="i" :value="i.toString().padStart(2, '0')">
                      {{ i.toString().padStart(2, '0') }}
                    </option>
                  </select>
                  <span class="flex items-center">:</span>
                  <select
                    v-model="scheduledMinute"
                    class="w-20 rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                  >
                    <option v-for="i in 60" :key="i" :value="i.toString().padStart(2, '0')">
                      {{ i.toString().padStart(2, '0') }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="text-sm text-slate-500">
              Post will be published on
              <span class="font-medium">
                {{ formatScheduledDate() }}
              </span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-4">
          <button
            @click="handleClose"
            :disabled="isSubmitting"
            class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50 disabled:opacity-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
          >
            Cancel
          </button>
          <button
            @click="handleSubmit"
            :disabled="!canSubmit"
            class="flex-1 rounded-lg bg-cyan-500 px-4 py-2 text-white hover:bg-cyan-600 disabled:opacity-50"
          >
            {{ isSubmitting ? 'Creating...' : isScheduled ? 'Schedule Post' : 'Create Post' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import {
  CalendarDays,
  Smile,
  Satellite,
  Trash2,
  Upload,
  X,
} from 'lucide-vue-next'
import { feedApi } from '../../api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'created'])

const content = ref('')
const satelliteContent = ref('')
const useSatelliteVersion = ref(false)
const activeEmojiCategory = ref('recent')
const showEmojiPicker = ref(false)
const media = ref([])
const isScheduled = ref(false)
const scheduledDate = ref('')
const scheduledHour = ref('09')
const scheduledMinute = ref('00')
const isSubmitting = ref(false)
const userProfile = ref(null)
const fileInput = ref(null)

const recentEmojis = ['😀', '😂', '❤️', '👍', '🎉', '🚀', '🔥', '💡', '⚠️', '🌍']
const emergencyEmojis = ['🚨', '⚠️', '⚡', '🛰️', '📡', '🔔', '💡', '🔥']

const emojiCategories = {
  recent: recentEmojis,
  faces: ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇'],
  emotions: ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔'],
  gestures: ['👍', '👎', '👌', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉'],
  objects: ['📱', '💻', '⌨️', '🖥️', '🖨️', '🖱️', '🖲️', '💽', '💾', '💿'],
  nature: ['🌍', '🌎', '🌏', '🌐', '🌑', '🌒', '🌓', '🌔', '🌕', '🌖'],
  activities: ['🎉', '🎊', '🎈', '🎂', '🎁', '🎄', '🎃', '🎗️', '🎟️', '🎫'],
  transport: ['🚀', '🚁', '🚂', '🚃', '🚄', '🚅', '🚆', '🚇', '🚈', '🚉'],
  symbols: ['✨', '💫', '⭐', '🌟', '💥', '🔥', '💦', '💨', '💢', '💬'],
  flags: emergencyEmojis,
}

const canSubmit = computed(() => {
  return (content.value.trim().length > 0 || media.value.length > 0) && !isSubmitting.value
})

const isAdmin = computed(() => {
  return userProfile.value?.role === 1 || userProfile.value?.role === 'admin'
})

const addEmoji = (emoji) => {
  content.value += emoji
}

const addSatelliteEmoji = (emoji) => {
  satelliteContent.value += emoji
}

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files)
  handleFiles(files)
}

const handleFileDrop = (event) => {
  const files = Array.from(event.dataTransfer.files)
  handleFiles(files)
}

const handleFiles = (files) => {
  const newMedia = files.map((file) => ({
    id: crypto.randomUUID(),
    file,
    preview: URL.createObjectURL(file),
    type: file.type.startsWith('image/') ? 'image' : 'video',
    size: file.size,
  }))

  media.value.push(...newMedia)
}

const removeMedia = (id) => {
  const fileToRemove = media.value.find((m) => m.id === id)
  if (fileToRemove) {
    URL.revokeObjectURL(fileToRemove.preview)
  }
  media.value = media.value.filter((m) => m.id !== id)
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const formatScheduledDate = () => {
  if (!isScheduled.value || !scheduledDate.value) return ''
  const date = new Date(`${scheduledDate.value}T${scheduledHour.value}:${scheduledMinute.value}`)
  return date.toLocaleDateString() + ' at ' + date.toLocaleTimeString()
}

const playVideo = (event) => {
  event.target.play()
}

const pauseVideo = (event) => {
  event.target.pause()
}

const createPost = async () => {
  isSubmitting.value = true
  
  try {
    const token = localStorage.getItem('auth_token')
    const formData = new FormData()
    
    formData.append('content', content.value)
    if (satelliteContent.value) {
      formData.append('satellite_content', satelliteContent.value)
    }
    if (isScheduled.value && scheduledDate.value) {
      const scheduledDateTime = new Date(`${scheduledDate.value}T${scheduledHour.value}:${scheduledMinute.value}`)
      formData.append('scheduled_for', scheduledDateTime.toISOString())
    }
    
    // Add media files
    media.value.forEach((mediaFile, index) => {
      formData.append(`media[${index}]`, mediaFile.file)
    })

    const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/news/create`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    // Reset form
    content.value = ''
    satelliteContent.value = ''
    useSatelliteVersion.value = false
    activeEmojiCategory.value = 'recent'
    showEmojiPicker.value = false
    media.value = []
    isScheduled.value = false
    scheduledDate.value = ''
    scheduledHour.value = '09'
    scheduledMinute.value = '00'

    emit('created')
    emit('close')
    
  } catch (error) {
    console.error('Failed to create post:', error)
    alert(error instanceof Error ? error.message : 'Failed to create post')
  } finally {
    isSubmitting.value = false
  }
}

const handleSubmit = async () => {
  if (!canSubmit.value) return
  await createPost()
}

const handleClose = () => {
  if (isSubmitting.value) return

  // Clean up media previews
  media.value.forEach((m) => URL.revokeObjectURL(m.preview))

  // Reset form
  content.value = ''
  satelliteContent.value = ''
  useSatelliteVersion.value = false
  activeEmojiCategory.value = 'recent'
  showEmojiPicker.value = false
  media.value = []
  isScheduled.value = false
  scheduledDate.value = ''
  scheduledHour.value = '09'
  scheduledMinute.value = '00'

  emit('close')
}

onMounted(() => {
  // Load user profile
  const userProfileData = localStorage.getItem('user_profile')
  if (userProfileData) {
    try {
      userProfile.value = JSON.parse(userProfileData)
    } catch (error) {
      console.error('Error parsing user profile:', error)
    }
  }
})
</script>