import { createRouter, createWebHistory } from 'vue-router';
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

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardPage
  },
  {
    path: '/profile/settings',
    name: 'ProfileSettings',
    component: ProfileSettingsPage
  },
  {
    path: '/profile',
    name: 'Profile',
    component: ProfilePage
  },
  {
    path: '/bookmarks',
    name: 'Bookmarks',
    component: BookmarksPage
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
    component: AlertsMapPage
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
