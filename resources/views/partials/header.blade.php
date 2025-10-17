@vite(['resources/css/app.css', 'resources/js/app.js'])

<header>
    <nav class="header-nav">
        <!-- Левая часть: Главная -->
        <div class="header-left">
            <a href="/">Главная</a>
        </div>

        <!-- Средняя часть: Поиск с иконкой -->
{{--        <div class="search-form">--}}
{{--            <form--}}
{{--                action="{{ route('product.search') }}"--}}
{{--                method="GET"--}}
{{--                data-base-url="{{ route('products.index') }}"--}}
{{--            >--}}
{{--                <span class="search-icon" aria-label="Поиск">🔍</span>--}}
{{--                <input type="text" name="query" placeholder="Поиск товаров...">--}}
{{--                <button type="submit">Найти</button>--}}
{{--            </form>--}}
{{--        </div>--}}

        <!-- Правая часть: Аутентификация -->
{{--        <div class="header-right">--}}
{{--            @guest--}}
{{--                <a href="{{ route('login') }}" class="auth-btn btn-login">Вход</a>--}}
{{--                <a href="{{ route('register') }}" class="auth-btn btn-register">Регистрация</a>--}}
{{--            @else--}}
{{--                <a href="{{ route('dashboard') }}" class="auth-btn btn-account">Личный кабинет</a>--}}
{{--                <form action="{{ route('logout') }}" method="POST" style="display: inline;">--}}
{{--                    @csrf--}}
{{--                    <button type="submit" class="auth-btn btn-logout">Выход</button>--}}
{{--                </form>--}}
{{--            @endguest--}}
{{--        </div>--}}
    </nav>
</header>
