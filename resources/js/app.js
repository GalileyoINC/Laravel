import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './components/App.vue'
import router from './router'
import './bootstrap'

// Import new components
import HomeBackground from './components/public-site/HomeBackground.vue'
import PhoneMockup from './components/PhoneMockup.vue'

// Create Pinia store
const pinia = createPinia()

// Create app
const app = createApp(App)
app.use(pinia)
app.use(router)

// Register global components
app.component('HomeBackground', HomeBackground)
app.component('PhoneMockup', PhoneMockup)

app.mount('#app')