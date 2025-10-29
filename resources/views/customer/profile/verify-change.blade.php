@extends('layouts.app')

@section('title', 'Підтвердження зміни')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Підтвердження зміни</h2>
        <p class="text-gray-600 mb-6">
            Введіть код, який ми відправили на {{ $type === 'email' ? 'новий email' : 'новий телефон' }}: <strong>{{ $contact }}</strong>
        </p>

        <form action="{{ route('customer.verify-change') }}" method="POST">
            @csrf

            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="contact" value="{{ $contact }}">

            <div class="mb-6">
                <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Код підтвердження</label>
                <input type="text" name="code" id="code" maxlength="6"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-center text-2xl tracking-widest leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror"
                    required autofocus>
                @error('code')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Підтвердити
            </button>
        </form>
    </div>
</div>
@endsection
