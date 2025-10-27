@vite(['resources/css/app.css', 'resources/js/app.js'])

<header class="bg-white border-b shadow-sm">
    <nav class="flex items-center justify-between h-16 px-10">

        <!-- Левая часть -->
        <div class="flex items-center space-x-8">
            <a href="/" class="flex items-baseline space-x-1 select-none">
                <span class="font-[Poppins] italic text-cyan-700 text-2xl tracking-wide leading-none">
                    LifeScanEducation
                </span>
            </a>

            <!-- Мої курси -->
            <a href="/cart"
               class="text-teal-600 text-sm font-medium hover:text-teal-700 transition-colors duration-200">
                Мої курси
            </a>
        </div>

        <!-- Центр: поиск -->
        <div class="flex items-center w-1/2 justify-center">
            <form
                action="{{ route('product.search') }}"
                method="GET"
                data-base-url="{{ route('products.index') }}"
                class="flex items-center bg-gray-50 border border-gray-200 rounded-full px-4 py-2 w-80 focus-within:ring-2 focus-within:ring-teal-400 shadow-sm transition"
            >
                <i class="fa-regular fa-magnifying-glass text-gray-400 mr-3"></i>
                <input
                    type="text"
                    name="query"
                    placeholder="Пошук курсів..."
                    class="bg-transparent border-none outline-none w-full text-sm text-gray-700 placeholder-gray-400"
                >
                <button
                    type="submit"
                    class="ml-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm px-5 py-1.5 rounded-full font-semibold hover:from-teal-700 hover:to-cyan-700 transition"
                >
                    Знайти
                </button>
            </form>
        </div>

        <!-- Правая часть: аутентификация -->
        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}"
                   class="bg-teal-50 text-teal-700 text-sm font-medium px-4 py-1.5 rounded-md hover:bg-teal-100 transition">
                    Вхід
                </a>
                <a href="{{ route('register') }}"
                   class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-semibold px-4 py-1.5 rounded-md shadow-sm hover:from-teal-700 hover:to-cyan-700 transition">
                    Реєстрація
                </a>
            @else
                <!-- Кабінет -->
                <a href="{{ route('dashboard') }}"
                   class="text-teal-600 text-sm font-medium hover:text-teal-700 transition-colors duration-200">
                    Кабінет
                </a>

                <!-- Вихід -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="text-teal-600 text-sm font-medium hover:text-teal-700 transition-colors duration-200">
                        Вихід
                    </button>
                </form>
            @endguest
        </div>

    </nav>
</header>
