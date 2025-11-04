@extends('layouts.app')

@section('title', 'Профіль')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Мій профіль</h2>
            <a href="{{ route('customer.profile.edit') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Редагувати
            </a>
        </div>

        <div class="space-y-4">
            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Прізвище</label>
                <span class="text-gray-900">{{ $viewModel->surname() ?? 'Не вказано' }}</span>
            </div>

            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Ім'я</label>
                <span class="text-gray-900">{{ $viewModel->name() ?? 'Не вказано' }}</span>
            </div>

            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Дата народження</label>
                <span class="text-gray-900">{{ $viewModel->birthday() ?? 'Не вказано' }}</span>
            </div>

            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Місто</label>
                <span class="text-gray-900">{{ $viewModel->city() ?? 'Не вказано' }}</span>
            </div>

            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Email</label>
                <div class="flex items-center justify-between">
                    <span class="text-gray-900">{{ $viewModel->email() }}</span>
                    @if($viewModel->isEmailVerified())
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Підтверджено
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Не підтверджено
                        </span>
                    @endif
                </div>
                @if($viewModel->emailVerifiedAt())
                    <p class="text-xs text-gray-500 mt-1">Підтверджено: {{ $viewModel->emailVerifiedAt() }}</p>
                @endif
            </div>

            <div class="border-b pb-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Телефон</label>
                <div class="flex items-center justify-between">
                    <span class="text-gray-900">{{ $viewModel->phone() }}</span>
                    @if($viewModel->isPhoneVerified())
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Підтверджено
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Не підтверджено
                        </span>
                    @endif
                </div>
                @if($viewModel->phoneVerifiedAt())
                    <p class="text-xs text-gray-500 mt-1">Підтверджено: {{ $viewModel->phoneVerifiedAt() }}</p>
                @endif
            </div>

            @if(count($viewModel->profileFields()) > 0)
                <div class="pt-4 mt-4">
                    <h3 class="text-lg font-bold mb-4 text-gray-900">Додаткова інформація</h3>

                    @foreach($viewModel->profileFields() as $label => $value)
                        <div class="border-b pb-4 mb-4">
                            <label class="block text-gray-600 text-sm font-bold mb-2">{{ $label }}</label>
                            <span class="text-gray-900">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="pt-4">
                @unless($viewModel->isFullyVerified())
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">
                        Будь ласка, підтвердіть всі контактні дані
                    </div>
                @endunless

                @unless($viewModel->hasContactDetails())
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mt-2">
                        Будь ласка, заповніть всі особисті дані
                    </div>
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection
