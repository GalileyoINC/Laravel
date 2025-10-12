<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="loading" class="text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
      <p class="mt-2 text-slate-600 dark:text-slate-300">Loading profile...</p>
    </div>

    <div v-else-if="error" class="text-center text-red-500">
      <p>Error: {{ error }}</p>
      <button 
        @click="fetchProfile"
        class="mt-2 rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
      >
        Retry
      </button>
    </div>

    <div v-else-if="userProfile" class="max-w-4xl mx-auto">
      <!-- Profile Header -->
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Cover Image -->
        <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
        
        <!-- Profile Info -->
        <div class="px-6 pb-6">
          <div class="flex items-end -mt-16 mb-4">
            <!-- Avatar -->
            <div class="h-24 w-24 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center border-4 border-white dark:border-slate-800 overflow-hidden">
              <img 
                v-if="userProfile.image" 
                :src="userProfile.image" 
                :alt="userProfile.first_name + ' ' + userProfile.last_name"
                class="w-full h-full object-cover"
              />
              <span v-else class="text-2xl font-bold text-slate-600 dark:text-slate-300">
                {{ userProfile.first_name?.charAt(0) || 'U' }}
              </span>
            </div>
            
            <!-- Edit Button -->
            <div class="ml-auto">
              <button 
                @click="openEditModal"
                class="rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 transition-colors"
              >
                Edit Profile
              </button>
            </div>
          </div>

          <!-- User Details -->
          <div class="space-y-2">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
              {{ userProfile.first_name }} {{ userProfile.last_name }}
            </h1>
            <p class="text-slate-600 dark:text-slate-300">{{ userProfile.email }}</p>
            <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
              <span>Member since {{ formatDate(userProfile.created_at) }}</span>
              <span v-if="userProfile.role === 1" class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                Admin
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-lg">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Posts</p>
              <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ userStats.posts || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-lg">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Followers</p>
              <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ userStats.followers || 0 }}</p>
            </div>
          </div>
        </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-lg">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Bookmarks</p>
              <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ userStats.bookmarks || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Posts -->
      <div class="mt-8">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Recent Posts</h2>
        <div v-if="userPosts.length > 0" class="space-y-4">
          <div 
            v-for="post in userPosts" 
            :key="post.id"
            class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-lg"
          >
            <p class="text-slate-900 dark:text-white">{{ post.body }}</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
              {{ formatDate(post.created_at) }}
            </p>
          </div>
        </div>
        <div v-else class="text-center text-slate-500 dark:text-slate-400 py-8">
          No posts yet
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div class="max-h-[90vh] max-w-2xl w-full overflow-y-auto rounded-lg bg-white dark:bg-slate-900 shadow-xl">
        <!-- Header -->
        <div class="flex items-center gap-2 border-b border-slate-200 p-6 dark:border-slate-700">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <h2 class="text-lg font-semibold">Edit Profile</h2>
          <button
            @click="showEditModal = false"
            class="ml-auto text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Form -->
        <div class="p-6">
          <form @submit.prevent="updateProfile" class="space-y-6">
            <!-- Profile Picture -->
            <div class="flex items-center gap-6">
              <div class="relative">
                <div class="h-24 w-24 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center border-4 border-white dark:border-slate-800 overflow-hidden">
                  <img 
                    v-if="userProfile.image" 
                    :src="userProfile.image" 
                    :alt="editForm.first_name + ' ' + editForm.last_name"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-2xl font-bold text-slate-600 dark:text-slate-300">
                    {{ editForm.first_name?.charAt(0) || 'U' }}
                  </span>
                </div>
              </div>
              <div>
                <h3 class="mb-1 text-lg font-semibold text-slate-900 dark:text-white">
                  Profile Picture
                </h3>
                <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                  Update your profile picture to personalize your account
                </p>
                <div class="flex gap-2">
                  <button
                    type="button"
                    @click="triggerFileInput"
                    :disabled="isUploadingImage"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                  >
                    <svg v-if="isUploadingImage" class="mr-2 inline h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    {{ isUploadingImage ? 'Uploading...' : 'Upload New' }}
                  </button>
                  <button
                    type="button"
                    @click="removeProfilePicture"
                    class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600"
                  >
                    <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Remove
                  </button>
                  
                  <!-- Hidden file input -->
                  <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    @change="handleImageUpload"
                    class="hidden"
                  />
                </div>
              </div>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  First Name
                </label>
                <input
                  v-model="editForm.first_name"
                  type="text"
                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  placeholder="First Name"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Last Name
                </label>
                <input
                  v-model="editForm.last_name"
                  type="text"
                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  placeholder="Last Name"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Email
              </label>
              <input
                v-model="editForm.email"
                type="email"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="me@example.com"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Bio
              </label>
              <textarea
                v-model="editForm.about"
                rows="3"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="Tell us about yourself"
              ></textarea>
            </div>

            <!-- Privacy Settings -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Privacy Settings</h3>
              
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Who can see you in the member directory?
                  </label>
                  <select
                    v-model="editForm.memberDirectory"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  >
                    <option value="Public">Public</option>
                    <option value="Friend">Friend</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Who can see your phone number?
                  </label>
                  <select
                    v-model="editForm.phoneVisibility"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  >
                    <option value="Public">Public</option>
                    <option value="Friend">Friend</option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Who can see your location?
                  </label>
                  <select
                    v-model="editForm.locationVisibility"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  >
                    <option value="Public">Public</option>
                    <option value="Friend">Friend</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
              <button
                type="button"
                @click="showEditModal = false"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="isUpdating"
                class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg v-if="isUpdating" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ isUpdating ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { profileApi } from '../../api'

