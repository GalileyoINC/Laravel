<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          Alert Map
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
          Real-time alerts and emergency notifications displayed on an interactive map
        </p>
      </div>

      <!-- Alert Map Component -->
      <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
        <AlertMap />
      </div>

      <!-- Additional Info -->
      <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Map Features -->
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
          <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
            Map Features
          </h3>
          <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
            <li class="flex items-center gap-2">
              <span class="text-green-500">✓</span>
              Interactive markers with alert details
            </li>
            <li class="flex items-center gap-2">
              <span class="text-green-500">✓</span>
              Severity-based color coding
            </li>
            <li class="flex items-center gap-2">
              <span class="text-green-500">✓</span>
              Affected area visualization
            </li>
            <li class="flex items-center gap-2">
              <span class="text-green-500">✓</span>
              Real-time filtering options
            </li>
          </ul>
        </div>

        <!-- Alert Types -->
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
          <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
            Alert Types
          </h3>
          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-600 dark:text-gray-400">Emergency</span>
              <span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800 dark:bg-red-900 dark:text-red-200">
                Critical
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600 dark:text-gray-400">Weather</span>
              <span class="rounded-full bg-orange-100 px-2 py-1 text-xs text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                High
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600 dark:text-gray-400">Security</span>
              <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                Medium
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600 dark:text-gray-400">Traffic</span>
              <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800 dark:bg-green-900 dark:text-green-200">
                Low
              </span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
          <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
            Quick Actions
          </h3>
          <div class="space-y-2">
            <button 
              @click="refreshAlerts"
              class="w-full rounded-lg bg-blue-500 px-4 py-2 text-sm text-white hover:bg-blue-600"
            >
              Refresh Alerts
            </button>
            <button 
              @click="centerMap"
              class="w-full rounded-lg bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600"
            >
              Center Map
            </button>
            <button 
              @click="showAllAlerts"
              class="w-full rounded-lg bg-green-500 px-4 py-2 text-sm text-white hover:bg-green-600"
            >
              Show All Alerts
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AlertMap from '../AlertMap.vue'

// Component refs
const alertMapRef = ref(null)

// Methods
const refreshAlerts = () => {
  // This would trigger a refresh in the AlertMap component
  // For now, we'll just reload the page
  window.location.reload()
}

const centerMap = () => {
  // This would center the map in the AlertMap component
  // Implementation would depend on exposing methods from AlertMap
}

const showAllAlerts = () => {
  // This would clear all filters in the AlertMap component
  // Implementation would depend on exposing methods from AlertMap
}
</script>