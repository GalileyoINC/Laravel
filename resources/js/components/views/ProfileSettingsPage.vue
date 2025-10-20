<template>
  <main class="container py-8">
    <div class="mx-auto max-w-4xl">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Account Settings</h1>
        <p class="mt-2 text-slate-600 dark:text-slate-400">
          Manage your account settings and privacy preferences
        </p>
      </div>

      <!-- Settings Tabs -->
      <div class="mb-8">
        <nav class="flex space-x-8 border-b border-slate-200 dark:border-slate-700">
          <button
            @click="activeTab = 'general'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'general'
                ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'
            ]"
          >
            General
          </button>
          <button
            @click="activeTab = 'privacy'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'privacy'
                ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'
            ]"
          >
            Privacy
          </button>
          <button
            @click="activeTab = 'security'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'security'
                ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'
            ]"
          >
            Security
          </button>
        </nav>
      </div>

      <!-- General Settings -->
      <div v-if="activeTab === 'general'" class="space-y-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Profile Information</h3>
          
          <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  First Name
                </label>
                <input
                  v-model="editForm.first_name"
                  type="text"
                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                  placeholder="Enter your first name"
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
                  placeholder="Enter your last name"
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
                placeholder="Enter your email"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                About
              </label>
              <textarea
                v-model="editForm.about"
                rows="3"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="Tell us about yourself"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Profile Picture -->
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Profile Picture</h3>
          
          <div class="flex items-center space-x-6">
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
            
            <div class="space-y-2">
              <button
                @click="fileInput?.click()"
                :disabled="isUploadingImage"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Upload class="h-4 w-4 mr-2" />
                {{ isUploadingImage ? 'Uploading...' : 'Upload new' }}
              </button>
              
              <button
                v-if="userProfile.image"
                @click="removeProfilePicture"
                class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-slate-800 dark:text-red-400 dark:border-red-600 dark:hover:bg-red-900"
              >
                <Trash2 class="h-4 w-4 mr-2" />
                Remove
              </button>
            </div>
          </div>
          
          <input
            ref="fileInput"
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            class="hidden"
          />
        </div>
      </div>

      <!-- Privacy Settings -->
      <div v-if="activeTab === 'privacy'" class="space-y-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Privacy Settings</h3>
          
          <div class="space-y-4">
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
        </div>
      </div>

      <!-- Security Settings -->
      <div v-if="activeTab === 'security'" class="space-y-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Change Password</h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Current Password
              </label>
              <input
                v-model="passwordForm.currentPassword"
                type="password"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="Enter current password"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                New Password
              </label>
              <input
                v-model="passwordForm.newPassword"
                type="password"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="Enter new password"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Confirm New Password
              </label>
              <input
                v-model="passwordForm.confirmPassword"
                type="password"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                placeholder="Confirm new password"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="mt-8 flex justify-end">
        <button
          @click="saveSettings"
          :disabled="isSaving"
          class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <Save class="h-5 w-5 mr-2" />
          {{ isSaving ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Upload, Trash2, Save } from 'lucide-vue-next'

const activeTab = ref('general')
const isSaving = ref(false)
const isUploadingImage = ref(false)
const fileInput = ref(null)

const userProfile = ref({})
const editForm = ref({
  first_name: '',
  last_name: '',
  email: '',
  about: '',
  memberDirectory: 'Public',
  phoneVisibility: 'Public',
  locationVisibility: 'Public'
})

const passwordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const fetchProfile = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      console.error('Not authenticated')
      return
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL || ''}/api/v1/customer/get-profile`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    userProfile.value = data.data || data
    editForm.value = {
      first_name: userProfile.value.first_name || '',
      last_name: userProfile.value.last_name || '',
      email: userProfile.value.email || '',
      about: userProfile.value.about || '',
      memberDirectory: 'Public',
      phoneVisibility: 'Public',
      locationVisibility: 'Public'
    }
  } catch (err) {
    console.error('Failed to fetch profile:', err)
  }
}

const handleImageUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  if (!file.type.startsWith('image/')) {
    alert('Please select a valid image file')
    return
  }

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

    const response = await fetch(`${import.meta.env.VITE_API_URL || ''}/api/v1/customer/update-profile`, {
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
    if (result.data && result.data.image) {
      userProfile.value.image = result.data.image
    }
    
    await fetchProfile()
    alert('Profile picture updated successfully!')
    
  } catch (err) {
    alert(`Failed to upload image: ${err.message}`)
  } finally {
    isUploadingImage.value = false
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

    const response = await fetch(`${import.meta.env.VITE_API_URL || ''}/api/v1/customer/remove-avatar`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    userProfile.value.image = null
    alert('Profile picture removed successfully!')
    
  } catch (err) {
    alert(`Failed to remove profile picture: ${err.message}`)
  }
}

const saveSettings = async () => {
  isSaving.value = true

  try {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      throw new Error('Not authenticated')
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL || ''}/api/v1/customer/update-profile`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(editForm.value)
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const result = await response.json()
    if (result.data) {
      userProfile.value = { ...userProfile.value, ...result.data }
    }
    
    alert('Settings saved successfully!')
    
  } catch (err) {
    alert(`Failed to save settings: ${err.message}`)
  } finally {
    isSaving.value = false
  }
}

onMounted(() => {
  fetchProfile()
})
</script>
