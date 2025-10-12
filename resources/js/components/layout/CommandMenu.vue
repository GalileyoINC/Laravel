<template>
  <div class="relative">
    <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-slate-600 dark:bg-slate-800">
      <Search class="h-4 w-4 text-slate-500" />
      <input
        v-model="searchQuery"
        @keydown.enter="handleSearch"
        type="text"
        placeholder="Search posts, users, topics..."
        class="ml-2 flex-1 bg-transparent text-sm text-slate-900 placeholder-slate-500 focus:outline-none dark:text-white dark:placeholder-slate-400"
      />
      <kbd class="hidden rounded bg-slate-100 px-2 py-1 text-xs text-slate-600 dark:bg-slate-700 dark:text-slate-400 sm:inline-flex">
        ⌘K
      </kbd>
    </div>
    
    <!-- Search Results Dropdown -->
    <div v-if="showResults && searchResults.length > 0" class="absolute top-full z-10 mt-1 w-full rounded-lg border bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800">
      <div class="p-2">
        <div v-for="result in searchResults" :key="result.id" class="flex items-center gap-3 rounded-lg p-2 hover:bg-slate-100 dark:hover:bg-slate-700">
          <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
          <div class="flex-1">
            <div class="text-sm font-medium">{{ result.title }}</div>
            <div class="text-xs text-slate-500">{{ result.type }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Search } from 'lucide-vue-next'
import { searchApi } from '../../api'

const searchQuery = ref('')
const showResults = ref(false)
const searchResults = ref([])

const handleSearch = async () => {
  if (!searchQuery.value.trim()) return
  
  try {
    const response = await searchApi.search({
      phrase: searchQuery.value,
      page: 1,
      page_size: 5
    })
    
    if (response.data.status === 'success') {
      searchResults.value = response.data.data.results || []
      showResults.value = true
    }
  } catch (error) {
    console.error('Search failed:', error)
    searchResults.value = []
    showResults.value = false
  }
}

// Hide results when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    showResults.value = false
  }
}

// Add click outside listener
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
