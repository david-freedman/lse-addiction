@extends('layouts.app')

@section('title', 'Підтвердження email')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Підтвердження email</h2>
        <p class="text-gray-600 mb-6">Введіть код, який ми відправили на ваш email</p>

        <form action="{{ route('student.verify-email') }}" method="POST">
            @csrf
            @include('student.auth._partials.verification-form', [
                'type' => 'email',
                'contact' => session('student_email'),
                'buttonText' => 'Підтвердити'
            ])
        </form>

        @include('student.auth._partials.countdown-display', [
            'sessionKey' => 'email_code_expires_at'
        ])

        @include('student.auth._partials.resend-form', [
            'resendRoute' => route('student.resend-code'),
            'type' => 'email'
        ])
    </div>
</div>
@endsection
