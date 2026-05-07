/*
    QuickCut - Navbar Interactions
*/

document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('main-nav');
    if (!navbar) return;

    const navLinks = Array.from(document.querySelectorAll('.nav-link[data-nav-target]'));
    const navbarCollapse = document.getElementById('navbarNav');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const isWelcomePage = window.location.pathname.toLowerCase().includes('welcome.php');

    const setScrolledState = () => {
        navbar.classList.toggle('scrolled', window.scrollY > 24);
    };

    const closeMobileNav = () => {
        if (!navbarCollapse || !navbarToggler) return;
        if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
            navbarToggler.click();
        }
    };

    const setActive = (sectionId = 'hero') => {
        navLinks.forEach((link) => {
            const active = link.dataset.navTarget === sectionId;
            link.classList.toggle('active', active);
        });
    };

    if (isWelcomePage) {
        const sectionIds = ['hero', 'services', 'how-it-works'];
        const sections = sectionIds
            .map((id) => document.getElementById(id))
            .filter(Boolean);

        navLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                const sectionId = link.dataset.navTarget;
                const target = document.getElementById(sectionId);
                if (!target) return;
                event.preventDefault();
                const offset = navbar.offsetHeight + 10;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
                setActive(sectionId);
                closeMobileNav();
                history.replaceState(null, '', `#${sectionId}`);
            });
        });

        const updateActiveOnScroll = () => {
            let activeSection = 'hero';
            const viewportTrigger = window.scrollY + navbar.offsetHeight + 22;
            for (const section of sections) {
                if (viewportTrigger >= section.offsetTop) {
                    activeSection = section.id;
                }
            }
            setActive(activeSection);
        };

        window.addEventListener('scroll', updateActiveOnScroll);
        updateActiveOnScroll();
    }

    window.addEventListener('scroll', setScrolledState);
    setScrolledState();
});
