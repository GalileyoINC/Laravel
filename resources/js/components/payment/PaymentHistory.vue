<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payment History</h2>
        <p class="text-gray-600 dark:text-gray-400">View your payment transactions and invoices</p>
      </div>
      
      <!-- Filters -->
      <div class="flex space-x-4">
        <select
          v-model="filters.type"
          @change="loadPaymentHistory"
          class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Types</option>
          <option value="authorize">Credit Card</option>
          <option value="bitpay">BitPay</option>
          <option value="apply_credit">Credit Applied</option>
          <option value="pay_from_credit">Paid from Credit</option>
          <option value="discount">Discount</option>
          <option value="apple">Apple Pay</option>
        </select>
        
        <select
          v-model="filters.is_success"
          @change="loadPaymentHistory"
          class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Status</option>
          <option value="true">Successful</option>
          <option value="false">Failed</option>
        </select>
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
          <h3 class="text-sm font-medium text-red-800">Error loading payment history</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Payment History Table -->
    <div v-else class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
      <div v-if="paymentHistory.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
        <div
          v-for="payment in paymentHistory"
          :key="payment.id"
          class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-4">
                <!-- Status Icon -->
                <div class="flex-shrink-0">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center"
                    :class="getStatusColor(payment)"
                  >
                    <svg v-if="payment.is_success" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>

                <!-- Payment Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ payment.title }}
                      </h3>
                      <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ getTypeLabel(payment.type) }}</span>
                        <span>{{ formatDate(payment.created_at) }}</span>
                        <span v-if="payment.card_number">****{{ payment.card_number.slice(-4) }}</span>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ formatAmount(payment.total) }}
                      </div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ getStatusText(payment) }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0">
                  <div class="flex items-center space-x-2">
                    <span
                      v-if="payment.is_test"
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                    >
                      Test
                    </span>
                    <span
                      v-if="payment.is_void"
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                    >
                      Voided
                    </span>
                    <button
                      v-if="payment.invoice_id"
                      @click="downloadInvoice(payment.invoice_id)"
                      class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                    >
                      Download Invoice
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No payment history</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your payment transactions will appear here.</p>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="paymentHistory.length > 0 && pagination.total > pagination.limit" class="flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-300">
        Showing {{ pagination.offset + 1 }} to {{ Math.min(pagination.offset + pagination.limit, pagination.total) }} of {{ pagination.total }} results
      </div>
      <div class="flex space-x-2">
        <button
          @click="loadPaymentHistory(pagination.page - 1)"
          :disabled="pagination.page <= 1"
          class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          Previous
        </button>
        <button
          @click="loadPaymentHistory(pagination.page + 1)"
          :disabled="pagination.page >= Math.ceil(pagination.total / pagination.limit)"
          class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { paymentApi } from '../../api'

export default {
  name: 'PaymentHistory',
  setup() {
    const paymentHistory = ref([])
    const loading = ref(false)
    const error = ref(null)
    const filters = ref({
      type: '',
      is_success: ''
    })
    const pagination = ref({
      page: 1,
      limit: 20,
      total: 0,
      offset: 0
    })

    // Load payment history
    const loadPaymentHistory = async (page = 1) => {
      loading.value = true
      error.value = null
      
      try {
        const params = {
          page,
          limit: pagination.value.limit,
          ...filters.value
        }
        
        const response = await paymentApi.getPaymentHistory(params)
        const data = response.data.data
        
        paymentHistory.value = data.list || []
        pagination.value = {
          page: data.page,
          limit: data.page_size,
          total: data.count,
          offset: (data.page - 1) * data.page_size
        }
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load payment history'
      } finally {
        loading.value = false
      }
    }

    // Download invoice
    const downloadInvoice = async (invoiceId) => {
      try {
        const response = await paymentApi.downloadInvoice(invoiceId)
        
        // Create blob and download
        const blob = new Blob([response.data], { type: 'application/pdf' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `invoice-${invoiceId}.pdf`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(url)
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to download invoice'
      }
    }

    // Get status color
    const getStatusColor = (payment) => {
      if (payment.is_void) return 'bg-gray-500'
      if (payment.is_test) return 'bg-yellow-500'
      return payment.is_success ? 'bg-green-500' : 'bg-red-500'
    }

    // Get status text
    const getStatusText = (payment) => {
      if (payment.is_void) return 'Voided'
      if (payment.is_test) return 'Test Payment'
      return payment.is_success ? 'Success' : 'Failed'
    }

    // Get type label
    const getTypeLabel = (type) => {
      const labels = {
        'authorize': 'Credit Card',
        'bitpay': 'BitPay',
        'apply_credit': 'Credit Applied',
        'pay_from_credit': 'Paid from Credit',
        'discount': 'Discount',
        'apple': 'Apple Pay'
      }
      return labels[type] || type
    }

    // Format amount
    const formatAmount = (amount) => {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
    }

    // Format date
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    onMounted(() => {
      loadPaymentHistory()
    })

    return {
      paymentHistory,
      loading,
      error,
      filters,
      pagination,
      loadPaymentHistory,
      downloadInvoice,
      getStatusColor,
      getStatusText,
      getTypeLabel,
      formatAmount,
      formatDate
    }
  }
}
</script>
