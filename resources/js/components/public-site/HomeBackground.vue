<template>
  <div class="absolute inset-0 z-0">
    <!-- Particles effect -->
    <div class="absolute inset-0">
      <div
        v-for="i in 100"
        :key="i"
        class="absolute h-1 w-1 rounded-full bg-white opacity-20 animate-pulse"
        :style="{
          left: Math.random() * 100 + '%',
          top: Math.random() * 100 + '%',
          animationDelay: Math.random() * 2 + 's',
          animationDuration: (2 + Math.random() * 3) + 's'
        }"
      ></div>
    </div>
    
    <!-- Shooting stars effect -->
    <div class="absolute inset-0 overflow-hidden">
      <div
        v-for="i in 5"
        :key="i"
        class="absolute h-0.5 w-20 bg-gradient-to-r from-white to-transparent opacity-60 animate-pulse"
        :style="{
          left: Math.random() * 100 + '%',
          top: Math.random() * 100 + '%',
          transform: `rotate(${Math.random() * 360}deg)`,
          animationDelay: Math.random() * 3 + 's',
          animationDuration: (1 + Math.random() * 2) + 's'
        }"
      ></div>
    </div>
  </div>
</template>

<script setup>
// No additional logic needed for this background component
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-pulse {
  animation: float 2s ease-in-out infinite;
}
</style>
