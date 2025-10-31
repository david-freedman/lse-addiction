@extends('layouts.app')

@section('title', 'Завершення верифікації')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Завершення верифікації</h2>
        <p class="text-gray-600 mb-6">
            @if($verificationStep === 'phone')
                Для завершення реєстрації підтвердіть ваш номер телефону. Код відправлено на {{ $contact }}
            @else
                Для завершення реєстрації підтвердіть вашу електронну пошту. Код відправлено на {{ $contact }}
            @endif
        </p>

        @if(session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('customer.complete-verification.verify') }}" method="POST">
            @csrf
            @include('customer.auth._partials.verification-form', [
                'type' => $verificationStep,
                'contact' => $contact,
                'buttonText' => 'Підтвердити'
            ])
        </form>

        @include('customer.auth._partials.countdown-display', [
            'sessionKey' => $verificationStep . '_code_expires_at'
        ])

        <form action="{{ route('customer.complete-verification.resend') }}" method="POST" class="mt-2 text-center">
            @csrf
            <button
                id="resend-button"
                type="submit"
                class="text-blue-500 hover:text-blue-700 text-sm underline disabled:text-gray-400 disabled:cursor-not-allowed disabled:no-underline"
            >
                Відправити код знову
            </button>
            @error('resend')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

@if(session('next_resend_at'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextResendAt = {{ session('next_resend_at') }};
            if (window.initResendCountdown) {
                window.initResendCountdown(nextResendAt, 'resend-button');
            }
        });
    </script>
@endif
@endsection
