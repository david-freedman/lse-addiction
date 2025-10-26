<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Показ формы входа
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка входа (email или телефон)
     */
    public function login(Request $request)
    {
        $login = $request->input('login');

        // === Вариант входа по телефону (эмуляция) ===
        if (preg_match('/^\+?\d{10,13}$/', $login)) {
            $digits = preg_replace('/\D/', '', $login);
            $user = User::where('telephone', $digits)->first();

            if (!$user) {
                return back()->withErrors(['login' => 'Пользователь с таким номером не найден.']);
            }

            if ($request->input('code') !== '1111') {
                return back()->withErrors(['login' => 'Неверный код. Для входа введите 1111.']);
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // === Вариант входа по email ===
        $request->validate([
            'login' => ['required', 'email'],
        ]);

        $user = User::where('email', $login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Пользователь с таким email не найден.']);
        }

        // Логиним пользователя, даже если email ещё не подтверждён
        Auth::login($user);
        $request->session()->regenerate();

        // Проверяем верификацию email
        if (!$user->hasVerifiedEmail()) {
            // если email не подтверждён → показываем страницу verify-email
            return redirect()->route('verification.notice')
                ->with('status', 'Проверьте почту и подтвердите email.');
        }

        // Если email подтверждён → впускаем в кабинет
        return redirect()->intended('/dashboard');
    }

    /**
     * Выход
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
