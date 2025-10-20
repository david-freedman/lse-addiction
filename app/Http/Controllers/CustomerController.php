<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Обработка формы регистрации данных (шаг 1)
     */
    public function register(Request $request)
    {
        // Валидация данных формы
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Создание пользователя
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Логируем действие
        Log::channel('user')->info("Пользователь {$user->email} зарегистрировался.");
        Log::channel('system')->info("Создана новая запись пользователя: {$user->id}");

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    /**
     * Обработка формы добавления данных (шаг 2)
     */
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        Log::channel('user')->info("Пользователь {$user->email} обновил профиль.");
        Log::channel('system')->info("Обновлены данные пользователя: {$user->id}");

        return response()->json(['status' => 'updated', 'user' => $user]);
    }
}
