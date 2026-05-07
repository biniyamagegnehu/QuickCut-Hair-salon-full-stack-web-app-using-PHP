/* 
    QuickCut - Modern Homepage Interactions
*/

document.addEventListener('DOMContentLoaded', () => {
    // Navbar Scroll Effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth Scroll for Nav Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Stats Animation (Counters)
    const stats = document.querySelectorAll('.stat-number');
    const statsSection = document.querySelector('.hero-stats');
    
    let animated = false;
    const animateStats = () => {
        if (!statsSection) return;
        
        const sectionTop = statsSection.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        
        if (sectionTop < windowHeight && !animated) {
            stats.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                let count = 0;
                const increment = target / 50;
                
                const updateCount = () => {
                    if (count < target) {
                        count += increment;
                        stat.innerText = Math.ceil(count) + (stat.getAttribute('data-suffix') || '');
                        setTimeout(updateCount, 30);
                    } else {
                        stat.innerText = target + (stat.getAttribute('data-suffix') || '');
                    }
                };
                updateCount();
            });
            animated = true;
        }
    };

    window.addEventListener('scroll', animateStats);
    animateStats(); // Run once on load

    // Hover effect for feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            // Add any specific JS-driven animation if needed
        });
    });

    // Refresh Queue logic (if needed, but already handled in welcome.php logic usually)
    const refreshBtn = document.getElementById('refresh-queue');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', (e) => {
            // Add a spinning animation to the icon
            const icon = refreshBtn.querySelector('i');
            icon.classList.add('fa-spin');
            setTimeout(() => {
                icon.classList.remove('fa-spin');
            }, 1000);
        });
    }
});
