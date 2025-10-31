@extends('layouts.app')

@section('title', 'Підтвердження входу')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Підтвердження входу</h2>
        <p class="text-gray-600 mb-6">Введіть код, який ми відправили на {{ $type === 'email' ? 'ваш email' : 'ваш телефон' }}</p>

        <form action="{{ route('customer.verify-login') }}" method="POST">
            @csrf
            @include('customer.auth._partials.verification-form', [
                'type' => $type,
                'contact' => $contact,
                'buttonText' => 'Увійти'
            ])
        </form>
    </div>
</div>
@endsection
