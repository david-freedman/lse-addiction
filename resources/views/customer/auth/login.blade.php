@extends('layouts.app')

@section('title', 'Вхід')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Вхід</h2>

        <form action="{{ route('customer.login.send') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="contact" class="block text-gray-700 text-sm font-bold mb-2">Email або телефон</label>
                <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('contact') border-red-500 @enderror"
                    required autofocus>
                @error('contact')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Отримати код
                </button>
                <a href="{{ route('customer.register') }}" class="text-sm text-blue-500 hover:text-blue-700">
                    Немає акаунта?
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
