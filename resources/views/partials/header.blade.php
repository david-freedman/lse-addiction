@vite(['resources/css/app.css', 'resources/js/app.js'])

<header class="bg-white shadow-sm">
    <nav class="header-nav container mx-auto flex items-center justify-between py-3 px-6">

        <!-- Левая часть: ссылки -->
        <div class="header-left flex items-center space-x-6">
            <a href="/" class="text-gray-700 font-medium hover:text-blue-600 transition">LifeScanEducation</a>
            <a href="/cart" class="text-gray-700 font-medium hover:text-blue-600 transition">Мои курсы</a>
        </div>

        <!-- Средняя часть: Поиск -->
        <div class="search-form flex items-center">
            <form
                action="{{ route('product.search') }}"
                method="GET"
                data-base-url="{{ route('products.index') }}"
                class="flex items-center bg-gray-50 border border-gray-200 rounded-full px-3 py-1.5 shadow-sm hover:shadow-md transition focus-within:ring-2 focus-within:ring-blue-300"
            >
                <span class="text-gray-500 mr-2">🔍</span>
                <input
                    type="text"
                    name="query"
                    placeholder="Поиск курсов.."
                    class="bg-transparent border-none outline-none text-sm text-gray-700 w-48 focus:w-64 transition-all duration-300"
                >
                <button
                    type="submit"
                    class="ml-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm px-4 py-1.5 rounded-full font-semibold shadow-sm hover:shadow-md hover:from-blue-600 hover:to-indigo-600 transition"
                >
                    Найти
                </button>
            </form>
        </div>

        <!-- Правая часть: Аутентификация -->
        <div class="header-right flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}" class="auth-btn btn-login bg-blue-50 text-blue-700 font-medium px-4 py-1.5 rounded-md hover:bg-blue-100 transition">
                    Вход
                </a>
                <a href="{{ route('register') }}" class="auth-btn btn-register bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold px-4 py-1.5 rounded-md shadow-sm hover:shadow-md hover:from-blue-600 hover:to-indigo-600 transition">
                    Регистрация
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="auth-btn btn-account text-gray-700 hover:text-blue-600 transition">
                    Личный кабинет
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="auth-btn btn-logout text-red-500 font-medium hover:text-red-700 transition">
                        Выход
                    </button>
                </form>
            @endguest
        </div>

    </nav>
</header>
