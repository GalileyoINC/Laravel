<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Membership</h2>
        <p class="text-gray-600 dark:text-gray-400">Manage your subscription and billing</p>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading membership</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Current Plan -->
    <div v-else-if="currentPlan" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ currentPlan.name }}</h3>
          <p class="text-gray-600 dark:text-gray-400">{{ currentPlan.description }}</p>
          <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ formatAmount(currentPlan.price) }}/month</span>
            <span v-if="currentPlan.is_new_plan" class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
              New Plan
            </span>
          </div>
        </div>
        <div class="text-right">
          <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
          <div class="text-lg font-medium text-green-600 dark:text-green-400">
            {{ getSubscriptionStatus() }}
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-6 flex space-x-4">
        <button
          v-if="!currentPlan.is_cancelled"
          @click="showCancelDialog = true"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Cancel Membership
        </button>
        <button
          v-else-if="currentPlan.can_reactivate"
          @click="restoreMembership"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Restore Membership
        </button>
        <button
          @click="showSwitchPlanModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Switch Plan
        </button>
      </div>
    </div>

    <!-- Available Plans -->
    <div v-if="availablePlans.length > 0" class="space-y-4">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">Available Plans</h3>
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="plan in availablePlans"
          :key="plan.id"
          class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 relative"
          :class="{ 'ring-2 ring-blue-500': plan.current }"
        >
          <!-- Current Badge -->
          <div v-if="plan.current" class="absolute top-4 right-4">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
              Current
            </span>
          </div>

          <!-- Plan Info -->
          <div class="space-y-4">
            <div>
              <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ plan.name }}</h4>
              <p class="text-gray-600 dark:text-gray-400">{{ plan.description }}</p>
            </div>

            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ formatAmount(plan.price) }}
              <span class="text-sm font-normal text-gray-500 dark:text-gray-400">/month</span>
            </div>

            <!-- Features -->
            <div v-if="plan.settings" class="space-y-2">
              <div
                v-for="(value, key) in plan.settings"
                :key="key"
                class="flex items-center text-sm text-gray-600 dark:text-gray-400"
              >
                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                {{ formatSetting(key, value) }}
              </div>
            </div>

            <!-- Action Button -->
            <button
              v-if="!plan.current"
              @click="switchToPlan(plan)"
              :disabled="switching"
              class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors"
            >
              {{ switching ? 'Switching...' : 'Switch to This Plan' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Switch Plan Modal -->
    <div v-if="showSwitchPlanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Switch Plan</h3>
          
          <div class="space-y-4">
            <div
              v-for="plan in newPlans"
              :key="plan.id"
              @click="selectedPlan = plan"
              class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              :class="{ 'ring-2 ring-blue-500': selectedPlan?.id === plan.id }"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900 dark:text-white">{{ plan.name }}</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatAmount(plan.price) }}/month</p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ plan.price > currentPlan.price ? 'Upgrade' : 'Downgrade' }}
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button
              @click="showSwitchPlanModal = false"
              class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
            >
              Cancel
            </button>
            <button
              @click="confirmSwitchPlan"
              :disabled="!selectedPlan || switching"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors"
            >
              {{ switching ? 'Switching...' : 'Switch Plan' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Cancel Membership Dialog -->
    <div v-if="showCancelDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cancel Membership</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Are you sure you want to cancel your membership? You will lose access to premium features.
          </p>
          
          <div class="flex justify-end space-x-3">
            <button
              @click="showCancelDialog = false"
              class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
            >
              Keep Membership
            </button>
            <button
              @click="cancelMembership"
              :disabled="cancelling"
              class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white px-4 py-2 rounded-lg transition-colors"
            >
              {{ cancelling ? 'Cancelling...' : 'Cancel Membership' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { paymentApi } from '../api'

export default {
  name: 'Membership',
  setup() {
    const currentPlan = ref(null)
    const availablePlans = ref([])
    const loading = ref(false)
    const error = ref(null)
    const switching = ref(false)
    const cancelling = ref(false)
    const showSwitchPlanModal = ref(false)
    const showCancelDialog = ref(false)
    const selectedPlan = ref(null)

    // Computed properties
    const newPlans = computed(() => {
      return availablePlans.value.filter(plan => !plan.current)
    })

    // Load membership data
    const loadMembership = async () => {
      loading.value = true
      error.value = null
      
      try {
        const [plansResponse] = await Promise.all([
          paymentApi.getProducts({ full_info: true })
        ])
        
        const plans = plansResponse.data.data.list || []
        availablePlans.value = plans
        
        // Find current plan
        currentPlan.value = plans.find(plan => plan.current) || null
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load membership data'
      } finally {
        loading.value = false
      }
    }

    // Switch to a plan
    const switchToPlan = async (plan) => {
      if (!confirm(`Are you sure you want to switch to ${plan.name}?`)) {
        return
      }
      
      switching.value = true
      
      try {
        await paymentApi.switchPlan({
          product_id: plan.id,
          credit_card_id: null // Will use preferred card
        })
        
        await loadMembership()
        showSwitchPlanModal.value = false
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to switch plan'
      } finally {
        switching.value = false
      }
    }

    // Confirm switch plan
    const confirmSwitchPlan = async () => {
      if (!selectedPlan.value) return
      
      await switchToPlan(selectedPlan.value)
    }

    // Cancel membership
    const cancelMembership = async () => {
      cancelling.value = true
      
      try {
        await paymentApi.cancelMembership()
        await loadMembership()
        showCancelDialog.value = false
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to cancel membership'
      } finally {
        cancelling.value = false
      }
    }

    // Restore membership
    const restoreMembership = async () => {
      try {
        await paymentApi.restoreMembership()
        await loadMembership()
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to restore membership'
      }
    }

    // Get subscription status
    const getSubscriptionStatus = () => {
      if (!currentPlan.value) return 'No Plan'
      if (currentPlan.value.is_cancelled) return 'Cancelled'
      return 'Active'
    }

    // Format amount
    const formatAmount = (amount) => {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
    }

    // Format setting
    const formatSetting = (key, value) => {
      const labels = {
        'max_alerts': `${value} alerts`,
        'max_devices': `${value} devices`,
        'storage_gb': `${value} GB storage`,
        'api_calls': `${value} API calls`,
        'support_level': `${value} support`
      }
      return labels[key] || `${key}: ${value}`
    }

    onMounted(() => {
      loadMembership()
    })

    return {
      currentPlan,
      availablePlans,
      loading,
      error,
      switching,
      cancelling,
      showSwitchPlanModal,
      showCancelDialog,
      selectedPlan,
      newPlans,
      loadMembership,
      switchToPlan,
      confirmSwitchPlan,
      cancelMembership,
      restoreMembership,
      getSubscriptionStatus,
      formatAmount,
      formatSetting
    }
  }
}
</script>
