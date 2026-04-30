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
                                <div class="auth__field field" @if($field->key === 'diploma_number') x-data="{ showDiiaModal: false }" @endif>
                                    <div class="field__label">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span style="color: #dc2626;">*</span>
                                        @endif
                                        @if($field->key === 'diploma_number')
                                            <button type="button"
                                                @click="showDiiaModal = true"
                                                style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;background:#00AE9D;color:#fff;font-size:11px;font-weight:700;border:none;cursor:pointer;margin-left:6px;vertical-align:middle;line-height:1;flex-shrink:0;"
                                                title="Підказка">і</button>
                                        @endif
                                    </div>

                                    @if($field->key === 'diploma_number')
                                        <template x-teleport="body">
                                            <div x-show="showDiiaModal" x-cloak>
                                                <div @click="showDiiaModal = false"
                                                     style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;"></div>
                                                <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;background:#fff;border-radius:20px;padding:36px 32px 32px;max-width:340px;width:90%;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.18);">
                                                    <button type="button"
                                                        @click="showDiiaModal = false"
                                                        style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:22px;cursor:pointer;color:#aaa;line-height:1;">&times;</button>
                                                    <div style="width:64px;height:64px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                                                        <span style="color:#fff;font-size:20px;font-weight:800;letter-spacing:-0.5px;"><svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 1000 1000" fill="none">
                                                        <path d="M500 1000C719.101 1000 840.901 1000 920.45 920.45C1000 840.901 1000 719.174 1000 500C1000 280.826 1000 159.099 920.45 79.5496C840.901 0 719.101 0 500 0C280.899 0 159.099 0 79.5496 79.5496C0 159.099 0 280.899 0 500C0 719.101 0 840.901 79.5496 920.45C159.099 1000 280.899 1000 500 1000Z" fill="black"/>
                                                        <path d="M687.788 401.139C650.017 401.139 621.983 430.297 621.983 466.273C621.983 495.648 642.637 518.568 668.784 525.35L615.183 607.457H665.248L710.817 531.044H744.925V607.457H786.722V401.139H687.788ZM693.536 496.138C676.346 496.138 665.702 482.647 665.702 467.27C665.702 451.893 675.457 437.242 693.536 437.242H744.979V496.138H693.536Z" fill="white"/>
                                                        <path d="M458.891 401.139L445.019 439.327L501.939 439.001L468.229 562.632C458.619 598.68 496.19 625.916 528.25 604.718L601.163 555.252L579.693 523.7L506.871 574.146L555.957 401.139H458.891Z" fill="white"/>
                                                        <path d="M537.391 374.611C553.365 374.611 566.314 362.944 566.314 348.553C566.314 334.162 553.365 322.496 537.391 322.496C521.418 322.496 508.469 334.162 508.469 348.553C508.469 362.944 521.418 374.611 537.391 374.611Z" fill="white"/>
                                                        <path d="M389.86 569.374V353.192H229.473V468.572C229.473 522.863 213.752 556.427 204.486 569.283H185.483V663.575H226.155V607.834H378.219V663.666H418.873V569.374H389.86ZM269.91 466.197V391.543H348.154V569.247H247.153C256.31 554.269 269.91 516.154 269.91 466.197Z" fill="white"/>
                                                        <script xmlns=""/></svg>
                                                    </span>
                                                    </div>
                                                    <p style="color:#333;font-size:15px;line-height:1.6;margin:0;">
                                                        Ви можете переглянути інформацію про документи в додатку <strong>Дія</strong> з мобільного пристрою
                                                    </p>
                                                </div>
                                            </div>
                                        </template>
                                    @endif

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
