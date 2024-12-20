import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Set up Pusher globally
window.Pusher = Pusher;

// Initialize Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Replace with your Pusher key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Replace with your Pusher cluster
    forceTLS: false,
});

console.log('Echo initialized:', window.Echo); // Debugging

