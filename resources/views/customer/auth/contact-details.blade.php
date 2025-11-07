@extends('layouts.auth')

@section('title', 'Контактні дані')

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
                        Контактні дані
                    </h1>
                </div>
                <div class="header-auth__label">
                    крок 2/3
                </div>
            </div>
            <form action="{{ route('customer.contact-details') }}" method="POST" class="auth__content">
                @csrf
                <div class="auth__wrapper">
                    <div class="auth__fields">
                        <div class="auth__field field">
                            <div class="field__label">
                                Прізвище
                            </div>
                            <div class="field__input">
                                <input type="text" name="surname" id="surname" placeholder="Введіть своє прізвище" value="{{ old('surname') }}" required>
                            </div>
                            @error('surname')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                І'мя
                            </div>
                            <div class="field__input">
                                <input type="text" name="name" id="name" placeholder="Введіть свое І'мя" value="{{ old('name') }}" required>
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
                                <input type="date" name="birthday" id="birthday" placeholder="17.10.2025" value="{{ old('birthday') }}" required>
                            </div>
                            @error('birthday')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="auth__field field">
                            <div class="field__label">
                                Місто
                            </div>
                            <div class="field__input">
                                <input type="text" name="city" id="city" placeholder="Введіть свое місто" value="{{ old('city') }}" required>
                            </div>
                            @error('city')
                                <div class="field__error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="auth__button button button--fill">
                        Продовжити
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="ibg">
    </div>
</div>
@endsection
