require('./bootstrap');

// Load jQuery for the frontend.
window.jQuery = require('jquery');

// Load Alpine for the backend (=Login/Registration).
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

