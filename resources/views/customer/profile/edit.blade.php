@extends('layouts.auth')

@section('title', 'Редагувати профіль')

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
                        Редагувати профіль
                    </h1>
                </div>
            </div>
            <form action="{{ route('customer.profile.update') }}" method="POST" class="auth__content">
                @csrf
                @method('PATCH')
                <div class="auth__wrapper">
                    <div class="auth__fields">
                        <div class="auth__field field">
                            <div class="field__label">
                                Прізвище
                            </div>
                            <div class="field__input">
                                <input type="text" name="surname" id="surname" placeholder="Введіть своє прізвище" value="{{ old('surname', $customer->surname) }}" required>
                            </div>
                            @error('surname')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Ім'я
                            </div>
                            <div class="field__input">
                                <input type="text" name="name" id="name" placeholder="Введіть своє ім'я" value="{{ old('name', $customer->name) }}" required>
                            </div>
                            @error('name')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Дата народження
                            </div>
                            <div class="field__input">
                                <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $customer->birthday?->format('Y-m-d')) }}" required>
                            </div>
                            @error('birthday')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                            <div class="field__hint" style="margin-top: 8px; font-size: 14px; color: #666;">
                                Вам повинно бути не менше 18 років
                            </div>
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Місто
                            </div>
                            <div class="field__input">
                                <input type="text" name="city" id="city" placeholder="Введіть своє місто" value="{{ old('city', $customer->city) }}" required>
                            </div>
                            @error('city')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth__field field" style="border-top: 1px solid #e5e5e5; padding-top: 24px; margin-top: 24px;">
                            <div class="field__label" style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">
                                Зміна контактів
                            </div>
                        </div>

                        <div class="auth__field field">
                            <div class="field__label">
                                Новий Email
                            </div>
                            <div class="field__input">
                                <input type="email" name="email" id="email" placeholder="{{ $customer->email }}" value="{{ old('email') }}">
                            </div>
                            <div class="field__hint" style="margin-top: 8px; font-size: 14px; color: #666;">
                                Поточний: {{ $customer->email }}
                            </div>
                            @error('email')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Новий Телефон
                            </div>
                            <div class="field__input">
                                <input type="text" name="phone" id="phone" placeholder="+380XXXXXXXXX" value="{{ old('phone') }}">
                            </div>
                            <div class="field__hint" style="margin-top: 8px; font-size: 14px; color: #666;">
                                Поточний: {{ $customer->phone }}
                            </div>
                            @error('phone')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth__field" style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                            <div style="font-size: 14px; color: #1e40af;">
                                Після зміни email або телефону вам буде відправлено код підтвердження
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 16px; align-items: center; justify-content: space-between;">
                        <button type="submit" class="auth__button button button--fill">
                            Зберегти зміни
                        </button>
                        <a href="{{ route('customer.profile.show') }}" style="font-size: 14px; color: #666; text-decoration: none; transition: color 0.2s;">
                            Скасувати
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="ibg">
    </div>
</div>
@endsection
