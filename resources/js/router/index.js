import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import HomePage from '../components/views/HomePage.vue';
import LoginPage from '../components/views/LoginPage.vue';
import DashboardPage from '../components/views/DashboardPage.vue';
import ProfilePage from '../components/views/ProfilePage.vue';
import BookmarksPage from '../components/views/BookmarksPage.vue';
import BlogPage from '../components/views/BlogPage.vue';
import ContactPage from '../components/views/ContactPage.vue';
import FAQPage from '../components/views/FAQPage.vue';
import PrivacyPolicyPage from '../components/views/PrivacyPolicyPage.vue';
import TermsOfServicePage from '../components/views/TermsOfServicePage.vue';
import ProfileSettingsPage from '../components/views/ProfileSettingsPage.vue';
import AlertsMapPage from '../components/views/AlertsMapPage.vue';
import PaymentPage from '../components/views/PaymentPage.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/home',
    name: 'HomeAlt',
    component: HomePage
  },
  {
    path: '/sign-up',
    name: 'SignUp',
    component: LoginPage
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
    meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile/settings',
    name: 'ProfileSettings',
    component: ProfileSettingsPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: ProfilePage,
    meta: { requiresAuth: true }
  },
  {
    path: '/bookmarks',
    name: 'Bookmarks',
    component: BookmarksPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/blog',
    name: 'Blog',
    component: BlogPage
  },
  {
    path: '/contact',
    name: 'Contact',
    component: ContactPage
  },
  {
    path: '/faq',
    name: 'FAQ',
    component: FAQPage
  },
  {
    path: '/privacy-policy',
    name: 'PrivacyPolicy',
    component: PrivacyPolicyPage
  },
  {
    path: '/terms-of-service',
    name: 'TermsOfService',
    component: TermsOfServicePage
  },
  {
    path: '/alerts-map',
    name: 'AlertsMap',
    component: AlertsMapPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/payment',
    name: 'Payment',
    component: PaymentPage,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  
  // Check authentication status from localStorage
  authStore.checkAuth();
  
  // Debug logging (remove in production)
  console.log('Router guard:', {
    to: to.name,
    requiresAuth: to.meta.requiresAuth,
    hasToken: !!authStore.token,
    token: authStore.token
  });
  
  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    // Verify token is valid before allowing access
    if (!authStore.token) {
      console.log('No token found, redirecting to login');
      next({ name: 'Login', query: { redirect: to.fullPath } });
      return;
    }
    
    try {
      const { api } = await import('../api');
      await api.get('/v1/user'); // Test if token is valid
      console.log('Token is valid, allowing access');
      next();
      return;
    } catch (error) {
      console.error('Auth check error:', error);
      console.log('Token invalid, clearing auth and redirecting to login');
      authStore.logout();
      next({ name: 'Login', query: { redirect: to.fullPath } });
      return;
    }
  }
  
  // Check if route requires guest (not logged in)
  if (to.meta.requiresGuest) {
    if (!authStore.token) {
      console.log('No token, staying on login page');
      next();
      return;
    }
    
    // Verify token is still valid before redirecting
    try {
      const { api } = await import('../api');
      await api.get('/v1/user'); // Test if token is valid
      console.log('Token is valid, redirecting to dashboard');
      next({ name: 'Dashboard' });
      return;
    } catch (error) {
      console.log('Token invalid, clearing auth and staying on login');
      authStore.logout();
      next();
      return;
    }
  }
  
  console.log('Allowing navigation');
  next();
});

export default router;
