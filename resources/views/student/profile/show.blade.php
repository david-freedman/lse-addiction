@extends('layouts.app')

@section('title', 'Профіль')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Мій профіль</h1>
        <p class="mt-1 text-sm text-gray-600">Перегляд вашої особистої інформації</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-8">
            <div class="flex items-center gap-6 mb-8 pb-8 border-b border-gray-200">
                @if($viewModel->hasProfilePhoto())
                    <img src="{{ $viewModel->profilePhotoUrl() }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-white text-2xl font-semibold">{{ $viewModel->initials() }}</span>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $viewModel->name() }} {{ $viewModel->surname() }}</h2>
                    <p class="text-gray-600">{{ $viewModel->email() }}</p>
                    @unless($viewModel->isFullyVerified())
                        <div class="mt-2 inline-flex items-center gap-1 px-3 py-1 bg-yellow-50 text-yellow-700 text-sm rounded-full">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Необхідно підтвердити контактні дані
                        </div>
                    @endunless
                </div>
                <a href="{{ route('student.profile.edit') }}" class="px-4 py-2 text-sm font-medium text-teal-600 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition">
                    Редагувати профіль
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Прізвище</h3>
                    <p class="text-base text-gray-900">{{ $viewModel->surname() ?? 'Не вказано' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Ім'я</h3>
                    <p class="text-base text-gray-900">{{ $viewModel->name() ?? 'Не вказано' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Дата народження</h3>
                    <p class="text-base text-gray-900">{{ $viewModel->birthday() ?? 'Не вказано' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Місто</h3>
                    <p class="text-base text-gray-900">{{ $viewModel->city() ?? 'Не вказано' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">ID студента</h3>
                    <p class="text-base text-gray-900">{{ $viewModel->number() }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Контактні дані</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                        <div class="flex items-center gap-2">
                            <p class="text-base text-gray-900">{{ $viewModel->email() }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Телефон</h4>
                        <div class="flex items-center gap-2">
                            <p class="text-base text-gray-900">{{ $viewModel->phone() }}</p>
                            @if($viewModel->isPhoneVerified())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Підтверджено
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                    Не підтверджено
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(count($viewModel->profileFields()) > 0)
                <div class="border-t border-gray-200 pt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Додаткова інформація</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($viewModel->profileFields() as $label => $value)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">{{ $label }}</h4>
                                <p class="text-base text-gray-900">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Підключені акаунти</h3>
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @error('google')
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.35-8.16 2.35-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Google</p>
                        @if($viewModel->hasGoogleLinked())
                            <p class="text-xs text-green-600">Прив'язано</p>
                        @else
                            <p class="text-xs text-gray-500">Не прив'язано</p>
                        @endif
                    </div>
                </div>
                @if($viewModel->hasGoogleLinked())
                    @if($viewModel->canUnlinkGoogle())
                        <form method="POST" action="{{ route('student.profile.google.unlink') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                Відв'язати
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-gray-400">Для відв'язки додайте телефон</span>
                    @endif
                @else
                    <a href="{{ route('student.profile.google.link') }}" class="px-3 py-1.5 text-sm text-teal-600 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
                        Прив'язати
                    </a>
                @endif
            </div>
        </div>
    </div>

    @unless($viewModel->hasContactDetails())
        <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium">Заповніть всі особисті дані</p>
                    <p class="text-sm mt-1">Для повного доступу до платформи необхідно заповнити всі поля профілю.</p>
                </div>
            </div>
        </div>
    @endunless

    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-600 mb-1">Небезпечна зона</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Видалення акаунту - це незворотна дія. Всі ваші дані, курси, прогрес та сертифікати будуть назавжди видалені.
                    </p>
                    <button type="button" onclick="openDeleteAccountModal()" class="inline-flex items-center gap-2 px-4 py-2 border border-red-600 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Видалити акаунт
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('student.profile.partials.delete-account-modal')
@endsection
