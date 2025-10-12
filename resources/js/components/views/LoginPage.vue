<template>
  <div class="flex min-h-full flex-col items-center justify-center gap-6 p-6 md:p-10">
    <div class="w-full max-w-sm">
      <LoginForm />
    </div>
  </div>
</template>

<script setup>
import LoginForm from '../auth/LoginForm.vue'
</script>