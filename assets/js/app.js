/* ============================================================
 * START: Utility Function
 * Purpose: Small helper to safely query single element.
 * Why: Avoid repeated null checks and improve readability.
 * ============================================================ */
function qs(selector) {
    return document.querySelector(selector);
}
/* ============================================================
 * END: Utility Function
 * ============================================================ */

/* ============================================================
 * START: Bootstrap Tooltip Initializer
 * Purpose: Enable Bootstrap tooltips globally.
 * Why: Required UX feature for compact icon controls.
 * ============================================================ */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
/* ============================================================
 * END: Bootstrap Tooltip Initializer
 * ============================================================ */

/* ============================================================
 * START: Sidebar Toggle Behaviors
 * Purpose: Handle desktop collapse and mobile open/close.
 * Why: Required responsive navigation interaction.
 * ============================================================ */
function initSidebarToggles() {
    const desktopToggle = qs('#desktopSidebarToggle');
    const mobileToggle = qs('#mobileSidebarToggle');

    if (desktopToggle) {
        desktopToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('macerti_sidebar_collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        });
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
    }

    if (localStorage.getItem('macerti_sidebar_collapsed') === '1') {
        document.body.classList.add('sidebar-collapsed');
    }
}
/* ============================================================
 * END: Sidebar Toggle Behaviors
 * ============================================================ */

/* ============================================================
 * START: Dark Mode Toggle
 * Purpose: Toggle dark theme class and persist preference.
 * Why: Modern UX personalization requirement.
 * ============================================================ */
function initDarkMode() {
    const darkToggle = qs('#darkModeToggle');
    const savedMode = localStorage.getItem('macerti_theme');

    if (savedMode === 'dark') {
        document.body.classList.add('dark-mode');
    }

    if (darkToggle) {
        darkToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const mode = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('macerti_theme', mode);
        });
    }
}
/* ============================================================
 * END: Dark Mode Toggle
 * ============================================================ */

/* ============================================================
 * START: Active Navigation Detection
 * Purpose: Highlight current nav item from URL path.
 * Why: Improves orientation and page context.
 * ============================================================ */
function initActiveNavDetection() {
    const path = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('[data-nav-key]');

    navLinks.forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && href.endsWith(path)) {
            link.classList.add('active');
        }
    });
}
/* ============================================================
 * END: Active Navigation Detection
 * ============================================================ */

/* ============================================================
 * START: Toast Notification Helper
 * Purpose: Programmatically show Bootstrap toasts.
 * Why: Shared notification pattern for future AJAX events.
 * ============================================================ */
function showToast(message, type) {
    const toastContainer = qs('#toastContainer');
    if (!toastContainer) {
        return;
    }

    const toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center text-bg-' + (type || 'primary') + ' border-0';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');

    toastEl.innerHTML = '<div class="d-flex">' +
        '<div class="toast-body"></div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
        '</div>';

    toastEl.querySelector('.toast-body').textContent = message;
    toastContainer.appendChild(toastEl);

    const bsToast = new bootstrap.Toast(toastEl, {delay: 3500});
    bsToast.show();

    toastEl.addEventListener('hidden.bs.toast', function () {
        toastEl.remove();
    });
}
window.showToast = showToast;
/* ============================================================
 * END: Toast Notification Helper
 * ============================================================ */

/* ============================================================
 * START: App Initializer
 * Purpose: Run all UI shell initializers once DOM is ready.
 * Why: Keep startup predictable and centralized.
 * ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    initTooltips();
    initSidebarToggles();
    initDarkMode();
    initActiveNavDetection();
});
/* ============================================================
 * END: App Initializer
 * ============================================================ */
