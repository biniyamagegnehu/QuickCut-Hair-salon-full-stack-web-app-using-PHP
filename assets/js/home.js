/* 
    QuickCut - Home Page Specific Interactions
*/

document.addEventListener('DOMContentLoaded', () => {
    // Reveal animations on scroll
    const revealElements = () => {
        const elements = document.querySelectorAll('.fade-up, .fade-right');
        const windowHeight = window.innerHeight;
        
        elements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                el.classList.add('active');
            }
        });
    };
    
    window.addEventListener('scroll', revealElements);
    revealElements(); // Initial check
    
    // Stats Counter Animation (if applicable)
    const counters = document.querySelectorAll('.stat-number');
    if (counters.length > 0) {
        const options = {
            threshold: 0.5
        };
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 2000;
                    const step = target / (duration / 16);
                    let current = 0;
                    
                    const update = () => {
                        current += step;
                        if (current < target) {
                            counter.innerText = Math.floor(current);
                            requestAnimationFrame(update);
                        } else {
                            counter.innerText = target + (counter.getAttribute('data-suffix') || '');
                        }
                    };
                    update();
                    observer.unobserve(counter);
                }
            });
        }, options);
        
        counters.forEach(counter => observer.observe(counter));
    }
});
