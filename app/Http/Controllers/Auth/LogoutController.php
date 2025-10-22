<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Выход пользователя из системы
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Разлогиниваем пользователя

        $request->session()->invalidate(); // Сбрасываем сессию
        $request->session()->regenerateToken(); // Обновляем CSRF-токен

        return redirect('/')->with('status', 'Вы успешно вышли из системы.');
    }
}
