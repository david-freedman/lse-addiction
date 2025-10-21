@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-lg mt-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Проверка данных клиента</h1>

        {{-- Сообщения об успехе / ошибках --}}
        <div id="alert-box" class="hidden mb-4 p-3 rounded"></div>

        {{-- Форма проверки --}}
        <form id="customerForm" action="{{ route('customer.validate') }}" method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div>
                <label for="first_name" class="block font-semibold mb-1">Имя</label>
                <input type="text" id="first_name" name="first_name" placeholder="Введите имя"
                       class="border rounded p-2 w-full focus:ring-2 focus:ring-blue-400">
                <small id="error_first_name" class="text-red-500 text-sm"></small>
            </div>

            <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Введите email"
                       class="border rounded p-2 w-full focus:ring-2 focus:ring-blue-400">
                <small id="error_email" class="text-red-500 text-sm"></small>
            </div>

            <div>
                <label for="phone" class="block font-semibold mb-1">Телефон</label>
                <input type="text" id="phone" name="phone" placeholder="+380..."
                       class="border rounded p-2 w-full focus:ring-2 focus:ring-blue-400">
                <small id="error_phone" class="text-red-500 text-sm"></small>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
                Проверить
            </button>
        </form>
    </div>

    {{-- JavaScript для AJAX-валидации --}}
    <script>
        document.querySelector('#customerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const alertBox = document.getElementById('alert-box');
            const formData = new FormData(form);

            // Очищаем старые ошибки
            ['first_name', 'email', 'phone'].forEach(field => {
                document.getElementById('error_' + field).textContent = '';
            });

            // Отправляем AJAX-запрос на сервер
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            console.log(data);

            // Очистить старое сообщение
            alertBox.classList.add('hidden');
            alertBox.textContent = '';

            if (data.status === 'error') {
                // Показ ошибок под каждым полем
                for (const [field, messages] of Object.entries(data.errors)) {
                    const errorEl = document.getElementById('error_' + field);
                    if (errorEl) errorEl.textContent = messages.join(', ');
                }

                // Показ уведомления об ошибке
                alertBox.className = 'mb-4 p-3 rounded bg-red-100 text-red-700';
                alertBox.textContent = 'Исправьте ошибки в форме.';
                alertBox.classList.remove('hidden');
            } else {
                // Успешная валидация
                alertBox.className = 'mb-4 p-3 rounded bg-green-100 text-green-700';
                alertBox.textContent = data.message || 'Данные успешно проверены!';
                alertBox.classList.remove('hidden');

                 //Здесь автоматически вызывается регистрация (второй шаг)
                const registerResponse = await fetch('/customer/register', { method: 'POST', body: formData });
                const registerData = await registerResponse.json();
                alert(registerData.message);
            }
        });
    </script>
@endsection
