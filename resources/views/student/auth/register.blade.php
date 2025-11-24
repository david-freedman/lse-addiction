@extends('layouts.auth')

@section('title', 'Реєстрація')

@section('popup')
    @include('components.verification-popup', ['action' => route('student.verify-phone')])
@endsection

@section('content')
<div class="main-page__auth auth" x-data="registrationForm()">
    <div class="auth__main">
        <div class="auth__logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="LifeScanEducation" class="ibg ibg--contain">
            </a>
        </div>
        <div class="auth__body">
            <div class="auth__header header-auth header-auth--col">
                <div class="header-auth__icon">
                    <img src="{{ asset('img/lock.svg') }}" alt="Image">
                </div>
                <div class="header-auth__body">
                    <h1 class="header-auth__title">
                        Реєстрація на платформу
                    </h1>
                    <div class="header-auth__subtitle">LifeScanEducation</div>
                </div>
            </div>
            <form action="{{ route('student.register') }}" method="POST" class="auth__content" @submit="handleSubmit">
                @csrf
                <div class="auth__wrapper">
                    <div class="auth__fields">
                        <div class="auth__field field">
                            <div class="field__label">
                                Email
                            </div>
                            <div class="field__input">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="Введіть свій Email"
                                    value="{{ old('email') }}"
                                    x-model="email"
                                    :disabled="emailVerified"
                                    required>
                                <button
                                    type="button"
                                    class="field__send"
                                    x-show="!emailVerified"
                                    @click="sendCode('email')"
                                    :disabled="!email || emailSending">
                                    <span x-show="!emailSending">відправити код</span>
                                    <span x-show="emailSending">відправка...</span>
                                </button>
                                <button
                                    type="button"
                                    class="field__confirmed"
                                    x-show="emailVerified">
                                    ✓ Підтверджено
                                </button>
                            </div>
                            @error('email')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                            <div class="field__error" x-show="emailError" x-text="emailError"></div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Телефон
                            </div>
                            <div class="field__input">
                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    placeholder="+380XXXXXXXXX"
                                    value="{{ old('phone') }}"
                                    x-model="phone"
                                    :disabled="phoneVerified"
                                    pattern="^\+380\d{9}$"
                                    title="Формат: +380XXXXXXXXX"
                                    required>
                                <button
                                    type="button"
                                    class="field__send"
                                    x-show="!phoneVerified"
                                    @click="sendCode('phone')"
                                    :disabled="!phone || phoneSending">
                                    <span x-show="!phoneSending">відправити код</span>
                                    <span x-show="phoneSending">відправка...</span>
                                </button>
                                <button
                                    type="button"
                                    class="field__confirmed"
                                    x-show="phoneVerified">
                                    ✓ Підтверджено
                                </button>
                            </div>
                            @error('phone')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                            <div class="field__error" x-show="phoneError" x-text="phoneError"></div>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="auth__button button button--fill"
                        :disabled="!canSubmit">
                        Зареєструватися
                    </button>
                    <div class="auth__label">
                        Вже маєте обліковий запис? <a href="{{ route('student.login') }}">Увійти</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/auth.webp') }}" alt="Image" class="ibg">
    </div>

    @include('components.verification-popup')
</div>

