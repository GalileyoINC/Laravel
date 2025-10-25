import { api } from './index'

export const alertMapApi = {
  /**
   * Get alerts with map data
   * @param {Object} params - Query parameters
   * @param {number} params.limit - Number of alerts to fetch
   * @param {number} params.offset - Offset for pagination
   * @param {string} params.severity - Filter by severity (critical, high, medium, low)
   * @param {string} params.category - Filter by category
   * @param {Object} params.bounds - Geographic bounds
   * @param {number} params.bounds.north - North latitude
   * @param {number} params.bounds.south - South latitude
   * @param {number} params.bounds.east - East longitude
   * @param {number} params.bounds.west - West longitude
   * @param {Object} params.filter - Additional filters
   * @returns {Promise} API response
   */
  getAlertsWithMap(params = {}) {
    return api.post('/v1/product/alerts/map', params)
  },

  /**
   * Get alerts within geographic bounds
   * @param {Object} bounds - Geographic bounds
   * @param {number} limit - Number of alerts to fetch
   * @returns {Promise} API response
   */
  getAlertsInBounds(bounds, limit = 20) {
    return this.getAlertsWithMap({
      bounds,
      limit,
      filter: { active_only: true }
    })
  },

  /**
   * Get alerts by severity
   * @param {string} severity - Severity level
   * @param {number} limit - Number of alerts to fetch
   * @returns {Promise} API response
   */
  getAlertsBySeverity(severity, limit = 20) {
    return this.getAlertsWithMap({
      severity,
      limit,
      filter: { active_only: true }
    })
  },

  /**
   * Get alerts by category
   * @param {string} category - Alert category
   * @param {number} limit - Number of alerts to fetch
   * @returns {Promise} API response
   */
  getAlertsByCategory(category, limit = 20) {
    return this.getAlertsWithMap({
      category,
      limit,
      filter: { active_only: true }
    })
  }
}
