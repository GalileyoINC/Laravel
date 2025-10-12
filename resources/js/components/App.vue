<template>
  <div id="app" class="font-inter relative z-10 flex min-h-screen flex-col bg-background dark:bg-slate-900">
    <SiteHeader :show-map="true" />
    <main class="flex flex-1 flex-col">
      <RouterView />
    </main>
    <SiteFooter />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import SiteHeader from './layout/SiteHeader.vue'
import SiteFooter from './layout/SiteFooter.vue'
</script>