@push('scripts')
<script>
function registrationForm() {
    return {
        email: '{{ old('email') }}',
        phone: '{{ old('phone') }}',
        phoneVerified: false,
        emailVerified: false,
        phoneSending: false,
        emailSending: false,
        phoneError: '',
        emailError: '',
        currentVerificationType: null,
        resending: false,
        nextResendAt: null,
        codeExpiresAt: null,
        resendError: '',
        resendInterval: null,

        init() {
            document.addEventListener('click', (e) => {
                const popup = document.getElementById('popup-code');
                if (e.target === popup?.querySelector('.popup__wrapper')) {
                    this.closeVerificationPopup();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const popup = document.getElementById('popup-code');
                    if (popup?.classList.contains('popup_show')) {
                        this.closeVerificationPopup();
                    }
                }
            });
        },

        get canSubmit() {
            return this.phoneVerified && this.emailVerified && this.email && this.phone;
        },

        async sendCode(type) {
            const contact = type === 'email' ? this.email : this.phone;

            if (!contact) {
                if (type === 'email') {
                    this.emailError = 'Заповніть email';
                } else {
                    this.phoneError = 'Заповніть телефон';
                }
                return;
            }

            if (type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(contact)) {
                    this.emailError = 'Введіть коректний email адрес';
                    return;
                }
                this.emailSending = true;
                this.emailError = '';
            } else {
                const phoneRegex = /^\+380\d{9}$/;
                if (!phoneRegex.test(contact)) {
                    this.phoneError = 'Номер телефону має бути у форматі +380XXXXXXXXX';
                    return;
                }
                this.phoneSending = true;
                this.phoneError = '';
            }

            try {
                const payload = {
                    type: type
                };

                if (type === 'email') {
                    payload.email = this.email;
                } else {
                    payload.phone = this.phone;
                }

                const response = await fetch('{{ route('student.registration.send-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Помилка відправки коду');
                }

                this.currentVerificationType = type;
                this.codeExpiresAt = Math.floor(Date.now() / 1000) + (15 * 60);
                this.nextResendAt = null;
                this.resendError = '';

                this.openVerificationPopup(type);
            } catch (error) {
                if (type === 'email') {
                    this.emailError = error.message;
                } else {
                    this.phoneError = error.message;
                }
            } finally {
                if (type === 'email') {
                    this.emailSending = false;
                } else {
                    this.phoneSending = false;
                }
            }
        },

        openVerificationPopup(type) {
            this.resetPopupState();

            const popup = document.getElementById('popup-code');
            if (!popup) {
                console.error('Popup not found');
                return;
            }

            const title = popup.querySelector('.code-popup__title');
            if (title) {
                title.textContent = type === 'email' ? 'Введіть код з E-mail' : 'Введіть код з голосового дзвінка';
            }

            const inputs = popup.querySelectorAll('.popup-code-input');
            inputs.forEach(input => input.value = '');

            popup.classList.add('popup_show');
            popup.setAttribute('aria-hidden', 'false');
            document.documentElement.classList.add('popup-show');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                const firstInput = inputs[0];
                if (firstInput) {
                    firstInput.focus();
                }

                popup.dispatchEvent(new Event('popup-opened'));
            }, 100);
        },

        async verifyCode(code) {
            try {
                const response = await fetch('{{ route('student.registration.verify-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        code: code,
                        type: this.currentVerificationType
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Невірний код');
                }

                if (this.currentVerificationType === 'email') {
                    this.emailVerified = true;
                } else {
                    this.phoneVerified = true;
                }

                this.closeVerificationPopup();

                return true;
            } catch (error) {
                throw error;
            }
        },

        resetPopupState() {
            const errorDiv = document.getElementById('popupCodeError');
            const infoDiv = document.getElementById('popupCodeInfo');
            const resendErrorDiv = document.getElementById('popupResendError');

            if (errorDiv) {
                errorDiv.style.display = 'none';
                errorDiv.textContent = '';
            }

            if (infoDiv) {
                infoDiv.style.display = 'none';
                infoDiv.textContent = '';
                infoDiv.style.color = '#666';
            }

            if (resendErrorDiv) {
                resendErrorDiv.style.display = 'none';
                resendErrorDiv.textContent = '';
            }

            this.resendError = '';
        },

        closeVerificationPopup() {
            const popup = document.getElementById('popup-code');
            if (!popup) return;

            popup.classList.remove('popup_show');
            popup.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('popup-show');
            document.body.style.overflow = '';

            if (this.resendInterval) {
                clearInterval(this.resendInterval);
                this.resendInterval = null;
            }

            popup.dispatchEvent(new Event('popup-closed'));
        },

        async resendCode() {
            if (!this.currentVerificationType) {
                return;
            }

            if (this.nextResendAt && Date.now() / 1000 < this.nextResendAt) {
                return;
            }

            this.resending = true;
            this.resendError = '';

            try {
                const response = await fetch('{{ route('student.registration.resend-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        type: this.currentVerificationType
                    })
                });

                const data = await response.json();

                if (response.status === 429) {
                    this.nextResendAt = data.next_resend_at;
                    this.resendError = data.message || 'Спробуйте пізніше';
                    this.startResendCountdown();
                    return;
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Помилка відправки коду');
                }

                this.codeExpiresAt = data.expires_at;
                this.nextResendAt = null;
                this.resendError = '';

                const inputs = document.querySelectorAll('.popup-code-input');
                inputs.forEach(input => input.value = '');
                if (inputs[0]) {
                    inputs[0].focus();
                }
            } catch (error) {
                this.resendError = error.message;
            } finally {
                this.resending = false;
            }
        },

        startResendCountdown() {
            if (this.resendInterval) {
                clearInterval(this.resendInterval);
            }

            this.resendInterval = setInterval(() => {
                const now = Date.now() / 1000;
                if (now >= this.nextResendAt) {
                    this.nextResendAt = null;
                    clearInterval(this.resendInterval);
                    this.resendInterval = null;
                }
            }, 1000);
        },

        getResendCountdown() {
            if (!this.nextResendAt) return null;

            const now = Date.now() / 1000;
            const diff = Math.max(0, this.nextResendAt - now);

            const minutes = Math.floor(diff / 60);
            const seconds = Math.floor(diff % 60);

            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        },

        getCodeExpiry() {
            if (!this.codeExpiresAt) return null;

            const now = Date.now() / 1000;
            const diff = Math.max(0, this.codeExpiresAt - now);

            const minutes = Math.floor(diff / 60);
            const seconds = Math.floor(diff % 60);

            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        },

        canResend() {
            if (this.resending) return false;
            if (!this.nextResendAt) return true;
            return Date.now() / 1000 >= this.nextResendAt;
        },

        handleSubmit(event) {
            if (!this.canSubmit) {
                event.preventDefault();

                if (!this.phoneVerified) {
                    this.phoneError = 'Спочатку підтвердіть телефон';
                }
                if (!this.emailVerified) {
                    this.emailError = 'Спочатку підтвердіть email';
                }
            }
        }
    }
}
</script>
@endpush
@endsection
