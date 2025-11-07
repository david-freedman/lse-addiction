<div id="popup-code" aria-hidden="true" class="popup popup--code">
    <div class="popup__wrapper">
        <div class="popup__content">
            <div class="code-popup" id="popupCodeForm">
                <div class="code-popup__title">
                    Введіть код з E-mail
                </div>
                <div class="code-popup__body">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                    <input type="number" class="popup-code-input" maxlength="1" autocomplete="off">
                </div>
                <div class="code-popup__error" id="popupCodeError" style="display: none; color: #e7000b; margin-bottom: 1rem; text-align: center;"></div>
                <button type="button" class="code-popup__button button button--fill" id="popupCodeSubmit">
                    Продовжити
                </button>
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
            const digits = pastedData.replace(/\D/g, '').split('').slice(0, 6);

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

        if (code.length !== 6) {
            errorDiv.textContent = 'Будь ласка, введіть повний 6-значний код';
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
});
</script>
@endpush
