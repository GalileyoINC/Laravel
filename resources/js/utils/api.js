/**
 * Get the API base URL
 * Uses relative URL for production, allows override with VITE_API_URL for development
 */
export function getApiUrl() {
  // In production, use relative URL (same domain)
  // In development, allow override with VITE_API_URL
  return import.meta.env.VITE_API_URL || ''
}

/**
 * Build full API URL
 * @param {string} path - API path (e.g., '/api/news/last')
 * @returns {string} Full API URL
 */
export function buildApiUrl(path) {
  const baseUrl = getApiUrl()
  // Remove leading slash from path if baseUrl is empty (relative URL)
  const cleanPath = !baseUrl && path.startsWith('/') ? path : path
  return `${baseUrl}${cleanPath}`
}
