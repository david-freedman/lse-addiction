@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Вход в аккаунт</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Email:</label>
                <input type="email" name="email" class="border rounded w-full p-2" required autofocus>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Пароль:</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
            </div>

            @error('email')
            <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Войти
            </button>

            <p class="mt-4 text-sm text-center text-gray-600">
                Ещё нет аккаунта?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                    Зарегистрируйтесь
                </a>
            </p>

        </form>
    </div>
@endsection
