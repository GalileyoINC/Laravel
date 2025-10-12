import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import App from './components/App.vue'
import './bootstrap'

// Import views
import HomePage from './components/views/HomePage.vue'
import LoginPage from './components/views/LoginPage.vue'
import DashboardPage from './components/views/DashboardPage.vue'
import ProfilePage from './components/views/ProfilePage.vue'
import BookmarksPage from './components/views/BookmarksPage.vue'
import BlogPage from './components/views/BlogPage.vue'
import ContactPage from './components/views/ContactPage.vue'
import FAQPage from './components/views/FAQPage.vue'
import PrivacyPolicyPage from './components/views/PrivacyPolicyPage.vue'
import TermsOfServicePage from './components/views/TermsOfServicePage.vue'
import AlertsMapPage from './components/views/AlertsMapPage.vue'

// Import new components
import HomeBackground from './components/public-site/HomeBackground.vue'
import PhoneMockup from './components/PhoneMockup.vue'

// Create Pinia store
const pinia = createPinia()

// Create router
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: HomePage },
    { path: '/home', name: 'home-alt', component: HomePage },
    { path: '/login', name: 'login', component: LoginPage },
    { path: '/sign-up', name: 'sign-up', component: LoginPage }, // Temporary redirect to login
    { path: '/dashboard', name: 'dashboard', component: DashboardPage },
    { path: '/profile', name: 'profile', component: ProfilePage },
    { path: '/bookmarks', name: 'bookmarks', component: BookmarksPage },
    { path: '/blog', name: 'blog', component: BlogPage },
    { path: '/contact', name: 'contact', component: ContactPage },
    { path: '/faq', name: 'faq', component: FAQPage },
    { path: '/privacy-policy', name: 'privacy-policy', component: PrivacyPolicyPage },
    { path: '/terms-of-service', name: 'terms-of-service', component: TermsOfServicePage },
    { path: '/alerts-map', name: 'alerts-map', component: AlertsMapPage },
  ]
})

// Create app
const app = createApp(App)
app.use(pinia)
app.use(router)

// Register global components
app.component('HomeBackground', HomeBackground)
app.component('PhoneMockup', PhoneMockup)

app.mount('#app')