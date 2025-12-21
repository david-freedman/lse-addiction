@extends('layouts.auth')

@section('title', 'Підтвердження входу')

@section('content')
<div class="main-page__auth auth">
    <div class="auth__main">
        <div class="auth__logo">
            <a href="{{ route('student.dashboard') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="LifeScanEducation" class="ibg ibg--contain">
            </a>
        </div>
        <div class="auth__body">
            <div class="auth__header header-auth">
                <div class="header-auth__icon">
                    <img src="{{ asset('img/lock.svg') }}" alt="Image">
                </div>
                <div class="header-auth__body">
                    <h1 class="header-auth__title">
                        Підтвердження входу
                    </h1>
                    <div class="header-auth__subtitle">Код відправлено на {{ $type === 'email' ? 'ваш email' : 'ваш телефон' }}</div>
                </div>
            </div>
            <form action="{{ route('student.verify-login') }}" method="POST" class="auth__content" id="verifyForm">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="contact" value="{{ $contact }}">
                <input type="hidden" name="code" id="codeValue">

                <div class="auth__wrapper">
                    <div class="code-popup__title" style="text-align: center; margin-bottom: 20px; font-size: 18px;">
                        Введіть код з {{ $type === 'email' ? 'E-mail' : 'голосового дзвінка' }}
                    </div>
                    <div class="code-popup__body" style="display: flex; gap: 10px; justify-content: center; margin-bottom: 20px;">
                        <input type="text" inputmode="numeric" pattern="[0-9]" class="code-input" maxlength="1" autocomplete="off" autofocus style="width: 50px; height: 50px; text-align: center; font-size: 24px;">
                        <input type="text" inputmode="numeric" pattern="[0-9]" class="code-input" maxlength="1" autocomplete="off" style="width: 50px; height: 50px; text-align: center; font-size: 24px;">
                        <input type="text" inputmode="numeric" pattern="[0-9]" class="code-input" maxlength="1" autocomplete="off" style="width: 50px; height: 50px; text-align: center; font-size: 24px;">
                        <input type="text" inputmode="numeric" pattern="[0-9]" class="code-input" maxlength="1" autocomplete="off" style="width: 50px; height: 50px; text-align: center; font-size: 24px;">
                    </div>
                    @error('code')
                        <div class="field__error" style="text-align: center; margin-bottom: 15px; color: #dc2626;">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="auth__button button button--fill">
                        Увійти
                    </button>
                </div>
            </form>

            <form action="{{ route('student.verify-login.resend') }}" method="POST" style="text-align: center; margin-top: 15px;">
                @csrf
                <button type="submit" id="resendButton" class="auth__link" style="background: none; border: none; cursor: pointer; padding: 0; font: inherit; color: inherit; text-decoration: underline;">
                    Відправити код знову
                </button>
            </form>

            @error('resend')
                <div id="resendError" class="field__error" style="text-align: center; margin-top: 10px; color: #dc2626;">{{ $message }}</div>
            @enderror

            @if(session('status'))
                <div style="text-align: center; margin-top: 10px; color: #059669; font-size: 14px;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="auth__label" style="text-align: center; margin-top: 15px;">
                <a href="{{ route('student.login') }}">← Повернутися до входу</a>
            </div>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/auth.webp') }}" alt="Image" class="ibg">
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verifyForm');
    const inputs = document.querySelectorAll('.code-input');
    const codeValue = document.getElementById('codeValue');

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

    form.addEventListener('submit', function(e) {
        const code = Array.from(inputs).map(input => input.value).join('');
        codeValue.value = code;
    });

    // Resend button timer
@if($errors->has('resend') && session('next_resend_at'))
    const resendButton = document.getElementById('resendButton');
    const nextResendAt = {{ session('next_resend_at') }};
    const resendButtonOriginalText = resendButton ? resendButton.textContent : '';

    function updateResendTimer() {
        if (!resendButton) return;

        const now = Math.floor(Date.now() / 1000);
        const secondsLeft = nextResendAt - now;

        if (secondsLeft <= 0) {
            resendButton.textContent = resendButtonOriginalText;
            resendButton.disabled = false;
            resendButton.style.opacity = '1';
            resendButton.style.cursor = 'pointer';

            const errorDiv = document.getElementById('resendError');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }

            clearInterval(resendTimerInterval);
            return;
        }

        const minutes = Math.floor(secondsLeft / 60);
        const seconds = secondsLeft % 60;

        let timeString = '';
        if (minutes > 0) {
            timeString = `Повторити через ${minutes} хв ${seconds} сек`;
        } else {
            timeString = `Повторити через ${seconds} сек`;
        }

        resendButton.textContent = timeString;
        resendButton.disabled = true;
        resendButton.style.opacity = '0.5';
        resendButton.style.cursor = 'not-allowed';
    }

    updateResendTimer();
    const resendTimerInterval = setInterval(updateResendTimer, 1000);
@endif
});
</script>
@endpush
@endsection
