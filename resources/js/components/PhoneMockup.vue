<template>
  <div class="flex items-center justify-center">
    <!-- iPhone 15 Container -->
    <div class="relative h-[504px] w-72 rounded-[45px] border-8 border-zinc-900 shadow-[0_0_2px_2px_rgba(255,255,255,0.1)]">
      <!-- Dynamic Island -->
      <div class="absolute left-1/2 top-2 z-20 h-[22px] w-[90px] -translate-x-1/2 transform rounded-full bg-black"></div>

      <div class="pointer-events-none absolute -inset-[1px] rounded-[37px] border-[3px] border-zinc-700 border-opacity-40 dark:bg-zinc-900">
        <div class="flex h-full flex-col">
        </div>
      </div>

      <!-- Screen Content -->
      <div class="relative flex h-full w-full overflow-hidden rounded-[37px] bg-zinc-900/10 pt-2">
        <slot />
      </div>

      <!-- Left Side Buttons -->
      <!-- Silent Switch -->
      <div class="absolute left-[-12px] top-20 h-8 w-[6px] rounded-l-md bg-zinc-900 shadow-md"></div>

      <!-- Volume Up -->
      <div class="absolute left-[-12px] top-36 h-12 w-[6px] rounded-l-md bg-zinc-900 shadow-md"></div>

      <!-- Volume Down -->
      <div class="absolute left-[-12px] top-52 h-12 w-[6px] rounded-l-md bg-zinc-900 shadow-md"></div>

      <!-- Right Side Button (Power) -->
      <div class="absolute right-[-12px] top-36 h-16 w-[6px] rounded-r-md bg-zinc-900 shadow-md"></div>
    </div>
  </div>
</template>

<script setup>
// PhoneMockup component - no additional logic needed
</script>
