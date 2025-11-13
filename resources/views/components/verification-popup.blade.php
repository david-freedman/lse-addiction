<div id="popup-code" aria-hidden="true" class="popup popup--code">
    <div class="popup__wrapper">
        <div class="popup__content">
            <div class="code-popup" id="popupCodeForm">
                <div class="code-popup__title">
                    Введіть код з E-mail
                </div>
                <div class="code-popup__body">
                    <input type="text" inputmode="numeric" pattern="[0-9]" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="text" inputmode="numeric" pattern="[0-9]" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="text" inputmode="numeric" pattern="[0-9]" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="text" inputmode="numeric" pattern="[0-9]" class="popup-code-input" maxlength="1" autocomplete="off">
                </div>
                <div class="code-popup__error" id="popupCodeError" style="display: none; color: #e7000b; margin-bottom: 1rem; text-align: center;"></div>

                <button type="button" class="code-popup__button button button--fill" id="popupCodeSubmit">
                    Продовжити
                </button>

                <div class="code-popup__resend-error" id="popupResendError" style="display: none; color: #e7000b; text-align: center; font-size: 0.875rem;"></div>

                <div class="code-popup__info" id="popupCodeInfo" style="text-align: center; font-size: 0.875rem; color: #666;"></div>

                <div class="code-popup__resend" style="text-align: center;">
                    <button type="button" class="code-popup__resend-button" id="popupResendButton" style="background: none; border: none; color: #007bff; cursor: pointer; font-size: 0.875rem; text-decoration: underline; padding: 0.5rem;">
                        Відправити код знову
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popup-code');
    if (!popup) return;

    const inputs = document.querySelectorAll('.popup-code-input');
    const submitBtn = document.getElementById('popupCodeSubmit');
    const errorDiv = document.getElementById('popupCodeError');

    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const digits = pastedData.replace(/\D/g, '').split('').slice(0, 4);

            digits.forEach((digit, i) => {
                if (inputs[i]) {
                    inputs[i].value = digit;
                }
            });

            if (digits.length > 0) {
                const lastIndex = Math.min(digits.length, inputs.length - 1);
                inputs[lastIndex].focus();
            }
        });
    });

    submitBtn.addEventListener('click', async function(e) {
        e.preventDefault();

        const code = Array.from(inputs).map(input => input.value).join('');

        if (code.length !== 4) {
            errorDiv.textContent = 'Будь ласка, введіть повний 4-значний код';
            errorDiv.style.display = 'block';
            return;
        }

        errorDiv.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Перевірка...';

        try {
            const alpineComponent = window.Alpine.$data(document.querySelector('[x-data]'));

            if (alpineComponent && typeof alpineComponent.verifyCode === 'function') {
                await alpineComponent.verifyCode(code);
            } else {
                throw new Error('Помилка верифікації');
            }
        } catch (error) {
            errorDiv.textContent = error.message || 'Невірний код верифікації';
            errorDiv.style.display = 'block';

            inputs.forEach(input => input.value = '');
            inputs[0].focus();
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Продовжити';
        }
    });

    const resendBtn = document.getElementById('popupResendButton');
    const infoDiv = document.getElementById('popupCodeInfo');
    const resendErrorDiv = document.getElementById('popupResendError');
    let updateInterval = null;

    function getAlpineComponent() {
        const element = document.querySelector('[x-data]');
        return element ? window.Alpine.$data(element) : null;
    }

    function updatePopupUI() {
        const component = getAlpineComponent();
        if (!component) return;

        if (component.codeExpiresAt) {
            const expiry = component.getCodeExpiry();
            if (expiry && expiry !== '0:00') {
                infoDiv.textContent = `Код дійсний ще ${expiry}`;
                infoDiv.style.display = 'block';
            } else {
                infoDiv.textContent = 'Час дії коду закінчився';
                infoDiv.style.color = '#e7000b';
                infoDiv.style.display = 'block';
            }
        } else {
            infoDiv.style.display = 'none';
        }

        if (component.resendError) {
            resendErrorDiv.textContent = component.resendError;
            resendErrorDiv.style.display = 'block';
        } else {
            resendErrorDiv.style.display = 'none';
        }

        const canResend = component.canResend();
        resendBtn.disabled = !canResend;
        resendBtn.style.opacity = canResend ? '1' : '0.5';
        resendBtn.style.cursor = canResend ? 'pointer' : 'not-allowed';

        if (component.resending) {
            resendBtn.textContent = 'Відправка...';
        } else if (canResend) {
            resendBtn.textContent = 'Відправити код знову';
        } else if (component.nextResendAt) {
            const countdown = component.getResendCountdown();
            resendBtn.textContent = `Відправити через ${countdown}`;
        }
    }

    resendBtn.addEventListener('click', async function() {
        const component = getAlpineComponent();
        if (component && component.resendCode) {
            await component.resendCode();
            updatePopupUI();
        }
    });

    popup.addEventListener('popup-opened', function() {
        updatePopupUI();
        if (updateInterval) clearInterval(updateInterval);
        updateInterval = setInterval(updatePopupUI, 1000);
    });

    popup.addEventListener('popup-closed', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
            updateInterval = null;
        }
    });
});
</script>
@endpush
