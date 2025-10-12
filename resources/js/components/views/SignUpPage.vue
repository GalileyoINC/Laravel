<template>
  <div class="min-h-screen bg-background dark:bg-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <div class="flex justify-center">
          <img src="/galileyo-icon.svg" alt="Galileyo" class="h-12 w-12" />
        </div>
        <h2 class="mt-6 text-3xl font-bold text-slate-900 dark:text-white">
          Create your account
        </h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
          Join Galileyo to stay informed and connected
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
        <!-- Name Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="first_name" class="block text-sm font-medium text-slate-900 dark:text-white">
              First Name
            </label>
            <input
              id="first_name"
              v-model="form.first_name"
              type="text"
              required
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
              placeholder="First Name"
            />
            <p v-if="errors.first_name" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.first_name }}
            </p>
          </div>

          <div>
            <label for="last_name" class="block text-sm font-medium text-slate-900 dark:text-white">
              Last Name
            </label>
            <input
              id="last_name"
              v-model="form.last_name"
              type="text"
              required
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
              placeholder="Last Name"
            />
            <p v-if="errors.last_name" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.last_name }}
            </p>
          </div>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-slate-900 dark:text-white">
            Email Address
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
            placeholder="Email Address"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
            {{ errors.email }}
          </p>
        </div>

        <!-- Password Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="password" class="block text-sm font-medium text-slate-900 dark:text-white">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
              placeholder="Password"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.password }}
            </p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-900 dark:text-white">
              Confirm Password
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400"
              placeholder="Confirm Password"
            />
            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.password_confirmation }}
            </p>
          </div>
        </div>

        <!-- Country and State -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="country" class="block text-sm font-medium text-slate-900 dark:text-white">
              Country
            </label>
            <select
              id="country"
              v-model="form.country"
              required
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            >
              <option value="">Select Country</option>
              <option value="US">United States</option>
              <option value="CA">Canada</option>
              <option value="GB">United Kingdom</option>
              <option value="AU">Australia</option>
              <option value="DE">Germany</option>
              <option value="FR">France</option>
              <option value="IT">Italy</option>
              <option value="ES">Spain</option>
              <option value="MX">Mexico</option>
              <option value="BR">Brazil</option>
              <option value="IN">India</option>
              <option value="CN">China</option>
              <option value="JP">Japan</option>
              <option value="KR">South Korea</option>
              <option value="RU">Russia</option>
              <option value="ZA">South Africa</option>
              <option value="EG">Egypt</option>
              <option value="NG">Nigeria</option>
              <option value="AR">Argentina</option>
              <option value="CL">Chile</option>
              <option value="CO">Colombia</option>
              <option value="PE">Peru</option>
              <option value="VE">Venezuela</option>
              <option value="EC">Ecuador</option>
              <option value="UY">Uruguay</option>
              <option value="PY">Paraguay</option>
              <option value="BO">Bolivia</option>
              <option value="GY">Guyana</option>
              <option value="SR">Suriname</option>
              <option value="FK">Falkland Islands</option>
              <option value="GF">French Guiana</option>
              <option value="GS">South Georgia and the South Sandwich Islands</option>
              <option value="BZ">Belize</option>
              <option value="CR">Costa Rica</option>
              <option value="GT">Guatemala</option>
              <option value="HN">Honduras</option>
              <option value="NI">Nicaragua</option>
              <option value="PA">Panama</option>
              <option value="SV">El Salvador</option>
              <option value="CU">Cuba</option>
              <option value="DO">Dominican Republic</option>
              <option value="HT">Haiti</option>
              <option value="JM">Jamaica</option>
              <option value="PR">Puerto Rico</option>
              <option value="TT">Trinidad and Tobago</option>
              <option value="BB">Barbados</option>
              <option value="BS">Bahamas</option>
              <option value="DM">Dominica</option>
              <option value="GD">Grenada</option>
              <option value="KN">Saint Kitts and Nevis</option>
              <option value="LC">Saint Lucia</option>
              <option value="VC">Saint Vincent and the Grenadines</option>
              <option value="AG">Antigua and Barbuda</option>
              <option value="AI">Anguilla</option>
              <option value="AW">Aruba</option>
              <option value="BM">Bermuda</option>
              <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
              <option value="CW">Curaçao</option>
              <option value="GL">Greenland</option>
              <option value="GP">Guadeloupe</option>
              <option value="KY">Cayman Islands</option>
              <option value="MF">Saint Martin</option>
              <option value="MQ">Martinique</option>
              <option value="MS">Montserrat</option>
              <option value="SX">Sint Maarten</option>
              <option value="TC">Turks and Caicos Islands</option>
              <option value="VG">British Virgin Islands</option>
              <option value="VI">U.S. Virgin Islands</option>
            </select>
            <p v-if="errors.country" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.country }}
            </p>
          </div>

          <div v-if="form.country === 'US'">
            <label for="state" class="block text-sm font-medium text-slate-900 dark:text-white">
              State
            </label>
            <select
              id="state"
              v-model="form.state"
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
            >
              <option value="">Select State</option>
              <option value="AL">Alabama</option>
              <option value="AK">Alaska</option>
              <option value="AZ">Arizona</option>
              <option value="AR">Arkansas</option>
              <option value="CA">California</option>
              <option value="CO">Colorado</option>
              <option value="CT">Connecticut</option>
              <option value="DE">Delaware</option>
              <option value="FL">Florida</option>
              <option value="GA">Georgia</option>
              <option value="HI">Hawaii</option>
              <option value="ID">Idaho</option>
              <option value="IL">Illinois</option>
              <option value="IN">Indiana</option>
              <option value="IA">Iowa</option>
              <option value="KS">Kansas</option>
              <option value="KY">Kentucky</option>
              <option value="LA">Louisiana</option>
              <option value="ME">Maine</option>
              <option value="MD">Maryland</option>
              <option value="MA">Massachusetts</option>
              <option value="MI">Michigan</option>
              <option value="MN">Minnesota</option>
              <option value="MS">Mississippi</option>
              <option value="MO">Missouri</option>
              <option value="MT">Montana</option>
              <option value="NE">Nebraska</option>
              <option value="NV">Nevada</option>
              <option value="NH">New Hampshire</option>
              <option value="NJ">New Jersey</option>
              <option value="NM">New Mexico</option>
              <option value="NY">New York</option>
              <option value="NC">North Carolina</option>
              <option value="ND">North Dakota</option>
              <option value="OH">Ohio</option>
              <option value="OK">Oklahoma</option>
              <option value="OR">Oregon</option>
              <option value="PA">Pennsylvania</option>
              <option value="RI">Rhode Island</option>
              <option value="SC">South Carolina</option>
              <option value="SD">South Dakota</option>
              <option value="TN">Tennessee</option>
              <option value="TX">Texas</option>
              <option value="UT">Utah</option>
              <option value="VT">Vermont</option>
              <option value="VA">Virginia</option>
              <option value="WA">Washington</option>
              <option value="WV">West Virginia</option>
              <option value="WI">Wisconsin</option>
              <option value="WY">Wyoming</option>
            </select>
            <p v-if="errors.state" class="mt-1 text-sm text-red-600 dark:text-red-400">
              {{ errors.state }}
            </p>
          </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="space-y-4">
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="accept_terms"
                v-model="form.accept_terms"
                type="checkbox"
                required
                class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="accept_terms" class="text-slate-700 dark:text-slate-300">
                I accept the
                <router-link to="/terms-of-service" class="text-cyan-500 hover:text-cyan-400 underline">
                  Terms of Service
                </router-link>
                and
                <router-link to="/privacy-policy" class="text-cyan-500 hover:text-cyan-400 underline">
                  Privacy Policy
                </router-link>
              </label>
              <p v-if="errors.accept_terms" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ errors.accept_terms }}
              </p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="after_eighteen"
                v-model="form.after_eighteen"
                type="checkbox"
                required
                class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="after_eighteen" class="text-slate-700 dark:text-slate-300">
                I am at least 18 years old
              </label>
              <p v-if="errors.after_eighteen" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ errors.after_eighteen }}
              </p>
            </div>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="rounded-lg bg-red-50 p-4 text-red-600 dark:bg-red-900/20 dark:text-red-400">
          {{ error }}
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-cyan-500 hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Creating Account...' : 'Create Account' }}
          </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Already have an account?
            <router-link to="/login" class="font-medium text-cyan-500 hover:text-cyan-400">
              Sign in
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { profileApi } from '../../api'

