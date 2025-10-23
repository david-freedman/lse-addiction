<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Показ формы редактирования профиля
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Обновление профиля и повторная отправка письма для подтверждения email
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Валидация email
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Обновляем email
        $user->update($validated);

        // Сбрасываем верификацию и отправляем новое письмо
        $user->email_verified_at = null;
        $user->save();

        // Отправляем письмо подтверждения
        $user->sendEmailVerificationNotification();

        // Перенаправляем на страницу верификации
        return redirect()
            ->route('verification.notice')
            ->with('status', 'verification-link-sent');
    }
}
