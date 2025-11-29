<button 
    id="themeToggle" 
    class="btn btn-sm btn-outline-secondary theme-toggle-btn" 
    title="Toggle dark mode"
    aria-label="Toggle dark mode"
>
    <i class="bi bi-moon-stars"></i>
</button>

<style>
    .theme-toggle-btn {
        position: relative;
        transition: all 0.3s ease;
    }

    .theme-toggle-btn:hover {
        transform: scale(1.1);
    }

    .theme-toggle-btn i {
        transition: transform 0.3s ease;
    }

    .theme-toggle-btn:active i {
        transform: rotate(20deg);
    }

    @media (prefers-color-scheme: dark) {
        .theme-toggle-btn {
            border-color: #666;
            color: #ffc107;
        }

        .theme-toggle-btn:hover {
            border-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;

        // Get current theme preference
        const currentTheme = localStorage.getItem('theme') || 
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        // Apply theme on load
        applyTheme(currentTheme);

        // Toggle theme on button click
        themeToggle.addEventListener('click', function() {
            const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' 
                ? 'light' 
                : 'dark';
            
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function applyTheme(theme) {
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            
            if (theme === 'dark') {
                html.setAttribute('data-bs-theme', 'dark');
                html.style.colorScheme = 'dark';
                if (toggle) {
                    toggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
                    toggle.title = 'Switch to light mode';
                }
            } else {
                html.removeAttribute('data-bs-theme');
                html.style.colorScheme = 'light';
                if (toggle) {
                    toggle.innerHTML = '<i class="bi bi-moon-stars"></i>';
                    toggle.title = 'Switch to dark mode';
                }
            }
        }
    });
</script>