const router = useRouter()
const route = useRoute()

const form = reactive({
  first_name: '',
  last_name: '',
  email: route.query.email || '',
  password: '',
  password_confirmation: '',
  country: '',
  state: '',
  accept_terms: false,
  after_eighteen: false,
})

const errors = ref({})
const error = ref('')
const loading = ref(false)

const validateForm = () => {
  errors.value = {}

  if (!form.first_name.trim()) {
    errors.value.first_name = 'First name is required'
  }

  if (!form.last_name.trim()) {
    errors.value.last_name = 'Last name is required'
  }

  if (!form.email.trim()) {
    errors.value.email = 'Email is required'
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.value.email = 'Email is invalid'
  }

  if (!form.password) {
    errors.value.password = 'Password is required'
  } else if (form.password.length < 6) {
    errors.value.password = 'Password must be at least 6 characters'
  }

  if (!form.password_confirmation) {
    errors.value.password_confirmation = 'Password confirmation is required'
  } else if (form.password !== form.password_confirmation) {
    errors.value.password_confirmation = 'Passwords do not match'
  }

  if (!form.country) {
    errors.value.country = 'Country is required'
  }

  if (!form.accept_terms) {
    errors.value.accept_terms = 'You must accept the terms and conditions'
  }

  if (!form.after_eighteen) {
    errors.value.after_eighteen = 'You must be at least 18 years old'
  }

  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  try {
    loading.value = true
    error.value = ''

    await profileApi.signup(form)

    // Redirect to login page with success message
    router.push({
      path: '/login',
      query: { 
        message: 'Account created successfully! Please sign in.',
        email: form.email 
      }
    })
  } catch (err) {
    error.value = err.response?.data?.error?.message || 'Failed to create account'
    
    // Handle specific field errors
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  // Set default state if country is US
  if (form.country === 'US' && !form.state) {
    form.state = 'CA' // Default to California
  }
})
</script>

