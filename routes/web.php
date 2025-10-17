<?php

use Illuminate\Support\Facades\Route;

// Подключение контроллеров
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь регистрируются все маршруты веб-интерфейса.
| Они обрабатываются через контроллеры, которые созданы.
|
*/

/* ==============================
   Публичная часть сайта
   ============================== */

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Статические страницы (например: /page/about)
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Товары (CRUD)
Route::resource('products', ProductController::class);

// Категории (CRUD)
Route::resource('categories', CategoryController::class);

// Форма обратной связи
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');


/* ==============================
   Пользовательская часть (личный кабинет)
   ============================== */

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


/* ==============================
   Авторизация и регистрация
   ============================== */

// Вход
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Регистрация
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Выход
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
