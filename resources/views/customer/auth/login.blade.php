@extends('layouts.auth')

@section('title', 'Увійти')

@section('content')
<div class="main-page__auth auth">
    <div class="auth__main">
        <div class="auth__logo">
            <a href="{{ route('home') }}">
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
            <form action="{{ route('customer.login.send') }}" method="POST" class="auth__content">
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
                    <div class="auth__label">
                        Немає облікового запису? <a href="{{ route('customer.register') }}">Зареєструватися</a>
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
