/**
 * Briefly — Agentic AI Media Production Assistant
 * Main Application Script Entry Point
 */

// 1. Common Modules (Shared UI & Controls)
import { showToast } from './common/toast.js';
import { initThemeToggle } from './common/themeToggle.js';
import { initQuickSwitcher } from './common/quickSwitcher.js';

// 2. Admin Modules (Brand Guidelines RAG & AI Settings)
import { initGuidelines } from './admin/guidelines.js';
import { initSettings } from './admin/settings.js';

// 3. Designer Modules (HITL Slide Preview, Actions & Multi-Channel Export)
import { initSlidePreview } from './designer/slidePreview.js';
import { initReviewActions } from './designer/reviewActions.js';
import { initChannelExport } from './designer/channelExport.js';

// 4. Requester Modules (Brief Submission Form & Templates)
import { initBriefForm } from './requester/briefForm.js';

// Expose toast notification helper globally
window.showToast = showToast;

// Initialize theme as early as possible before DOM is fully rendered
initThemeToggle();

// Initialize role-specific and feature-specific modules on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    // Common / Global UI
    initQuickSwitcher();

    // Admin Features
    initGuidelines();
    initSettings();

    // Designer Features
    initSlidePreview();
    initReviewActions();
    initChannelExport();

    // Requester Features
    initBriefForm();
});
