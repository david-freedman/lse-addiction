<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Главная страница личного кабинета
     */
    public function index()
    {
        $user = Auth::user(); // Получаем текущего пользователя

        // Можно передать данные в шаблон, например имя
        return view('dashboard.index', compact('user'));
    }
}
