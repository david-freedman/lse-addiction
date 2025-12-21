@extends('layouts.app')

@section('title', $webinar->title)

@section('content')
<div class="space-y-6">
    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-teal-500 font-medium transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Повернутися на дашборд
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @if($webinar->banner_url)
                    <div class="w-full h-48 lg:h-64 overflow-hidden bg-gradient-to-br from-teal-400 to-teal-600">
                        <img src="{{ $webinar->banner_url }}" alt="{{ $webinar->title }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-48 lg:h-64 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @php
                        $statusClass = match($webinar->status->color()) {
                            'green' => 'bg-green-100 text-green-700',
                            'red' => 'bg-rose-100 text-rose-700',
                            'teal' => 'bg-teal-100 text-teal-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    @if($webinar->status === \App\Domains\Webinar\Enums\WebinarStatus::Live)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-600">
                            <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                            {{ $webinar->status->label() }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ $webinar->status->label() }}
                        </span>
                    @endif
                    @if($webinar->is_free)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Безкоштовно</span>
                    @endif
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $webinar->title }}</h1>
            </div>

            @if($webinar->description)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Про вебінар</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $webinar->description }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Викладач</h3>
                    <div class="flex items-center gap-3">
                        @if($webinar->teacher->avatar_url)
                            <img src="{{ $webinar->teacher->avatar_url }}" alt="{{ $webinar->teacher->full_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-lg font-medium">
                                {{ mb_substr($webinar->teacher->full_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $webinar->teacher->full_name }}</p>
                            @if($webinar->teacher->position)
                                <p class="text-sm text-gray-500">{{ $webinar->teacher->position }}</p>
                            @endif
                        </div>
                    </div>
                    @if($webinar->teacher->description)
                        <p class="mt-4 text-sm text-gray-600 leading-relaxed">{{ $webinar->teacher->description }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Деталі</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Дата</p>
                                <p class="font-semibold text-gray-900">{{ $webinar->formatted_date }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Час</p>
                                <p class="font-semibold text-gray-900">{{ $webinar->formatted_time }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Тривалість</p>
                                <p class="font-semibold text-gray-900">{{ $webinar->formatted_duration }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Учасників</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $webinar->participantsCount() }}
                                    @if($webinar->max_participants)
                                        / {{ $webinar->max_participants }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Реєстрація</h3>
                    @if($isRegistered)
                        <div class="flex items-center gap-2 text-teal-600 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Ви зареєстровані</span>
                        </div>
                        @if($webinar->status === \App\Domains\Webinar\Enums\WebinarStatus::Completed)
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-500 font-medium py-3 px-6 rounded-lg text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Вебінар завершено</span>
                            </div>
                        @elseif($meetingUrl)
                            @if($webinar->status === \App\Domains\Webinar\Enums\WebinarStatus::Live)
                                <p class="text-sm text-rose-600 font-medium mb-3 text-center">
                                    Вебінар вже триває {{ (int) $webinar->starts_at->diffInMinutes(now()) }} хв
                                </p>
                                <a href="{{ $meetingUrl }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                    </span>
                                    Приєднатися до вебінару
                                </a>
                            @else
                                <a href="{{ $meetingUrl }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Приєднатися до вебінару
                                </a>
                            @endif
                        @else
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-500 font-medium py-3 px-6 rounded-lg text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm">Посилання буде доступне за 30 хв до початку</span>
                            </div>
                        @endif
                    @else
                        @if(!$webinar->is_free)
                            <div class="text-center mb-4">
                                @if($webinar->old_price)
                                    <span class="text-gray-400 line-through text-lg">{{ number_format($webinar->old_price, 0, ',', ' ') }} ₴</span>
                                @endif
                                <p class="text-3xl font-bold text-teal-600">{{ number_format($webinar->price, 0, ',', ' ') }} ₴</p>
                            </div>
                        @endif
                        @if($webinar->hasCapacity())
                            <button
                                type="button"
                                onclick="openRegisterModal('{{ $webinar->slug }}', '{{ addslashes($webinar->title) }}', '{{ $webinar->teacher->full_name }}', '{{ $webinar->starts_at->translatedFormat('d.m.Y') }} о {{ $webinar->formatted_time }}', '{{ $webinar->formatted_duration }}', '{{ $webinar->available_spots !== null ? $webinar->available_spots : 'необмежено' }}', '{{ number_format($webinar->price, 0, ',', ' ') }} ₴', '{{ $webinar->banner_url ?? '' }}', {{ $webinar->is_free ? 'true' : 'false' }})"
                                class="w-full inline-flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition"
                            >
                                Зареєструватися
                            </button>
                        @else
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-lg">
                                Всі місця зайняті
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('student.webinar.partials.register-modal')
@endsection
