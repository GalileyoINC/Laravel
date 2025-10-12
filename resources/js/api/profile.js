import { api } from './index'

export const profileApi = {
  // Get profile
  getProfile() {
    return api.get('/customer/get-profile')
  },

  // Signup
  signup(data) {
    return api.post('/default/signup', {
      first_name: data.first_name,
      last_name: data.last_name,
      email: data.email,
      password: data.password,
      country: data.country,
      state: data.state,
      iam18: data.accept_terms,
      iagree: data.after_eighteen,
      'from-new-site': true,
    })
  },

  // Update profile
  updateProfile(data) {
    return api.post('/customer/update-profile', data)
  },

  // Remove avatar
  removeAvatar() {
    return api.post('/customer/remove-avatar')
  },

  // Remove header
  removeHeader() {
    return api.post('/customer/remove-header')
  },

  // Change password
  changePassword(oldPassword, newPassword) {
    return api.post('/customer/change-password', {
      old_password: oldPassword,
      new_password: newPassword,
    })
  },

  // Update privacy
  updatePrivacy(data) {
    return api.post('/customer/update-privacy', {
      generalVisibility: data.memberDirectory === 'Public' ? 0 : 1,
      phoneVisibility: data.satellitePhoneNumber === 'Public' ? 0 : 1,
      addressVisibility: data.location === 'Public' ? 0 : 1,
    })
  },
}

