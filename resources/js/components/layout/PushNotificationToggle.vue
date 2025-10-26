<template>
    <div class="push-notification-toggle">
        <button
            v-if="isSupported"
            @click="toggleSubscription"
            :disabled="isSubscribing"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors"
            :class="isSubscribed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'"
        >
            <svg :class="{ 'animate-pulse': isSubscribing }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span>{{ isSubscribing ? 'Processing...' : isSubscribed ? 'Subscribed' : 'Subscribe' }}</span>
        </button>
        <div v-else class="text-sm text-gray-500">
            Push notifications not supported
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { usePushNotification } from '../../composables/usePushNotification';

const { isSupported, subscription, isSubscribing, subscribeToPush, unsubscribeFromPush, requestPermission } = usePushNotification();

const isSubscribed = computed(() => subscription.value !== null);

async function toggleSubscription() {
    try {
        console.log('Toggling subscription, current state:', isSubscribed.value);
        
        if (isSubscribed.value) {
            console.log('Unsubscribing...');
            await unsubscribeFromPush();
        } else {
            console.log('Subscribing...');
            // Request permission first
            const hasPermission = await requestPermission();
            console.log('Permission granted:', hasPermission);
            
            if (hasPermission) {
                console.log('Calling subscribeToPush...');
                await subscribeToPush();
                console.log('Subscribe completed');
            } else {
                console.log('Permission denied');
            }
        }
    } catch (error) {
        console.error('Error toggling subscription:', error);
        console.error('Error details:', error.message, error.stack);
        alert(`Failed to toggle push notification subscription: ${error.message}`);
    }
}

onMounted(() => {
    console.log('Push Notification Toggle initialized');
});
</script>

