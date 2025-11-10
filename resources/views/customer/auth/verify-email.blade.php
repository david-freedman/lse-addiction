@extends('layouts.app')

@section('title', 'Підтвердження email')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Підтвердження email</h2>
        <p class="text-gray-600 mb-6">Введіть код, який ми відправили на ваш email</p>

        <form action="{{ route('customer.verify-email') }}" method="POST">
            @csrf
            @include('customer.auth._partials.verification-form', [
                'type' => 'email',
                'contact' => session('customer_email'),
                'buttonText' => 'Підтвердити'
            ])
        </form>

        @include('customer.auth._partials.countdown-display', [
            'sessionKey' => 'email_code_expires_at'
        ])

        @include('customer.auth._partials.resend-form', [
            'resendRoute' => route('customer.resend-code'),
            'type' => 'email'
        ])
    </div>
</div>
@endsection
