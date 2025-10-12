<template>
  <footer class="bg-slate-900 text-white dark:bg-slate-950">
    <div class="container py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Logo and Description -->
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <img src="/galileyo-icon-white.svg" alt="Galileyo" class="h-8 w-8" />
            <span class="text-xl font-bold">Galileyo</span>
          </div>
          <p class="text-slate-300 mb-4">
            Be the first to know about emergencies, outages, and critical events in your area.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li>
              <router-link to="/blog" class="text-slate-300 hover:text-white transition-colors">
                Blog
              </router-link>
            </li>
            <li>
              <router-link to="/contact" class="text-slate-300 hover:text-white transition-colors">
                Contact
              </router-link>
            </li>
            <li>
              <router-link to="/faq" class="text-slate-300 hover:text-white transition-colors">
                FAQ
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Legal</h3>
          <ul class="space-y-2">
            <li>
              <router-link to="/privacy-policy" class="text-slate-300 hover:text-white transition-colors">
                Privacy Policy
              </router-link>
            </li>
            <li>
              <router-link to="/terms-of-service" class="text-slate-300 hover:text-white transition-colors">
                Terms of Service
              </router-link>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom -->
      <div class="border-t border-slate-800 mt-8 pt-8 text-center text-slate-400">
        <p>&copy; 2024 Galileyo. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
// No additional logic needed for footer
</script>
