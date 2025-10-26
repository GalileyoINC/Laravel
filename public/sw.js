/* eslint-disable no-undef */
/* Service Worker for Push Notifications */

const CACHE_NAME = 'galileyo-v1';
const STATIC_CACHE_NAME = 'galileyo-static-v1';

// Install event
self.addEventListener('install', function (event) {
    console.log('[Service Worker] Install event');
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', function (event) {
    console.log('[Service Worker] Activate event');
    event.waitUntil(self.clients.claim());
});

// Push event
self.addEventListener('push', function (event) {
    console.log('[Service Worker] Push event received');

    let data = {};
    
    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data = {
                title: 'Galileyo',
                body: event.data.text(),
            };
        }
    }

    const options = {
        body: data.body || 'New notification',
        icon: data.icon || '/galileyo_new_logo.png',
        badge: '/badge.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/',
            dateOfArrival: Date.now(),
            primaryKey: data.id || '1',
        },
        tag: data.tag || 'galileyo-notification',
        requireInteraction: data.requireInteraction || false,
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Galileyo', options)
    );
});

// Notification click event
self.addEventListener('notificationclick', function (event) {
    console.log('[Service Worker] Notification click received');

    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true,
        }).then(function (clientList) {
            // If there's a client, focus on it
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }

            // If no client is found, open a new window
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Notification close event
self.addEventListener('notificationclose', function (event) {
    console.log('[Service Worker] Notification closed');
});
