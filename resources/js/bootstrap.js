import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: false, // Set to false for HTTP/WS connections
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: 'mt1',
});

// Debug WebSocket connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('🟢 WebSocket connected!');
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('🔴 WebSocket disconnected!');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.log('❌ WebSocket error:', error);
});

console.log('🔧 Echo initialized with config:', {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    scheme: import.meta.env.VITE_REVERB_SCHEME,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https'
});
