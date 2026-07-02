document.addEventListener('DOMContentLoaded', () => {
    initializeSidebar();
    initializeTouchedFields();
});

// -----------------------------------------------------------------------------
// Sidebar / navigation
// -----------------------------------------------------------------------------
function initializeSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const app = document.getElementById('app');
    const mainContent = document.querySelector('#app > .flex-1');

    const collapseSidebar = () => {
        if (window.innerWidth <= 768) {
            sidebar?.classList.remove('mobile-expanded');
            sidebar?.classList.add('mobile-collapsed');
            document.body.classList.remove('overflow-hidden');
        }
    };

    const closeSidebar = () => {
        if (window.innerWidth <= 768) {
            collapseSidebar();
        }
    };

    const expandSidebar = () => {
        if (window.innerWidth <= 768) {
            sidebar?.classList.remove('mobile-collapsed');
            sidebar?.classList.add('mobile-expanded');
            document.body.classList.add('overflow-hidden');
        }
    };

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                if (sidebar?.classList.contains('mobile-expanded')) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            } else {
                app?.classList.toggle('sidebar-collapsed');
                const isCollapsed = app?.classList.contains('sidebar-collapsed');
                sessionStorage.setItem('sidebar-collapsed', String(isCollapsed));
            }
        });
    }

    if (mainContent) {
        mainContent.addEventListener('click', () => {
            if (window.innerWidth <= 768 && sidebar?.classList.contains('mobile-expanded')) {
                collapseSidebar();
            }
        });
    }

    document.querySelectorAll('.sidebar-link').forEach((link) => {
        link.addEventListener('click', (event) => {
            const parentItem = link.closest('.sidebar-item');
            const hasSubmenu = parentItem?.querySelector('.sidebar-submenu');

            if (hasSubmenu) {
                event.preventDefault();
                const wasExpanded = parentItem?.classList.contains('expanded');
                document.querySelectorAll('.sidebar-item').forEach((item) => {
                    if (item !== parentItem) {
                        item.classList.remove('expanded');
                    }
                });
                parentItem?.classList.toggle('expanded', !wasExpanded);
                return;
            }

            document.querySelectorAll('.sidebar-link').forEach((item) => item.classList.remove('active'));
            link.classList.add('active');

            if (window.innerWidth <= 768) {
                closeSidebar();
            }

            const href = link.getAttribute('href');
            if (href && href.startsWith('#')) {
                const target = document.querySelector(href);
                if (target) {
                    event.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    window.addEventListener('resize', handleSidebarResize);
    handleSidebarResize();
}

function handleSidebarResize() {
    const sidebar = document.getElementById('sidebar');
    const app = document.getElementById('app');

    if (window.innerWidth > 768) {
        sidebar?.classList.remove('mobile-expanded');
        sidebar?.classList.remove('mobile-collapsed');
        document.body.classList.remove('overflow-hidden');

        const wasCollapsed = sessionStorage.getItem('sidebar-collapsed') === 'true';
        if (wasCollapsed) {
            app?.classList.add('sidebar-collapsed');
        } else {
            app?.classList.remove('sidebar-collapsed');
        }
    } else {
        app?.classList.remove('sidebar-collapsed');
        sidebar?.classList.remove('collapsed');
        sidebar?.classList.remove('show');
        if (!sidebar?.classList.contains('mobile-expanded') && !sidebar?.classList.contains('mobile-collapsed')) {
            sidebar?.classList.add('mobile-collapsed');
        }
        document.body.classList.remove('overflow-hidden');
    }
}

// -----------------------------------------------------------------------------
// Form validation helpers
// -----------------------------------------------------------------------------
function initializeTouchedFields() {
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach((field) => {
        const markTouched = () => field.classList.add('touched');
        field.addEventListener('blur', markTouched);
        field.addEventListener('change', markTouched);
    });
}
