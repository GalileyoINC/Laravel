import { ref, onMounted } from 'vue';

export function usePushNotification() {
    const isSupported = ref(false);
    const subscription = ref(null);
    const isSubscribing = ref(false);
    const publicKey = ref(null);

    // Convert VAPID public key to Uint8Array
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // Register service worker
    async function registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/',
                    updateViaCache: 'none',
                });

                console.log('Service Worker registered:', registration);
                return registration;
            } catch (error) {
                console.error('Error registering service worker:', error);
                throw error;
            }
        } else {
            throw new Error('Service Workers are not supported');
        }
    }

    // Subscribe to push notifications
    async function subscribeToPush() {
        isSubscribing.value = true;

        try {
            if (!isSupported.value) {
                throw new Error('Push notifications are not supported');
            }

            // Get service worker registration
            const registration = await navigator.serviceWorker.ready;

            // Check for existing subscription
            const existingSubscription = await registration.pushManager.getSubscription();

            if (existingSubscription) {
                subscription.value = existingSubscription;
                await saveSubscription(existingSubscription);
                isSubscribing.value = false;
                return;
            }

            // Subscribe
            const sub = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(publicKey.value),
            });

            subscription.value = sub;
            await saveSubscription(sub);

            return sub;
        } catch (error) {
            console.error('Error subscribing to push notifications:', error);
            throw error;
        } finally {
            isSubscribing.value = false;
        }
    }

    // Unsubscribe from push notifications
    async function unsubscribeFromPush() {
        isSubscribing.value = true;

        try {
            if (!subscription.value) {
                console.log('No subscription to unsubscribe');
                return;
            }

            const success = await subscription.value.unsubscribe();

            if (success) {
                subscription.value = null;
                await removeSubscription();
                console.log('Unsubscribed from push notifications');
            }

            return success;
        } catch (error) {
            console.error('Error unsubscribing from push notifications:', error);
            throw error;
        } finally {
            isSubscribing.value = false;
        }
    }

    // Save subscription to backend
    async function saveSubscription(sub) {
        try {
            // Get auth token from localStorage
            const token = localStorage.getItem('auth_token');
            
            const response = await fetch('/api/v1/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(sub.toJSON()),
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Failed to save subscription:', errorData);
                throw new Error('Failed to save subscription to backend');
            }

            const result = await response.json();
            console.log('Subscription saved to backend:', result);
        } catch (error) {
            console.error('Error saving subscription:', error);
            throw error;
        }
    }

    // Remove subscription from backend
    async function removeSubscription() {
        try {
            const endpoint = subscription.value?.endpoint;

            if (!endpoint) {
                return;
            }

            // Get auth token from localStorage
            const token = localStorage.getItem('auth_token');

            const response = await fetch('/api/v1/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ endpoint }),
            });

            if (!response.ok) {
                throw new Error('Failed to remove subscription from backend');
            }

            console.log('Subscription removed from backend');
        } catch (error) {
            console.error('Error removing subscription:', error);
        }
    }

    // Request permission
    async function requestPermission() {
        try {
            if (!('Notification' in window)) {
                throw new Error('This browser does not support notifications');
            }

            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                console.log('Notification permission granted');
                return true;
            } else {
                console.log('Notification permission denied');
                return false;
            }
        } catch (error) {
            console.error('Error requesting permission:', error);
            throw error;
        }
    }

    // Check support
    async function checkSupport() {
        try {
            const hasSW = 'serviceWorker' in navigator;
            const hasPush = 'PushManager' in window;
            const hasNotifications = 'Notification' in window;

            isSupported.value = hasSW && hasPush && hasNotifications;

            if (isSupported.value) {
                // Get public key from backend
                publicKey.value = getPublicKey();
            }

            return isSupported.value;
        } catch (error) {
            console.error('Error checking support:', error);
            return false;
        }
    }

    // Get public key from environment or config
    function getPublicKey() {
        // This should be set in your .env file and made available to the frontend
        // For now, we'll get it from a meta tag or config
        const metaTag = document.querySelector('meta[name="vapid-public-key"]');
        
        if (metaTag) {
            return metaTag.content;
        }

        // Fallback: you should set this in your Laravel config and make it available
        return import.meta.env.VITE_VAPID_PUBLIC_KEY || '';
    }

    // Initialize on mount
    onMounted(async () => {
        await checkSupport();

        if (isSupported.value) {
            await registerServiceWorker();

            // Get existing subscription
            if ('serviceWorker' in navigator) {
                try {
                    const registration = await navigator.serviceWorker.ready;
                    const sub = await registration.pushManager.getSubscription();
                    subscription.value = sub;
                } catch (error) {
                    console.error('Error getting existing subscription:', error);
                }
            }
        }
    });

    return {
        isSupported,
        subscription,
        isSubscribing,
        subscribeToPush,
        unsubscribeFromPush,
        requestPermission,
        checkSupport,
    };
}

