import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Laravel Echo for real-time features (only if available)
if (import.meta.env.VITE_REVERB_APP_KEY) {
    import('laravel-echo').then(({ default: Echo }) => {
        import('pusher-js').then((Pusher) => {
            window.Pusher = Pusher.default;
            
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: import.meta.env.VITE_REVERB_APP_KEY,
                wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
                wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
                forceTLS: false,
                enabledTransports: ['ws', 'wss'],
                disabledTransports: ['sockjs'],
                authEndpoint: '/broadcasting/auth',
            });
            
            console.log('✅ Laravel Echo initialized for real-time chat');
        }).catch(err => {
            console.warn('⚠️ Pusher.js not loaded:', err);
        });
    }).catch(err => {
        console.warn('⚠️ Laravel Echo not loaded:', err);
    });
} else {
    console.warn('⚠️ Laravel Reverb not configured - real-time features disabled');
}