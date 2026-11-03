/**
 * Role Quick Switcher & App Navigation Helper
 */
export function initQuickSwitcher() {
    const trigger = document.getElementById('quick-role-trigger');
    const dropdown = document.getElementById('quick-role-dropdown');

    if (trigger && dropdown) {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Auto-dismiss session flash alerts after 5 seconds
    const flashAlerts = document.querySelectorAll('.session-flash-alert');
    flashAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
}
