/**
 * Dark / Light Mode Switcher Module
 */
import { showToast } from './toast.js';

export function initThemeToggle() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const sunIcon = document.getElementById('theme-icon-sun');
    const moonIcon = document.getElementById('theme-icon-moon');

    const STORAGE_KEY = 'briefly_theme';

    // Retrieve saved theme or default to dark
    function getCurrentTheme() {
        return localStorage.getItem(STORAGE_KEY) || 'dark';
    }

    function applyTheme(theme, notify = false) {
        const isDark = theme === 'dark';

        if (isDark) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            document.documentElement.setAttribute('data-theme', 'dark');
            if (sunIcon) sunIcon.classList.remove('hidden');
            if (moonIcon) moonIcon.classList.add('hidden');
            if (toggleBtn) {
                toggleBtn.setAttribute('title', 'Beralih ke Mode Terang');
                toggleBtn.classList.remove('text-indigo-600', 'bg-slate-100', 'border-slate-300');
                toggleBtn.classList.add('text-amber-400', 'bg-slate-800/80', 'border-slate-700');
            }
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            document.documentElement.setAttribute('data-theme', 'light');
            if (sunIcon) sunIcon.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (toggleBtn) {
                toggleBtn.setAttribute('title', 'Beralih ke Mode Gelap');
                toggleBtn.classList.remove('text-amber-400', 'bg-slate-800/80', 'border-slate-700');
                toggleBtn.classList.add('text-indigo-600', 'bg-slate-100', 'border-slate-300');
            }
        }

        localStorage.setItem(STORAGE_KEY, theme);

        if (notify) {
            showToast(isDark ? 'Mode Gelap (Dark Mode) diaktifkan' : 'Mode Terang (Light Mode) diaktifkan', 'info', 2000);
        }
    }

    // Apply on initial load
    applyTheme(getCurrentTheme(), false);

    // Click event handler
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const current = getCurrentTheme();
            const nextTheme = current === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme, true);
        });
    }

    // Synchronize across browser tabs
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY && e.newValue) {
            applyTheme(e.newValue, false);
        }
    });
}
