import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '2C77uk6Ujmqyc11uuAbx',
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: window.location.protocol === 'https:' ? 443 : 6001,
    wssPort: window.location.protocol === 'https:' ? 443 : 6001,
    disableStats: true,
    forceTLS: window.location.protocol === 'https:',
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content'),
        },
    },
    authEndpoint: '/broadcasting/auth',
});
