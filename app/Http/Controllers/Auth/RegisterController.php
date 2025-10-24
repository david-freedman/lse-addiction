<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Показ формы регистрации
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Обработка регистрации пользователя
     */
    public function register(Request $request)
    {
        // --- 1. Валидация данных ---
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'telephone'             => ['required'], // формат проверим вручную ниже
        ]);

        // --- 2. Очистка номера ---
        $telephoneDigits = preg_replace('/\D/', '', $request->input('telephone')); // только цифры

        // --- 3. Проверка длины ---
        if (empty($telephoneDigits) || strlen($telephoneDigits) != 12) {
            return back()
                ->withErrors(['telephone' => 'Введите корректный номер телефона в формате 12 цифр, например 380671234567'])
                ->withInput();
        }

        // --- 4. Создание пользователя ---
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $telephoneDigits,
            'password'  => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        // --- 5. Вход и редирект ---
        Auth::login($user);

//        return redirect('/dashboard')->with('success', 'Регистрация успешна!');
        return redirect()->route('verification.notice')->with('success', 'Регистрация успешна! Проверьте email.');
    }
}
