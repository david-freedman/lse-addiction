export function initCountdown(expiresAt, elementId = 'countdown') {
    function updateCountdown() {
        const now = Math.floor(Date.now() / 1000);
        const remaining = expiresAt - now;

        const element = document.getElementById(elementId);
        if (!element) return;

        if (remaining <= 0) {
            element.textContent = 'закінчився';
            return;
        }

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        element.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        setTimeout(updateCountdown, 1000);
    }

    updateCountdown();
}

export function initResendCountdown(nextResendAt, buttonId = 'resend-button') {
    const button = document.getElementById(buttonId);
    if (!button) return;

    function updateResendCountdown() {
        const now = Math.floor(Date.now() / 1000);
        const remaining = nextResendAt - now;

        if (remaining <= 0) {
            button.disabled = false;
            button.textContent = button.dataset.originalText || 'Відправити код знову';
            return;
        }

        button.disabled = true;
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        button.textContent = `Можна повторно відправити через ${minutes}:${seconds.toString().padStart(2, '0')}`;

        setTimeout(updateResendCountdown, 1000);
    }

    if (!button.dataset.originalText) {
        button.dataset.originalText = button.textContent;
    }

    updateResendCountdown();
}

window.initCountdown = initCountdown;
window.initResendCountdown = initResendCountdown;
