import axios from 'axios'

// Use relative /api so it works with Vite proxy in dev and Nginx in Docker/prod
const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
  }
})

// Add request interceptor to include auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Add response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_profile')
      
      // Only redirect if not already on login page
      if (window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }
    return Promise.reject(error)
  }
)

export default api
export { api }

// Export all API modules
export { feedApi } from './feed'
export { profileApi } from './profile'
export { bookmarkApi } from './bookmark'
export { commentApi } from './comment'
export { searchApi } from './search'
export { alertMapApi } from './alertMap'
export { paymentApi } from './payment'
