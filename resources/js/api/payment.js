import api from './index'

/**
 * Payment API service
 * Handles all payment-related API calls
 */
export const paymentApi = {
  /**
   * Get user's credit cards
   */
  async getCreditCards(params = {}) {
    return api.get('/v1/payment/credit-cards', { params })
  },

  /**
   * Create a new credit card
   */
  async createCreditCard(data) {
    return api.post('/v1/payment/credit-cards', data)
  },

  /**
   * Update a credit card
   */
  async updateCreditCard(data) {
    return api.put('/v1/payment/credit-cards', data)
  },

  /**
   * Set a credit card as preferred
   */
  async setPreferredCard(cardId) {
    return api.post('/v1/payment/credit-cards/set-preferred', { id: cardId })
  },

  /**
   * Delete a credit card
   */
  async deleteCreditCard(cardId) {
    return api.delete('/v1/payment/credit-cards', { data: { id: cardId } })
  },

  /**
   * Get payment history
   */
  async getPaymentHistory(params = {}) {
    return api.get('/v1/payment/history', { params })
  },

  /**
   * Get available products/plans
   */
  async getProducts(params = {}) {
    return api.get('/v1/payment/products', { params })
  },

  /**
   * Switch subscription plan
   */
  async switchPlan(data) {
    return api.post('/v1/payment/switch-plan', data)
  },

  /**
   * Cancel membership
   */
  async cancelMembership() {
    return api.post('/v1/payment/cancel-membership')
  },

  /**
   * Restore membership
   */
  async restoreMembership() {
    return api.post('/v1/payment/restore-membership')
  },

  /**
   * Download invoice
   */
  async downloadInvoice(invoiceId) {
    return api.post('/v1/payment/download-invoice', { invoice_id: invoiceId }, {
      responseType: 'blob'
    })
  }
}
