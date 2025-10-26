@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Регистрация</h1>

        {{-- === Форма регистрации без пароля === --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Имя --}}
            <div class="mb-4">
                <label class="block mb-1">Имя:</label>
                <input type="text" name="name" class="border rounded w-full p-2" value="{{ old('name') }}" required>
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block mb-1">Email:</label>
                <input type="email" name="email" class="border rounded w-full p-2" value="{{ old('email') }}" required>
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Телефон --}}
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

            {{-- Скрытое поле для фиксации подтверждения телефона --}}
            <input type="hidden" name="phone_verified" id="phone_verified" value="0">

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Зарегистрироваться
            </button>

            <p class="mt-4 text-sm text-center text-gray-600">
                Уже есть аккаунт?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Войдите</a>
            </p>
        </form>
    </div>

    {{-- ===== Модалка подтверждения телефона (эмуляция) ===== --}}
    <div id="phoneModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative bg-white w-[95%] max-w-md mx-auto rounded-xl shadow-xl p-6">
            <h2 class="text-xl font-semibold mb-2">
                Мы отправили код на номер <span id="maskedPhone">+38 (0XX) XXX-XX-XX</span>
            </h2>
            <p class="text-gray-600 mb-4">Введите код из SMS</p>

            <input
                type="text"
                id="smsCode"
                inputmode="numeric"
                maxlength="6"
                class="border rounded w-full p-3 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Код (введите 1111)"
            />

            <div class="flex gap-2">
                <button id="confirmCodeBtn" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Подтвердить</button>
                <button id="cancelModalBtn" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Отмена</button>
            </div>

            <p id="codeError" class="text-red-600 text-sm mt-3 hidden">
                Неверный код. Для эмуляции введите <b>1111</b>.
            </p>
        </div>
    </div>

    {{-- ===== JS логика модалки ===== --}}
    <script>
        (function () {
            const form = document.querySelector('form[action="{{ route('register') }}"]');
            const phoneInput = document.getElementById('telephone');
            const phoneVerifiedField = document.getElementById('phone_verified');
            const modal = document.getElementById('phoneModal');
            const confirmBtn = document.getElementById('confirmCodeBtn');
            const cancelBtn = document.getElementById('cancelModalBtn');
            const codeInput = document.getElementById('smsCode');
            const codeError = document.getElementById('codeError');
            const maskedPhoneEl = document.getElementById('maskedPhone');

            // Формат +38 (067) 776-59-81
            function maskPhone(raw) {
                const d = (raw || '').replace(/\D/g, '');
                if (d.length >= 12) return `+${d.slice(0,2)} (${d.slice(2,5)}) ${d.slice(5,8)}-${d.slice(8,10)}-${d.slice(10,12)}`;
                return '+38 (0XX) XXX-XX-XX';
            }

            function openModal() {
                maskedPhoneEl.textContent = maskPhone(phoneInput.value);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                codeInput.value = '';
                codeError.classList.add('hidden');
                codeInput.focus();
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Показываем модалку при сабмите
            form.addEventListener('submit', function (e) {
                if (phoneVerifiedField.value === '1') return;
                e.preventDefault();
                openModal();
            }, { capture: true });

            confirmBtn.addEventListener('click', function () {
                const code = codeInput.value.trim();
                if (code === '1111') {
                    phoneVerifiedField.value = '1';
                    closeModal();
                    form.submit();
                } else {
                    codeError.classList.remove('hidden');
                }
            });

            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        })();
    </script>
@endsection
