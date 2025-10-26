@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Вход в аккаунт</h1>

        {{-- === Универсальная форма входа === --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Email или телефон:</label>
                <input type="text" name="login" id="login" class="border rounded w-full p-2" required autofocus placeholder="example@mail.com или +380671234567">
            </div>

            {{-- Поле для кода (появляется при вводе телефона) --}}
            <div id="codeField" class="mb-4 hidden">
                <label class="block mb-1">Введите код из SMS:</label>
                <input type="text" name="code" id="code" class="border rounded w-full p-2" placeholder="Введите 1111">
                <p id="codeError" class="text-red-600 text-sm mt-1 hidden">Неверный код. Для входа введите 1111.</p>
            </div>

            @error('login')
            <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <button type="submit" id="loginBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Войти</button>

            <p class="mt-4 text-sm text-center text-gray-600">
                Ещё нет аккаунта?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Зарегистрируйтесь</a>
            </p>
        </form>
    </div>

    {{-- ===== JS логика ===== --}}
    <script>
        (function(){
            const loginInput = document.getElementById('login');
            const codeField = document.getElementById('codeField');
            const codeInput = document.getElementById('code');
            const codeError = document.getElementById('codeError');
            const form = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');

            // Если пользователь ввёл телефон — показываем поле для кода
            loginInput.addEventListener('input', () => {
                const val = loginInput.value.trim();
                codeField.classList.toggle('hidden', !val.match(/^\+?\d{10,13}$/));
            });

            // Проверка кода перед отправкой формы
            form.addEventListener('submit', (e) => {
                const val = loginInput.value.trim();
                if (val.match(/^\+?\d{10,13}$/)) {
                    // Телефон → требуем код
                    const code = codeInput.value.trim();
                    if (code !== '1111') {
                        e.preventDefault();
                        codeError.classList.remove('hidden');
                        return false;
                    }
                }
            });
        })();
    </script>
@endsection
