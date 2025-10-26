<template>
    <div class="push-notification-toggle">
        <button
            v-if="isSupported"
            @click="toggleSubscription"
            :disabled="isSubscribing"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors"
            :class="isSubscribed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'"
        >
            <BellIcon :class="{ 'animate-pulse': isSubscribing }" />
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
import { BellIcon } from '@heroicons/vue/24/outline';

const { isSupported, subscription, isSubscribing, subscribeToPush, unsubscribeFromPush, requestPermission } = usePushNotification();

const isSubscribed = computed(() => subscription.value !== null);

async function toggleSubscription() {
    try {
        if (isSubscribed.value) {
            await unsubscribeFromPush();
        } else {
            // Request permission first
            const hasPermission = await requestPermission();
            
            if (hasPermission) {
                await subscribeToPush();
            }
        }
    } catch (error) {
        console.error('Error toggling subscription:', error);
        alert('Failed to toggle push notification subscription. Please try again.');
    }
}

onMounted(() => {
    console.log('Push Notification Toggle initialized');
});
</script>

<style scoped>
.push-notification-toggle {
    @apply p-4;
}
</style>

