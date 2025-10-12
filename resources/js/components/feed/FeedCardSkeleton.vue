<template>
  <div class="animate-pulse border-slate-200 bg-white/50 transition-colors hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-slate-600 rounded-lg border p-6">
    <div class="flex items-start justify-between">
      <div class="flex items-start gap-3">
        <!-- Avatar -->
        <div class="h-12 w-12 rounded-full bg-slate-200 dark:bg-slate-700"></div>
        <div class="flex-1">
          <!-- Title and badges -->
          <div class="mb-1 flex items-center gap-2">
            <div class="h-4 w-32 rounded bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-4 w-6 rounded bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-4 w-16 rounded bg-slate-200 dark:bg-slate-700"></div>
          </div>
          <!-- Meta info -->
          <div class="flex items-center gap-2 text-sm">
            <div class="h-3 w-20 rounded bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-3 w-3 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-3 w-16 rounded bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-3 w-3 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-3 w-20 rounded bg-slate-200 dark:bg-slate-700"></div>
          </div>
        </div>
      </div>
      <!-- Dropdown menu icon -->
      <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
    </div>
    <!-- Post Type Badge -->
    <div class="mt-2">
      <div class="h-5 w-32 rounded-full bg-slate-200 dark:bg-slate-700"></div>
    </div>
    
    <!-- Post Content -->
    <div class="mt-4 space-y-2">
      <div class="h-4 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
      <div class="h-4 w-5/6 rounded bg-slate-200 dark:bg-slate-700"></div>
      <div class="h-4 w-2/3 rounded bg-slate-200 dark:bg-slate-700"></div>
    </div>
    
    <!-- Post Actions -->
    <div class="mt-4 flex items-center justify-between">
      <div class="flex items-center gap-6">
        <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
        <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
        <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
      </div>
      <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
    </div>
  </div>
</template>

<script setup>
// No props needed for skeleton component
</script>
