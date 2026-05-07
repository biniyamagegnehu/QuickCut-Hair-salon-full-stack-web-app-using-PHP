/*
    QuickCut - Home Page Specific Interactions
*/

document.addEventListener('DOMContentLoaded', () => {
    const hero = document.getElementById('hero');
    if (!hero) return;

    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card) => {
        card.addEventListener('mouseenter', () => card.classList.add('is-hovered'));
        card.addEventListener('mouseleave', () => card.classList.remove('is-hovered'));
    });
});
