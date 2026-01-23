@extends('layouts.auth')

@section('title', 'Умови публічної оферти')

@section('content')
<div class="main-page__auth auth">
    <div class="auth__main auth__main--wide">
        <div class="auth__logo">
            <a href="{{ route('student.register') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="LifeScanEducation" class="ibg ibg--contain">
            </a>
        </div>
        <div class="auth__body">
            <div class="auth__header header-auth">
                <div class="header-auth__body">
                    <h1 class="header-auth__title">
                        Умови публічної оферти
                    </h1>
                </div>
            </div>
            <div class="auth__content">
                <div data-simplebar class="auth__scroll auth__scroll--legal">
                    <div class="auth__wrapper legal-content">
                        <p class="legal-placeholder">
                            Текст умов публічної оферти буде додано пізніше.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
