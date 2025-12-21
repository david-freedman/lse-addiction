<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <a href="{{ route('student.dashboard') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="LifeScanEducation" class="ibg ibg--contain">
            </a>
        </div>
        <div class="header__menu menu">
            <nav class="menu__body">
                <ul data-spoilers="1199.98, max">
                    <li><a href="{{ route('student.dashboard') }}#about">Про нас</a></li>
                    <li>
                        <details>
                            <summary>
                                Навчання у нас
                            </summary>
                            <ul class="sublist">
                                <li>
                                    <details>
                                        <summary>
                                            лікарям
                                        </summary>
                                        <ul class="sublist">
                                            <li>
                                                <a href="{{ route('student.courses.browse') }}?category=doctors&type=upcoming">майбутні події</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('student.courses.browse') }}?category=doctors&type=recorded">події у запісі</a>
                                            </li>
                                        </ul>
                                    </details>
                                </li>
                                <li>
                                    <details>
                                        <summary>
                                            рентгенлаборантам
                                        </summary>
                                        <ul class="sublist">
                                            <li>
                                                <a href="{{ route('student.courses.browse') }}?category=radiologists&type=upcoming">майбутні події</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('student.courses.browse') }}?category=radiologists&type=recorded">події у запісі</a>
                                            </li>
                                        </ul>
                                    </details>
                                </li>
                                <li>
                                    <a href="{{ route('student.courses.browse') }}?type=intercourse">
                                        інтеркурс у нас
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('student.courses.browse') }}?type=free">
                                        безкоштовно
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('student.courses.browse') }}?type=cases">
                                        цікави випадки
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li><a href="{{ route('student.dashboard') }}#speakers">Наші лектори</a></li>
                    <li><a href="{{ route('student.dashboard') }}#faq">FAQ</a></li>
                </ul>
                <a href="#" class="header__search-btn _icon-search">Пошук</a>
                @auth
                    <a href="{{ route('student.profile.show') }}" class="header__button button">Особистий кабінет</a>
                    <form action="{{ route('student.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="header__button button button--outline">Вихід</button>
                    </form>
                @else
                    <a href="{{ route('student.login') }}" class="header__button button">Особистий кабінет</a>
                @endauth
            </nav>
        </div>
        <button data-menu-toggle type="button" class="header__icon icon-menu"><span></span></button>
    </div>
</header>
