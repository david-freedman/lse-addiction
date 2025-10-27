@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-gray-50">
        <!-- Левое меню -->
        <aside class="w-64 bg-white border-r p-6">
            <nav class="space-y-4">
                <a href="#" class="flex items-center text-cyan-700 font-medium">
                    <i class="fa-regular fa-grid mr-2"></i> Дашборд
                </a>
                <a href="#" class="flex items-center text-gray-600 hover:text-cyan-700">
                    <i class="fa-regular fa-book mr-2"></i> Мої курси
                </a>
                <a href="#" class="flex items-center text-gray-600 hover:text-cyan-700">
                    <i class="fa-regular fa-clock mr-2"></i> Історія платежів
                </a>
                <a href="#" class="flex items-center text-gray-600 hover:text-cyan-700">
                    <i class="fa-regular fa-certificate mr-2"></i> Сертифікати
                </a>
                <a href="#" class="flex items-center text-gray-600 hover:text-cyan-700">
                    <i class="fa-regular fa-user mr-2"></i> Профіль
                </a>
            </nav>
        </aside>

        <!-- Контент -->
        <main class="flex-1 p-10">
            <!-- Приветствие -->
            <div class="bg-gradient-to-r from-cyan-600 to-teal-500 text-white rounded-2xl p-8 mb-10 shadow">
                <h1 class="text-2xl font-bold mb-2">Добрий вечір, {{ Auth::user()->name }}! 👋</h1>
                <p>Ласкаво просимо на освітню платформу LSE. Продовжуйте навчання і розвивайтеся разом з нами.</p>
            </div>

            <!-- Карточки статистики -->
            <div class="grid grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <p class="text-gray-500">Курси в процесі</p>
                    <h2 class="text-3xl font-bold mt-2 text-cyan-700">3</h2>
                    <p class="text-sm text-green-500 mt-1">+1 за цю тиждень</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <p class="text-gray-500">Завершені курси</p>
                    <h2 class="text-3xl font-bold mt-2 text-cyan-700">5</h2>
                    <p class="text-sm text-green-500 mt-1">+1 за цю тиждень</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <p class="text-gray-500">Години навчання</p>
                    <h2 class="text-3xl font-bold mt-2 text-cyan-700">124</h2>
                    <p class="text-sm text-green-500 mt-1">+12 за цю тиждень</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <p class="text-gray-500">Сертифікатів</p>
                    <h2 class="text-3xl font-bold mt-2 text-cyan-700">3</h2>
                    <p class="text-sm text-orange-500 mt-1">+2 нових</p>
                </div>
            </div>

            <!-- Мої курси -->
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2 bg-white rounded-xl p-6 shadow">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Мої курси</h2>

                    <div class="mb-6">
                        <p class="font-medium text-gray-700">Дегенеративні захворювання хребта</p>
                        <p class="text-sm text-gray-500 mb-2">12 годин до наступного уроку</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
                            <div class="bg-cyan-600 h-2.5 rounded-full" style="width: 75%"></div>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">Продовжити</button>
                    </div>

                    <div>
                        <p class="font-medium text-gray-700">Радіологічна анатомія печінки</p>
                        <p class="text-sm text-gray-500 mb-2">5 годин до наступного уроку</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
                            <div class="bg-cyan-600 h-2.5 rounded-full" style="width: 50%"></div>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">Продовжити</button>
                    </div>
                </div>

                <!-- Блок вебінарів -->
                <div class="bg-white rounded-xl p-6 shadow">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Найближчі вебінари</h2>
                    <div class="border-b pb-4 mb-4">
                        <p class="font-medium text-gray-800">МРТ та КТ: візуалізація печінки</p>
                        <p class="text-sm text-gray-500">Викладач: Дереш Н.В. | 25 жовтня о 14:00</p>
                        <button class="mt-3 bg-white border border-cyan-600 text-cyan-600 px-3 py-2 rounded-lg hover:bg-cyan-50">
                            Продовжити навчання
                        </button>
                    </div>

                    <div>
                        <p class="font-medium text-gray-800">Дегенеративні захворювання хребта (частина 2)</p>
                        <p class="text-sm text-gray-500">Викладач: Чумак Р.А. | 29 жовтня о 19:00</p>
                        <button class="mt-3 bg-cyan-600 text-white px-3 py-2 rounded-lg hover:bg-cyan-700">
                            Зареєструватися
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
