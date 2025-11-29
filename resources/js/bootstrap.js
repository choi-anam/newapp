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

// --- Reverb + Echo Initialization (migrated from inline dashboard script) ---
import Echo from 'laravel-echo';

// Dynamically inject Reverb client script if not already loaded
function ensureReverbClient(callback) {
    if (window.Reverb) {
        callback();
        return;
    }
    const existing = document.querySelector('script[data-reverb-client]');
    if (existing) {
        existing.addEventListener('load', () => callback());
        return;
    }
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/@laravel/reverb/dist/reverb.js';
    s.async = true;
    s.setAttribute('data-reverb-client', 'true');
    s.onload = () => callback();
    document.head.appendChild(s);
}

function initEcho() {
    if (!window.Reverb) {
        console.warn('Reverb client not loaded yet.');
        return;
    }
    if (window.Echo && window.Echo.connector) {
        return; // already initialized
    }
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: (document.querySelector('meta[name="reverb-key"]')?.getAttribute('content')) || 'local',
        wsHost: (document.querySelector('meta[name="reverb-host"]')?.getAttribute('content')) || window.location.hostname,
        wsPort: parseInt((document.querySelector('meta[name="reverb-port"]')?.getAttribute('content')) || '8080', 10),
        wssPort: parseInt((document.querySelector('meta[name="reverb-port"]')?.getAttribute('content')) || '8080', 10),
        forceTLS: false,
        enabledTransports: ['ws'],
    });

    // Subscribe to channel for online users updates
    try {
        window.Echo.channel('online-users')
            .listen('.OnlineUsersUpdated', (e) => {
                const users = e.users || [];
                const listEl = document.getElementById('online-users-list');
                const emptyEl = document.getElementById('online-users-empty');
                const updatedEl = document.getElementById('online-users-updated');
                if (!listEl || !emptyEl) return;
                listEl.innerHTML = '';
                if (users.length === 0) {
                    emptyEl.textContent = 'Tidak ada user online saat ini.';
                    emptyEl.classList.remove('d-none');
                    listEl.classList.add('d-none');
                } else {
                    emptyEl.classList.add('d-none');
                    listEl.classList.remove('d-none');
                    users.forEach(u => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = `<div><strong>${u.name}</strong> <small class='text-muted'>${u.email || ''}</small></div>`;
                        listEl.appendChild(li);
                    });
                }
                if (updatedEl) {
                    updatedEl.textContent = 'Diperbarui (WebSocket): ' + new Date().toLocaleTimeString();
                }
            });
    } catch (err) {
        console.warn('Echo channel subscription failed', err);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    ensureReverbClient(initEcho);
});
