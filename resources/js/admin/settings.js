/**
 * Admin AI Agent Configuration & Prompt Template Module
 */
import { showToast } from '../common/toast.js';

export function initSettings() {
    const promptPre = document.querySelector('pre');
    if (!promptPre) return;

    // Allow clicking on prompt template box to copy
    promptPre.style.cursor = 'pointer';
    promptPre.setAttribute('title', 'Klik untuk menyalin system prompt template');

    promptPre.addEventListener('click', () => {
        const text = promptPre.textContent || '';
        if (text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('System prompt template berhasil disalin ke clipboard!', 'success');
            });
        }
    });
}
