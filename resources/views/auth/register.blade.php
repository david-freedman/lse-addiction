@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Регистрация</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Имя:</label>
                <input type="text" name="name" class="border rounded w-full p-2" value="{{ old('name') }}" required>
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email:</label>
                <input type="email" name="email" class="border rounded w-full p-2" value="{{ old('email') }}" required>
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">Пароль:</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
                @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">Подтверждение пароля:</label>
                <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>
            </div>

            <!-- Телефон -->
            <div class="mb-4">
                <label for="telephone" class="block mb-1">Телефон:</label>
                <input
                    type="text"
                    id="telephone"
                    name="telephone"
                    value="{{ old('telephone') }}"
                    required
                    placeholder="+380671234567"
                    class="border rounded w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('telephone')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Зарегистрироваться
            </button>

            <p class="mt-4 text-sm text-center text-gray-600">
                Уже есть аккаунт?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                    Войдите
                </a>
            </p>
        </form>
    </div>
@endsection
