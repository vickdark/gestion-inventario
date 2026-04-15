export function initSidebar() {
    const sidebarToggles = document.querySelectorAll('[data-toggle="sidebar-mini"]');
    const body = document.body;

    // Persistencia del modo mini
    if (localStorage.getItem('sidebar-mini') === 'true' && window.innerWidth > 991) {
        body.classList.add('sidebar-mini');
    }

    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-mini');
                localStorage.setItem('sidebar-mini', body.classList.contains('sidebar-mini'));
            }
        });
    });
}
