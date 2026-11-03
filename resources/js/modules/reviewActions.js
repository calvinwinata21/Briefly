/**
 * Designer Review Actions & HITL Interaction Module
 */
import { showToast } from './toast.js';

export function initReviewActions() {
    // Copy Caption Button
    const copyCaptionBtn = document.getElementById('copy-caption-btn');
    if (copyCaptionBtn) {
        copyCaptionBtn.addEventListener('click', () => {
            const hook = document.getElementById('caption-hook')?.innerText || '';
            const body = document.getElementById('caption-body')?.innerText || '';
            const cta = document.getElementById('caption-cta')?.innerText || '';
            const hashtags = document.getElementById('caption-hashtags')?.innerText || '';

            const fullCaption = `${hook}\n\n${body}\n\n${cta}\n\n${hashtags}`.trim();

            navigator.clipboard.writeText(fullCaption).then(() => {
                showToast('Caption Instagram berhasil disalin ke clipboard!', 'success');
                const origHtml = copyCaptionBtn.innerHTML;
                copyCaptionBtn.innerHTML = `
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tersalin!
                `;
                setTimeout(() => {
                    copyCaptionBtn.innerHTML = origHtml;
                }, 2000);
            }).catch(() => {
                showToast('Gagal menyalin caption.', 'error');
            });
        });
    }

    // Copy JSON Schema Button
    const copyJsonBtn = document.getElementById('copy-json-btn');
    if (copyJsonBtn) {
        copyJsonBtn.addEventListener('click', () => {
            const rawJson = document.getElementById('raw-json-data')?.textContent || '';
            if (rawJson) {
                navigator.clipboard.writeText(rawJson).then(() => {
                    showToast('Kontrak JSON berhasil disalin!', 'success');
                });
            }
        });
    }

    // Modal helpers
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Approve Modal triggers
    const openApproveBtn = document.getElementById('open-approve-modal-btn');
    const closeApproveBtn = document.getElementById('close-approve-modal-btn');
    const cancelApproveBtn = document.getElementById('cancel-approve-btn');

    if (openApproveBtn) openApproveBtn.addEventListener('click', () => openModal('approve-modal'));
    if (closeApproveBtn) closeApproveBtn.addEventListener('click', () => closeModal('approve-modal'));
    if (cancelApproveBtn) cancelApproveBtn.addEventListener('click', () => closeModal('approve-modal'));

    // Reject Modal triggers
    const openRejectBtn = document.getElementById('open-reject-modal-btn');
    const closeRejectBtn = document.getElementById('close-reject-modal-btn');
    const cancelRejectBtn = document.getElementById('cancel-reject-btn');

    if (openRejectBtn) openRejectBtn.addEventListener('click', () => openModal('reject-modal'));
    if (closeRejectBtn) closeRejectBtn.addEventListener('click', () => closeModal('reject-modal'));
    if (cancelRejectBtn) cancelRejectBtn.addEventListener('click', () => closeModal('reject-modal'));

    // Toggle Edit Mode
    const toggleEditBtn = document.getElementById('toggle-edit-mode-btn');
    const viewContainer = document.getElementById('draft-view-mode');
    const editContainer = document.getElementById('draft-edit-mode');
    const cancelEditBtn = document.getElementById('cancel-edit-mode-btn');

    if (toggleEditBtn && viewContainer && editContainer) {
        toggleEditBtn.addEventListener('click', () => {
            viewContainer.classList.add('hidden');
            editContainer.classList.remove('hidden');
            showToast('Mode Sunting HITL aktif. Anda dapat mengubah teks caption dan struktur slide.', 'info');
        });

        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', () => {
                editContainer.classList.add('hidden');
                viewContainer.classList.remove('hidden');
            });
        }
    }

    // Close modals on clicking backdrop
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.closest('.modal-container')?.classList.add('hidden');
                backdrop.closest('.modal-container')?.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
}
