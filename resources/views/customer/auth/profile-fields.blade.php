@extends('layouts.auth')

@section('title', 'Анкетні дані')

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
                        Анкетні дані
                    </h1>
                </div>
                <div class="header-auth__label">
                    крок 3/3
                </div>
            </div>
            <form action="{{ route('customer.profile-fields.save') }}" method="POST" class="auth__content">
                @csrf
                <div data-simplebar class="auth__scroll">
                    <div class="auth__wrapper">
                        <div class="auth__fields">
                            @foreach($fields as $field)
                                <div class="auth__field field">
                                    <div class="field__label">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span style="color: #dc2626;">*</span>
                                        @endif
                                    </div>

                                    @switch($field->type->value)
                                        @case('select')
                                            <select name="profile_fields[{{ $field->key }}]" id="field_{{ $field->key }}" {{ $field->is_required ? 'required' : '' }}>
                                                <option value="" selected>Оберіть варіант</option>
                                                @if($field->options)
                                                    @foreach($field->options as $optionKey => $optionLabel)
                                                        <option value="{{ $optionKey }}" {{ old('profile_fields.' . $field->key) == $optionKey ? 'selected' : '' }}>
                                                            {{ $optionLabel }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break

                                        @case('date')
                                            <div class="field__input">
                                                <input type="date"
                                                    name="profile_fields[{{ $field->key }}]"
                                                    id="field_{{ $field->key }}"
                                                    value="{{ old('profile_fields.' . $field->key) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            </div>
                                            @break

                                        @case('number')
                                            <div class="field__input">
                                                <input type="number"
                                                    name="profile_fields[{{ $field->key }}]"
                                                    id="field_{{ $field->key }}"
                                                    placeholder="Введіть {{ mb_strtolower($field->label) }}"
                                                    value="{{ old('profile_fields.' . $field->key) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            </div>
                                            @break

                                        @default
                                            <div class="field__input">
                                                <input type="text"
                                                    name="profile_fields[{{ $field->key }}]"
                                                    id="field_{{ $field->key }}"
                                                    placeholder="Введіть {{ mb_strtolower($field->label) }}"
                                                    value="{{ old('profile_fields.' . $field->key) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            </div>
                                    @endswitch

                                    @error('profile_fields.' . $field->key)
                                        <div class="field__error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="auth__button button button--fill">
                            Продовжити
                        </button>
                    </div>
                </div>
                <div class="auth__footer">
                    <a href="{{ route('customer.contact-details.show') }}" class="auth__footer-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="11" viewBox="0 0 15 11" fill="none">
                            <path d="M0.5 5.5L5.5 10.5M0.5 5.5L5.5 0.5M0.5 5.5H14" stroke="var(--color, #4f4f4f)" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Назад
                    </a>
                    <button type="button" class="auth__footer-item" onclick="event.preventDefault(); document.getElementById('skip-form').submit();">
                        Продовжити потім
                    </button>
                </div>
            </form>

            <form id="skip-form" action="{{ route('customer.profile-fields.skip') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="ibg">
    </div>
</div>
@endsection
