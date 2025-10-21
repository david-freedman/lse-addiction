<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * ===========================================
     * 1️⃣ Метод регистрации нового пользователя
     * ===========================================
     * Получает данные формы регистрации (имя, email, пароль),
     * выполняет валидацию, создаёт нового пользователя
     * и записывает это событие в лог.
     *
     * URL: POST /customer/register
     * Используется в форме регистрации (AJAX или стандартной)
     */
    public function register(Request $request)
    {
        // --- Валидация данных формы ---
        // Проверяем, что:
        //  - name обязательно, строка, до 255 символов
        //  - email корректный, уникален в таблице users
        //  - password не короче 6 символов и совпадает с подтверждением
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // --- Создание нового пользователя в БД ---
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']), // хэшируем пароль
        ]);

        // --- Запись в пользовательский и системный лог ---
        Log::channel('user')->info("Пользователь {$user->email} зарегистрировался.");
        Log::channel('system')->info("Создана новая запись пользователя: {$user->id}");

        // --- Ответ в формате JSON ---
        // Возвращается при AJAX-запросе
        return response()->json(['status' => 'success', 'user' => $user]);
    }

    /**
     * ===========================================
     * 2️⃣ Метод обновления профиля (AJAX)
     * ===========================================
     * Принимает дополнительные данные профиля (телефон, город, адрес),
     * выполняет валидацию и обновляет запись пользователя в таблице `users`.
     *
     * URL: POST /customer/update/{id}
     * Используется в личном кабинете (обычно AJAX-запросом).
     */
    public function updateProfile(Request $request, $id)
    {
        // --- Поиск пользователя по ID ---
        $user = User::findOrFail($id);

        // --- Валидация входных данных ---
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
        ]);

        // --- Обновление данных в таблице users ---
        $user->update($validated);

        // --- Логирование изменений ---
        Log::channel('user')->info("Пользователь {$user->email} обновил профиль (updateProfile).");
        Log::channel('system')->info("Обновлены данные пользователя: {$user->id}");

        // --- Возврат ответа в формате JSON ---
        return response()->json(['status' => 'updated', 'user' => $user]);
    }

    /**
     * ===========================================
     * 3️⃣ Метод отображения формы редактирования
     * ===========================================
     * Отображает Blade-шаблон `customers/edit.blade.php`
     * и передаёт в него данные текущего пользователя.
     *
     * URL: GET /customers/{id}/edit
     * Автоматически используется Laravel при маршруте:
     * Route::resource('customers', CustomerController::class)
     */
    public function edit($id)
    {
        // --- Получаем пользователя по ID ---
        $user = User::findOrFail($id);

        // --- Передаём данные в шаблон customers/edit.blade.php ---
        return view('customers.edit', compact('user'));
    }

    /**
     * ===========================================
     * 4️⃣ Метод обработки формы редактирования
     * ===========================================
     * Получает данные из формы редактирования профиля (edit.blade.php),
     * проверяет их, сохраняет изменения и возвращает пользователя обратно
     * на ту же страницу с сообщением об успешном обновлении.
     *
     * URL: PUT /customers/{id}
     * Автоматически вызывается при сабмите формы
     * <form method="POST" action="{{ route('customers.update', $user->id) }}">
     */
    public function update(Request $request, $id)
    {
        // --- Получаем пользователя из базы ---
        $user = User::findOrFail($id);

        // --- Валидация данных ---
        // Email должен быть уникальным, но допускаем текущий email этого пользователя
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        // --- Сохраняем изменения ---
        $user->update($validated);

        // --- Логирование действий ---
        Log::channel('user')->info("Пользователь {$user->email} обновил профиль через форму edit.");
        Log::channel('system')->info("Обновлены данные пользователя: {$user->id}");

        // --- Возврат на ту же страницу с флеш-сообщением ---
        return redirect()->route('customers.edit', $user->id)
            ->with('success', 'Профиль успешно обновлён!');
    }
}
