// QuickCut - Welcome page interactions

document.addEventListener('DOMContentLoaded', () => {
    const isWelcomePage = window.location.pathname.toLowerCase().includes('welcome.php');
    if (!isWelcomePage) return;

    const waitingCount = document.getElementById('waiting-count');
    const nextSlotTime = document.getElementById('next-slot-time');
    if (!waitingCount || !nextSlotTime) return;

    let currentWaiting = Number.parseInt(waitingCount.textContent, 10) || 3;

    setInterval(() => {
        const nextValue = Math.max(1, currentWaiting + (Math.random() > 0.5 ? 1 : -1));
        currentWaiting = nextValue;
        waitingCount.textContent = String(nextValue);
    }, 16000);
});