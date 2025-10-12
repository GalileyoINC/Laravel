import { defineStore } from 'pinia'
import { api } from '../api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token')
  }),

  getters: {
    isAuthenticated: (state) => !!state.token
  },

  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/auth/login', {
          email,
          password
        })

        if (response.data.status === 'success') {
          this.token = response.data.access_token
          this.user = response.data.user_profile
          localStorage.setItem('auth_token', this.token)
          localStorage.setItem('user_profile', JSON.stringify(this.user))
          return { success: true }
        } else {
          return { success: false, error: response.data.message }
        }
      } catch (error) {
        return { 
          success: false, 
          error: error.response?.data?.message || 'Login failed' 
        }
      }
    },

    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_profile')
    },

    async checkAuth() {
      const token = localStorage.getItem('auth_token')
      const userProfile = localStorage.getItem('user_profile')
      
      console.log('checkAuth:', { token, userProfile })
      
      if (token && userProfile) {
        this.token = token
        this.user = JSON.parse(userProfile)
        console.log('Auth restored:', { token: this.token, user: this.user })
      } else {
        console.log('No auth data found in localStorage')
      }
    }
  }
})
