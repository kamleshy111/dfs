
    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    // Set up Pusher globally
    window.Pusher = Pusher;

    // Initialize Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'ba58f8359f6318f23ee1', // Replace with your Pusher key
        cluster: 'ap2', // Replace with your Pusher cluster
        forceTLS: false,
    });

    console.log('Echo initialized:', window.Echo); // Debugging