const userProfile = ref(null)
const userStats = ref({})
const userPosts = ref([])
const loading = ref(true)
const error = ref(null)

// Edit modal state
const showEditModal = ref(false)
const isUpdating = ref(false)
const isUploadingImage = ref(false)
const fileInput = ref(null)
const editForm = ref({
  first_name: '',
  last_name: '',
  email: '',
  about: '',
  memberDirectory: 'Public',
  phoneVisibility: 'Public',
  locationVisibility: 'Public'
})

const fetchProfile = async () => {
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      error.value = 'Not authenticated'
      return
    }

    // Fetch user profile
    const profileResponse = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/v1/customer/get-profile`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      }
    })

    if (!profileResponse.ok) {
      throw new Error(`HTTP error! status: ${profileResponse.status}`)
    }

    const profileData = await profileResponse.json()
    console.log('Profile API response:', profileData)
    console.log('Profile API data:', profileData.data)
    userProfile.value = profileData.data || profileData
    console.log('User profile data:', userProfile.value)
    console.log('User profile image field:', userProfile.value.image)

    // Mock stats for now (can be replaced with real API calls)
    userStats.value = {
      posts: 12,
      followers: 156,
      bookmarks: 8
    }

    // Fetch user posts (mock for now)
    userPosts.value = []

  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to fetch profile'
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const openEditModal = () => {
  if (userProfile.value) {
    editForm.value = {
      first_name: userProfile.value.first_name || '',
      last_name: userProfile.value.last_name || '',
      email: userProfile.value.email || '',
      about: userProfile.value.about || '',
      memberDirectory: userProfile.value.general_visibility === 0 ? 'Public' : 'Friend',
      phoneVisibility: userProfile.value.phone_visibility === 0 ? 'Public' : 'Friend',
      locationVisibility: userProfile.value.address_visibility === 0 ? 'Public' : 'Friend'
    }
  }
  showEditModal.value = true
}

const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click()
  }
}

const updateProfile = async () => {
  isUpdating.value = true
  
  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      throw new Error('Not authenticated')
    }

    const updateData = {
      first_name: editForm.value.first_name,
      last_name: editForm.value.last_name,
      email: editForm.value.email,
      about: editForm.value.about,
      general_visibility: editForm.value.memberDirectory === 'Public' ? 0 : 1,
      phone_visibility: editForm.value.phoneVisibility === 'Public' ? 0 : 1,
      address_visibility: editForm.value.locationVisibility === 'Public' ? 0 : 1
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/v1/customer/update-profile`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(updateData)
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const result = await response.json()
    
    // Update local profile data
    if (userProfile.value) {
      userProfile.value.first_name = editForm.value.first_name
      userProfile.value.last_name = editForm.value.last_name
      userProfile.value.email = editForm.value.email
      userProfile.value.about = editForm.value.about
      userProfile.value.general_visibility = updateData.general_visibility
      userProfile.value.phone_visibility = updateData.phone_visibility
      userProfile.value.address_visibility = updateData.address_visibility
    }

    showEditModal.value = false
    alert('Profile updated successfully!')
    
  } catch (err) {
    alert(`Failed to update profile: ${err.message}`)
  } finally {
    isUpdating.value = false
  }
}

const handleImageUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  // Validate file type
  if (!file.type.startsWith('image/')) {
    alert('Please select a valid image file')
    return
  }

  // Validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Image size should be less than 5MB')
    return
  }

  isUploadingImage.value = true

  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      throw new Error('Not authenticated')
    }

    const formData = new FormData()
    formData.append('image_file', file)

    const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/v1/customer/update-profile`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
      body: formData
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const result = await response.json()
    console.log('Upload response:', result)
    console.log('Upload response data:', result.data)
    
    // Update profile photo immediately
    if (result.data && result.data.image) {
      userProfile.value.image = result.data.image
      console.log('Updated image URL:', result.data.image)
    } else {
      console.log('No image field found in response data:', result.data)
    }
    
    // Refresh profile data
    await fetchProfile()
    alert('Profile picture updated successfully!')
    
  } catch (err) {
    alert(`Failed to upload image: ${err.message}`)
  } finally {
    isUploadingImage.value = false
    // Reset file input
    if (fileInput.value) {
      fileInput.value.value = ''
    }
  }
}

const removeProfilePicture = async () => {
  if (!confirm('Are you sure you want to remove your profile picture?')) {
    return
  }

  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      throw new Error('Not authenticated')
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:20000'}/api/v1/customer/remove-avatar`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    // Refresh profile data
    await fetchProfile()
    alert('Profile picture removed successfully!')
    
  } catch (err) {
    alert(`Failed to remove profile picture: ${err.message}`)
  }
}

onMounted(() => {
  fetchProfile()
})
</script>
