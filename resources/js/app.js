import { initThemeToggle } from './modules/themeToggle.js';
import { initBriefForm } from './modules/briefForm.js';
import { initSlidePreview } from './modules/slidePreview.js';
import { initReviewActions } from './modules/reviewActions.js';
import { initQuickSwitcher } from './modules/quickSwitcher.js';

// Initialize theme as early as possible
initThemeToggle();

document.addEventListener('DOMContentLoaded', () => {
    initQuickSwitcher();
    initBriefForm();
    initSlidePreview();
    initReviewActions();
});
