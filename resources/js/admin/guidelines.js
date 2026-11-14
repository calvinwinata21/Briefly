/**
 * Admin Brand Guidelines & RAG Knowledge Store Interactive Module
 */
import { showToast } from '../common/toast.js';

export function initGuidelines() {
    const primaryPicker = document.querySelector('input[type="color"][name="primary_color_picker"]') || document.querySelectorAll('input[type="color"]')[0];
    const primaryHexInput = document.querySelector('input[name="primary_color_hex"]');

    const secondaryPicker = document.querySelector('input[type="color"][name="secondary_color_picker"]') || document.querySelectorAll('input[type="color"]')[1];
    const secondaryHexInput = document.querySelector('input[name="secondary_color_hex"]');

    // Sync primary color
    if (primaryPicker && primaryHexInput) {
        primaryPicker.addEventListener('input', (e) => {
            primaryHexInput.value = e.target.value.toUpperCase();
        });

        primaryHexInput.addEventListener('input', (e) => {
            if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                primaryPicker.value = e.target.value;
            }
        });
    }

    // Sync secondary color
    if (secondaryPicker && secondaryHexInput) {
        secondaryPicker.addEventListener('input', (e) => {
            secondaryHexInput.value = e.target.value.toUpperCase();
        });

        secondaryHexInput.addEventListener('input', (e) => {
            if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                secondaryPicker.value = e.target.value;
            }
        });
    }

    // Copy chunk text helper
    const chunkBadges = document.querySelectorAll('.rag-chunk-card [data-chunk-text]');
    chunkBadges.forEach(badge => {
        badge.addEventListener('click', () => {
            const text = badge.getAttribute('data-chunk-text');
            if (text) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Teks chunk vektor berhasil disalin!', 'success');
                });
            }
        });
    });
}
