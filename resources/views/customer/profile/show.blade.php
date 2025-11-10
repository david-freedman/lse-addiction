@extends('layouts.auth')

@section('title', 'Профіль')

@section('content')
<div class="main-page__auth auth">
    <div class="auth__main">
        <div class="auth__body">
            <div class="auth__header header-auth">
                <div class="header-auth__icon">
                    <img src="{{ asset('img/user.svg') }}" alt="User" onerror="this.src='{{ asset('img/lock.svg') }}'">
                </div>
                <div class="header-auth__body">
                    <h1 class="header-auth__title">
                        Мій профіль
                    </h1>
                </div>
            </div>
            <div class="auth__content">
                <div class="auth__wrapper">
                    <div class="auth__fields">
                        <div class="auth__field field">
                            <div class="field__label">
                                Прізвище
                            </div>
                            <div class="field__value" style="padding: 12px 0; font-size: 16px; color: #333;">
                                {{ $viewModel->surname() ?? 'Не вказано' }}
                            </div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Ім'я
                            </div>
                            <div class="field__value" style="padding: 12px 0; font-size: 16px; color: #333;">
                                {{ $viewModel->name() ?? 'Не вказано' }}
                            </div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Дата народження
                            </div>
                            <div class="field__value" style="padding: 12px 0; font-size: 16px; color: #333;">
                                {{ $viewModel->birthday() ?? 'Не вказано' }}
                            </div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Місто
                            </div>
                            <div class="field__value" style="padding: 12px 0; font-size: 16px; color: #333;">
                                {{ $viewModel->city() ?? 'Не вказано' }}
                            </div>
                        </div>

                        <div class="auth__field field" style="border-top: 1px solid #e5e5e5; padding-top: 24px; margin-top: 24px;">
                            <div class="field__label" style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">
                                Контактні дані
                            </div>
                        </div>

                        <div class="auth__field field">
                            <div class="field__label">
                                Email
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0;">
                                <span style="font-size: 16px; color: #333; flex: 1;">{{ $viewModel->email() }}</span>
                                @if($viewModel->isEmailVerified())
                                    <span style="background-color: #d1fae5; color: #065f46; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px; flex-shrink: 0;">
                                        Підтверджено
                                    </span>
                                @else
                                    <span style="background-color: #fef3c7; color: #92400e; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px; flex-shrink: 0;">
                                        Не підтверджено
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Телефон
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0;">
                                <span style="font-size: 16px; color: #333; flex: 1;">{{ $viewModel->phone() }}</span>
                                @if($viewModel->isPhoneVerified())
                                    <span style="background-color: #d1fae5; color: #065f46; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px; flex-shrink: 0;">
                                        Підтверджено
                                    </span>
                                @else
                                    <span style="background-color: #fef3c7; color: #92400e; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px; flex-shrink: 0;">
                                        Не підтверджено
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(count($viewModel->profileFields()) > 0)
                            <div class="auth__field field" style="border-top: 1px solid #e5e5e5; padding-top: 24px; margin-top: 24px;">
                                <div class="field__label" style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">
                                    Додаткова інформація
                                </div>
                            </div>

                            @foreach($viewModel->profileFields() as $label => $value)
                                <div class="auth__field field">
                                    <div class="field__label">
                                        {{ $label }}
                                    </div>
                                    <div class="field__value" style="padding: 12px 0; font-size: 16px; color: #333;">
                                        {{ $value }}
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @unless($viewModel->isFullyVerified())
                            <div class="auth__field" style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                <div style="font-size: 14px; color: #92400e;">
                                    Будь ласка, підтвердіть всі контактні дані
                                </div>
                            </div>
                        @endunless

                        @unless($viewModel->hasContactDetails())
                            <div class="auth__field" style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-top: 16px;">
                                <div style="font-size: 14px; color: #1e40af;">
                                    Будь ласка, заповніть всі особисті дані
                                </div>
                            </div>
                        @endunless
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="{{ route('customer.profile.edit') }}" class="auth__button button button--fill">
                            Редагувати профіль
                        </a>
                        <form action="{{ route('customer.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="auth__button button button--outline" style="width: 100%;">
                                Вийти з профілю
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="ibg">
    </div>
</div>
@endsection
