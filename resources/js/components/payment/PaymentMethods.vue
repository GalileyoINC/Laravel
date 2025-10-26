<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Methods</h2>
        <p class="text-gray-600 dark:text-gray-400">Manage your credit cards and payment methods</p>
      </div>
      <button
        @click="showAddCardModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
      >
        Add Payment Method
      </button>
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
          <h3 class="text-sm font-medium text-red-800">Error loading payment methods</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Credit Cards List -->
    <div v-else-if="creditCards.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="card in creditCards"
        :key="card.id"
        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 relative"
        :class="{ 'ring-2 ring-blue-500': card.is_preferred }"
      >
        <!-- Preferred Badge -->
        <div v-if="card.is_preferred" class="absolute top-4 right-4">
          <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
            Preferred
          </span>
        </div>

        <!-- Card Info -->
        <div class="space-y-3">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
              <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                {{ getCardIcon(card.type) }}
              </span>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ card.type }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ card.masked_number }}</p>
            </div>
          </div>

          <div class="text-sm text-gray-600 dark:text-gray-400">
            <p>{{ card.first_name }} {{ card.last_name }}</p>
            <p>Expires {{ card.formatted_expiration }}</p>
          </div>

          <!-- Actions -->
          <div class="flex space-x-2 pt-2">
            <button
              v-if="!card.is_preferred"
              @click="setPreferredCard(card.id)"
              class="text-blue-600 hover:text-blue-700 text-sm font-medium"
            >
              Set as Preferred
            </button>
            <button
              @click="editCard(card)"
              class="text-gray-600 hover:text-gray-700 text-sm font-medium"
            >
              Edit
            </button>
            <button
              @click="deleteCard(card.id)"
              class="text-red-600 hover:text-red-700 text-sm font-medium"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No payment methods</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding a payment method.</p>
      <div class="mt-6">
        <button
          @click="showAddCardModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Add Payment Method
        </button>
      </div>
    </div>

    <!-- Add/Edit Card Modal -->
    <div v-if="showAddCardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ editingCard ? 'Edit Payment Method' : 'Add Payment Method' }}
          </h3>
          
          <form @submit.prevent="saveCard" class="space-y-4">
            <!-- Card Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Card Number
              </label>
              <input
                v-model="form.card_number"
                type="text"
                placeholder="1234 5678 9012 3456"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <!-- Expiration -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Month
                </label>
                <select
                  v-model="form.expiration_month"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                  <option value="">MM</option>
                  <option v-for="month in 12" :key="month" :value="month.toString().padStart(2, '0')">
                    {{ month.toString().padStart(2, '0') }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Year
                </label>
                <select
                  v-model="form.expiration_year"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                  required
                >
                  <option value="">YYYY</option>
                  <option v-for="year in getYearRange()" :key="year" :value="year">
                    {{ year }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Security Code -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Security Code
              </label>
              <input
                v-model="form.security_code"
                type="text"
                placeholder="123"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <!-- Name -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  First Name
                </label>
                <input
                  v-model="form.first_name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Last Name
                </label>
                <input
                  v-model="form.last_name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                  required
                />
              </div>
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Phone Number
              </label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <!-- ZIP Code -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                ZIP Code
              </label>
              <input
                v-model="form.zip"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors"
              >
                {{ saving ? 'Saving...' : (editingCard ? 'Update' : 'Add') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { paymentApi } from '../../api'

export default {
  name: 'PaymentMethods',
  setup() {
    const creditCards = ref([])
    const loading = ref(false)
    const error = ref(null)
    const saving = ref(false)
    const showAddCardModal = ref(false)
    const editingCard = ref(null)

    const form = ref({
      card_number: '',
      expiration_month: '',
      expiration_year: '',
      security_code: '',
      first_name: '',
      last_name: '',
      phone: '',
      zip: ''
    })

    // Load credit cards
    const loadCreditCards = async () => {
      loading.value = true
      error.value = null
      
      try {
        const response = await paymentApi.getCreditCards()
        creditCards.value = response.data.data.list || []
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load payment methods'
      } finally {
        loading.value = false
      }
    }

    // Save card (create or update)
    const saveCard = async () => {
      saving.value = true
      
      try {
        if (editingCard.value) {
          await paymentApi.updateCreditCard({
            ...form.value,
            id: editingCard.value.id
          })
        } else {
          await paymentApi.createCreditCard(form.value)
        }
        
        await loadCreditCards()
        closeModal()
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to save payment method'
      } finally {
        saving.value = false
      }
    }

    // Set preferred card
    const setPreferredCard = async (cardId) => {
      try {
        await paymentApi.setPreferredCard(cardId)
        await loadCreditCards()
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to set preferred card'
      }
    }

    // Delete card
    const deleteCard = async (cardId) => {
      if (!confirm('Are you sure you want to delete this payment method?')) {
        return
      }
      
      try {
        await paymentApi.deleteCreditCard(cardId)
        await loadCreditCards()
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to delete payment method'
      }
    }

    // Edit card
    const editCard = (card) => {
      editingCard.value = card
      form.value = {
        card_number: card.num,
        expiration_month: card.expiration_month.toString().padStart(2, '0'),
        expiration_year: card.expiration_year.toString(),
        security_code: '',
        first_name: card.first_name,
        last_name: card.last_name,
        phone: card.phone,
        zip: card.zip
      }
      showAddCardModal.value = true
    }

    // Close modal
    const closeModal = () => {
      showAddCardModal.value = false
      editingCard.value = null
      form.value = {
        card_number: '',
        expiration_month: '',
        expiration_year: '',
        security_code: '',
        first_name: '',
        last_name: '',
        phone: '',
        zip: ''
      }
    }

    // Get card icon
    const getCardIcon = (type) => {
      const icons = {
        'Visa': 'V',
        'MasterCard': 'M',
        'American Express': 'A',
        'Discover': 'D'
      }
      return icons[type] || 'C'
    }

    // Get year range
    const getYearRange = () => {
      const currentYear = new Date().getFullYear()
      const years = []
      for (let i = 0; i < 20; i++) {
        years.push(currentYear + i)
      }
      return years
    }

    onMounted(() => {
      loadCreditCards()
    })

    return {
      creditCards,
      loading,
      error,
      saving,
      showAddCardModal,
      editingCard,
      form,
      loadCreditCards,
      saveCard,
      setPreferredCard,
      deleteCard,
      editCard,
      closeModal,
      getCardIcon,
      getYearRange
    }
  }
}
</script>
