import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
const appName = (document.querySelector('meta[name="app-name"]')?.getAttribute('content') || 'Laravel').toUpperCase().replace(/\s+/g, '-');

if (token) {
    window.axios.defaults.headers.common[appName + '-T'] = token.content;
} else {
    console.error('CSRF token not found');
}
