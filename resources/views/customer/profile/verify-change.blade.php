@extends('layouts.app')

@section('title', 'Підтвердження зміни')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Підтвердження зміни</h1>
        <p class="mt-1 text-sm text-gray-600">Введіть код для підтвердження зміни контактних даних</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($type === 'email')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        @endif
                    </svg>
                </div>
                <p class="text-base text-gray-700">
                    Ми відправили код підтвердження на {{ $type === 'email' ? 'новий email' : 'новий телефон' }}:
                </p>
                <p class="text-lg font-semibold text-gray-900 mt-2">{{ $contact }}</p>
                <p class="text-sm text-gray-500 mt-2">Код дійсний протягом 10 хвилин</p>
            </div>

            <form action="{{ route('customer.verify-change') }}" method="POST" class="max-w-md mx-auto">
                @csrf

                <x-verification.form
                    :type="$type"
                    :contact="$contact"
                    buttonText="Підтвердити"
                    buttonColor="teal"
                />

                <div class="mt-4">
                    <a href="{{ route('customer.profile.edit') }}"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Повернутися до профілю
                    </a>
                </div>
            </form>

            <x-verification.resend-button
                :route="route('customer.verify-change.resend', ['type' => $type])"
                :type="$type"
                buttonColor="teal"
            />
        </div>
    </div>
</div>
@endsection
