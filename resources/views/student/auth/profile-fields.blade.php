@extends('layouts.auth')

@section('title', 'Анкетні дані')

@push('styles')
<style>
[x-cloak] { display: none !important; }
.auth__consents {
    margin-top: 24px;
    margin-bottom: 24px;
}
.auth__consent {
    margin-bottom: 16px;
}
.auth__consent:last-child {
    margin-bottom: 0;
}
.consent-checkbox {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    gap: 12px;
}
.consent-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.consent-checkbox__box {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    margin-top: 2px;
}
.consent-checkbox__box::after {
    content: '';
    width: 10px;
    height: 6px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) scale(0);
    transition: transform 0.15s ease;
    margin-top: -2px;
}
.consent-checkbox input[type="checkbox"]:checked + .consent-checkbox__box {
    background: #0d9488;
    border-color: #0d9488;
}
.consent-checkbox input[type="checkbox"]:checked + .consent-checkbox__box::after {
    transform: rotate(-45deg) scale(1);
}
.consent-checkbox input[type="checkbox"]:focus + .consent-checkbox__box {
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.2);
}
.consent-checkbox__label {
    font-size: 14px;
    line-height: 1.5;
    color: #374151;
}
.consent-checkbox__label a {
    color: #0d9488;
    text-decoration: underline;
}
.consent-checkbox__label a:hover {
    color: #0f766e;
}
.consent-checkbox__label .required {
    color: #dc2626;
}
.tag-input-container {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
    padding: 0.5rem 1.5625rem;
    min-height: 3rem;
    cursor: text;
    flex: 1;
}
.tag-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: #e0f2f1;
    color: #0d9488;
    border-radius: 4px;
    font-size: 13px;
    line-height: 1.4;
}
.tag-chip__remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
    font-size: 14px;
    line-height: 1;
    color: #0d9488;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
}
.tag-chip__remove:hover {
    color: #b91c1c;
}
.tag-input-field {
    border: none;
    outline: none;
    flex: 1;
    min-width: 120px;
    font-size: 14px;
    padding: 0;
    background: transparent;
}
.tag-counter {
    margin-top: 4px;
    font-size: 12px;
    color: #6b7280;
    text-align: right;
}
.custom-select {
    position: relative;
    cursor: pointer;
}
.custom-select__trigger {
    height: 3rem;
    flex-grow: 1;
    padding: 0 1.5625rem;
    display: flex;
    align-items: center;
    color: #4f4f4f;
    width: 100%;
}
.custom-select__label {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.custom-select__label--placeholder {
    opacity: 0.6;
}
.custom-select__chevron {
    width: 0.75rem;
    height: 0.75rem;
    margin-left: 0.5rem;
    transition: transform 0.2s;
    flex-shrink: 0;
}
.custom-select--open .custom-select__chevron {
    transform: rotate(180deg);
}
.custom-select--open.field__input {
    border-radius: 1.5625rem 1.5625rem 0 0;
    border-bottom-color: transparent;
}
.custom-select__dropdown {
    position: absolute;
    top: calc(100% - 1px);
    left: 0;
    right: 0;
    background: #f3f3f5;
    border: 0.0625rem solid #ddd;
    border-top: none;
    border-radius: 0 0 1.5625rem 1.5625rem;
    z-index: 10;
    overflow: hidden;
    max-height: 12.5rem;
    overflow-y: auto;
}
.custom-select__option {
    padding: 0.75rem 1.5625rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    color: #4f4f4f;
}
.custom-select__option:hover {
    background: #2ac3c1;
    color: #fff;
}
</style>
@endpush

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
                        Анкетні дані
                    </h1>
                </div>
                <div class="header-auth__label">
                    крок 3/3
                </div>
            </div>
            <form action="{{ route('student.profile-fields.save') }}" method="POST" class="auth__content">
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
                                            @php
                                                $selectedValue = old('profile_fields.' . $field->key, '');
                                                $selectedLabel = '';
                                                if ($selectedValue && $field->options) {
                                                    $selectedLabel = $field->options[$selectedValue] ?? '';
                                                }
                                            @endphp
                                            <div class="field__input custom-select"
                                                 x-data="{ open: false, selected: '{{ $selectedValue }}', label: '{{ $selectedLabel }}' }"
                                                 :class="{ 'custom-select--open': open }"
                                                 @click.away="open = false">
                                                <input type="hidden" name="profile_fields[{{ $field->key }}]" :value="selected">
                                                <div class="custom-select__trigger" @click="open = !open">
                                                    <span class="custom-select__label" :class="{ 'custom-select__label--placeholder': !selected }" x-text="label || 'Оберіть варіант'"></span>
                                                    <svg class="custom-select__chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 8" fill="none">
                                                        <path d="M1 1.5L6 6.5L11 1.5" stroke="#4f4f4f" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div class="custom-select__dropdown" x-show="open" x-cloak>
                                                    @if($field->options)
                                                        @foreach($field->options as $optionKey => $optionLabel)
                                                            <div class="custom-select__option"
                                                                 @click="selected = '{{ $optionKey }}'; label = '{{ $optionLabel }}'; open = false">
                                                                {{ $optionLabel }}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @break

                                        @case('date')
                                            <div class="field__input">
                                                <input type="text"
                                                    x-datepicker
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

                                        @case('tags')
                                            @php
                                                $maxItems = $field->options['max_items'] ?? 5;
                                                $existingTags = old("profile_fields.{$field->key}", []);
                                                if (!is_array($existingTags)) {
                                                    $existingTags = [];
                                                }
                                            @endphp
                                            <div x-data="tagInput(@js($existingTags), {{ $maxItems }}, '{{ $field->key }}')">
                                                <div class="field__input">
                                                    <div class="tag-input-container" @click="$refs.tagInputField.focus()">
                                                        <template x-for="(tag, index) in tags" :key="index">
                                                            <span class="tag-chip">
                                                                <span x-text="tag"></span>
                                                                <button type="button" @click.stop="removeTag(index)" class="tag-chip__remove">&times;</button>
                                                            </span>
                                                        </template>
                                                        <input x-show="tags.length < maxItems"
                                                            x-ref="tagInputField"
                                                            x-model="input"
                                                            @keydown.enter.prevent="addTag()"
                                                            @keydown.,.prevent="addTag()"
                                                            @keydown.backspace="handleBackspace()"
                                                            type="text"
                                                            placeholder="Введіть спеціальність і натисніть Enter"
                                                            class="tag-input-field">
                                                    </div>
                                                </div>
                                                <div class="tag-counter" x-text="`${tags.length}/${maxItems}`"></div>
                                                <template x-for="(tag, i) in tags" :key="'hidden-'+i">
                                                    <input type="hidden" :name="`profile_fields[${fieldKey}][]`" :value="tag">
                                                </template>
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

                        <div class="auth__consents">
                            <div class="auth__consent">
                                <label class="consent-checkbox">
                                    <input type="checkbox"
                                        name="consent_privacy_policy"
                                        id="consent_privacy_policy"
                                        value="1"
                                        {{ old('consent_privacy_policy') ? 'checked' : '' }}
                                        required>
                                    <span class="consent-checkbox__box"></span>
                                    <span class="consent-checkbox__label">
                                        Я погоджуюсь з <a href="{{ route('student.privacy-policy') }}" target="_blank" rel="noopener">Політикою обробки персональних даних</a> <span class="required">*</span>
                                    </span>
                                </label>
                                @error('consent_privacy_policy')
                                    <div class="field__error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="auth__consent">
                                <label class="consent-checkbox">
                                    <input type="checkbox"
                                        name="consent_public_offer"
                                        id="consent_public_offer"
                                        value="1"
                                        {{ old('consent_public_offer') ? 'checked' : '' }}
                                        required>
                                    <span class="consent-checkbox__box"></span>
                                    <span class="consent-checkbox__label">
                                        Я погоджуюсь з <a href="{{ route('student.public-offer') }}" target="_blank" rel="noopener">Умовами публічної оферти</a> <span class="required">*</span>
                                    </span>
                                </label>
                                @error('consent_public_offer')
                                    <div class="field__error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="auth__button button button--fill">
                            Продовжити
                        </button>
                    </div>
                </div>
                <div class="auth__footer">
                    <a href="{{ route('student.contact-details.show') }}" class="auth__footer-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="11" viewBox="0 0 15 11" fill="none">
                            <path d="M0.5 5.5L5.5 10.5M0.5 5.5L5.5 0.5M0.5 5.5H14" stroke="var(--color, #4f4f4f)" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Назад
                    </a>
                    <button type="button" class="auth__footer-item" onclick="handleSkip()">
                        Продовжити потім
                    </button>
                </div>
            </form>

            <form id="skip-form" action="{{ route('student.profile-fields.skip') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="consent_privacy_policy" id="skip_consent_privacy_policy">
                <input type="hidden" name="consent_public_offer" id="skip_consent_public_offer">
            </form>
        </div>
    </div>
    <div class="auth__image">
        <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="ibg">
    </div>
</div>

@push('scripts')
<script>
function tagInput(initialTags, maxItems, fieldKey) {
    return {
        tags: initialTags || [],
        input: '',
        maxItems: maxItems,
        fieldKey: fieldKey,
        addTag() {
            const tag = this.input.trim();
            if (tag.length < 2 || tag.length > 50) {
                this.input = '';
                return;
            }
            if (this.tags.length >= this.maxItems) {
                return;
            }
            const duplicate = this.tags.some(t => t.toLowerCase() === tag.toLowerCase());
            if (duplicate) {
                this.input = '';
                return;
            }
            this.tags.push(tag);
            this.input = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        handleBackspace() {
            if (this.input === '' && this.tags.length > 0) {
                this.tags.pop();
            }
        }
    };
}

function handleSkip() {
    const privacyConsent = document.getElementById('consent_privacy_policy');
    const offerConsent = document.getElementById('consent_public_offer');

    if (!privacyConsent.checked || !offerConsent.checked) {
        alert('Для продовження необхідно погодитись з Політикою обробки персональних даних та Умовами публічної оферти');
        return;
    }

    document.getElementById('skip_consent_privacy_policy').value = privacyConsent.checked ? '1' : '';
    document.getElementById('skip_consent_public_offer').value = offerConsent.checked ? '1' : '';
    document.getElementById('skip-form').submit();
}
</script>
@endpush
@endsection
