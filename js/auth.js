// auth.js - central logout handler
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const anchor = e.target.closest && e.target.closest('a');
        if (!anchor) return;
        const href = anchor.getAttribute('href');
        if (!href) return;

        // Check for logout link by ID
        if (anchor.id === 'nav-logout') {
            try {
                sessionStorage.removeItem('quickcutCurrentUser');
                // The browser will then follow the href to logout.php
            } catch (err) {
                console.warn('Failed clearing session on logout', err);
            }
            return;
        }

        // Handle other navigation if needed
        if (href.includes('welcome.php') || href.includes('welcome.html')) {
            return;
        }
    }, false);
});
