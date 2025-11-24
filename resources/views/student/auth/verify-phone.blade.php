@extends('layouts.app')

@section('title', 'Підтвердження телефону')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Підтвердження телефону</h2>
        <p class="text-gray-600 mb-6">Введіть код, який ми відправили на ваш телефон</p>

        <form action="{{ route('student.verify-phone') }}" method="POST">
            @csrf
            @include('student.auth._partials.verification-form', [
                'type' => 'phone',
                'contact' => session('student_phone'),
                'buttonText' => 'Підтвердити'
            ])
        </form>

        @include('student.auth._partials.countdown-display', [
            'sessionKey' => 'phone_code_expires_at'
        ])

        @include('student.auth._partials.resend-form', [
            'resendRoute' => route('student.resend-code'),
            'type' => 'phone'
        ])
    </div>
</div>
@endsection
