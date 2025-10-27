<?php

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SendVerifyEmailController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerValidationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Подключение контроллеров


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

// Поиск товаров
Route::get('/products/search', [ProductController::class, 'search'])->name('product.search');

/* ==============================
   Пользовательская часть (личный кабинет)
   ============================== */

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Управление профилем клиента (CRUD)
Route::resource('customers', CustomerController::class)
    ->middleware('auth');

//  Добавляем маршрут валидации клиента
Route::post('/customer/validate', [CustomerValidationController::class, 'validateCustomer'])
    ->name('customer.validate');

//  Добавляем маршруты для форм (регистрация и обновление данных)
Route::post('/customer/register', [CustomerController::class, 'register'])
    ->name('customer.register');

Route::post('/customer/update/{id}', [CustomerController::class, 'updateProfile'])
    ->name('customer.update');

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

// ===============================
// Маршруты верификации email
// ===============================
Route::middleware(['auth'])->group(function () {

    //  Страница уведомления о необходимости подтвердить email
    //  Сообщение пользователю: "Проверьте почту, подтвердите email"
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    // Ссылка из письма подтверждения
    // Laravel автоматически генерирует URL с {id} и {hash}, защищённый подписью
    // После перехода пользователь считается верифицированным
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    // Повторная отправка письма
    // Нажатие кнопки “Отправить письмо ещё раз” на /verify-email
    Route::post('verify-email', SendVerifyEmailController::class)->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

// ===============================
// Маршруты профиля пользователя
// ===============================

// Страница редактирования профиля (текущий email, и другие данные)
Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

// Обновление профиля — сохраняет изменения и отправляет письмо для подтверждения
Route::post('/profile/update', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');
