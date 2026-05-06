@extends('layouts.auth')

@section('title', 'Увійти')

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
                        Увійти
                    </h1>
                    <div class="header-auth__subtitle">LifeScanEducation</div>
                </div>
            </div>
            <form action="{{ route('student.login.send') }}" method="POST" class="auth__content">
                @csrf
                <div class="auth__wrapper">
                    @error('rate_limit')
                        <div class="auth__rate-limit-error" style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <div style="color: #dc2626; font-weight: 600; margin-bottom: 8px;">⏱ Перевищено ліміт спроб</div>
                            <div style="color: #991b1b; font-size: 14px; margin-bottom: 10px;">{{ $message }}</div>
                            @if(session('next_resend_at'))
                                <div style="color: #7f1d1d; font-size: 13px;">
                                    Наступна спроба через: <span id="timer" style="font-weight: 600;"></span>
                                </div>
                            @endif
                        </div>
                    @enderror

                    <div class="auth__fields">
                        <div class="auth__field field">
                            <div class="field__label" id="contactLabel">
                                Телефон
                            </div>
                            <div class="field__input">
                                <input type="text" name="contact" id="contact" placeholder="Введіть свій телефон" value="{{ old('contact') }}" required autofocus>
                            </div>
                            @error('contact')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="auth__link" id="toggleLoginMethod">Увійти через Email</a>
                    <button type="submit" class="auth__button button button--fill" id="submitButton" @if($errors->has('rate_limit') && session('next_resend_at')) disabled @endif>
                        Відправити код
                    </button>
                    <div class="auth__divider">
                        <span>або</span>
                    </div>
                    <a href="{{ route('student.auth.google') }}" class="auth__button auth__button--google button button--outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" style="margin-right:8px;flex-shrink:0;">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.35-8.16 2.35-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Увійти через Google
                    </a>
                    @error('google')
                        <div class="field__error">{{ $message }}</div>
                    @enderror
                    <div class="auth__label">
                        Немає облікового запису? <a href="{{ route('student.register') }}">Зареєструватися</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/auth.webp') }}" alt="Image" class="ibg">
    </div>
</div>

@push('styles')
<style>
    .auth__button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #9ca3af !important;
    }
    .auth__divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 4px 0;
        color: #9ca3af;
        font-size: 13px;
    }
    .auth__divider::before,
    .auth__divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }
    .auth__button--google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        background: #fff !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        font-weight: 500;
        text-decoration: none;
    }
    .auth__button--google:hover {
        background: #f9fafb !important;
        border-color: #9ca3af !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between Phone and Email login
    const toggleLink = document.getElementById('toggleLoginMethod');
    const contactLabel = document.getElementById('contactLabel');
    const contactInput = document.getElementById('contact');
    let isPhoneMode = true;

    if (toggleLink) {
        toggleLink.addEventListener('click', function(e) {
            e.preventDefault();

            isPhoneMode = !isPhoneMode;

            if (isPhoneMode) {
                // Switch to Phone mode
                contactLabel.textContent = 'Телефон';
                contactInput.placeholder = 'Введіть свій телефон';
                contactInput.type = 'text';
                toggleLink.textContent = 'Увійти через Email';
            } else {
                // Switch to Email mode
                contactLabel.textContent = 'Email';
                contactInput.placeholder = 'Введіть свій email';
                contactInput.type = 'email';
                toggleLink.textContent = 'Увійти через Телефон';
            }

            // Clear input value on toggle
            contactInput.value = '';
            contactInput.focus();
        });
    }

@if($errors->has('rate_limit') && session('next_resend_at'))
    // Rate limit timer
    const timerElement = document.getElementById('timer');
    const submitButton = document.getElementById('submitButton');
    const nextResendAt = {{ session('next_resend_at') }};

    function updateTimer() {
        const now = Math.floor(Date.now() / 1000);
        const secondsLeft = nextResendAt - now;

        if (secondsLeft <= 0) {
            if (timerElement) {
                timerElement.textContent = '0 секунд';
            }
            if (submitButton) {
                submitButton.disabled = false;
            }
            clearInterval(timerInterval);

            const errorBlock = document.querySelector('.auth__rate-limit-error');
            if (errorBlock) {
                errorBlock.style.transition = 'opacity 0.3s';
                errorBlock.style.opacity = '0';
                setTimeout(() => errorBlock.remove(), 300);
            }
            return;
        }

        const minutes = Math.floor(secondsLeft / 60);
        const seconds = secondsLeft % 60;

        let timeString = '';
        if (minutes > 0) {
            timeString = `${minutes} хв ${seconds} сек`;
        } else {
            timeString = `${seconds} сек`;
        }

        if (timerElement) {
            timerElement.textContent = timeString;
        }
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
@endif
});
</script>
@endpush
@endsection
