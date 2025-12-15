<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LifeScanEducation')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    @auth
        @php
            $newCertificatesCount = auth()->user()->certificates()
                ->whereNull('viewed_at')
                ->count();
        @endphp
        <div class="flex h-screen overflow-hidden">
            <aside class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                    <div class="flex items-center justify-center h-16 px-6 border-b border-gray-200">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="ml-2 text-xl font-semibold text-gray-900">LSE</span>
                        </a>
                    </div>

                    <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
                        <a href="{{ route('home') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('home') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span class="ml-3">Дашборд</span>
                        </a>

                        <a href="{{ route('student.my-courses') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.my-courses') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="ml-3">Мої курси</span>
                        </a>

                        <a href="{{ route('student.my-webinars') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.my-webinars') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="ml-3">Мої вебінари</span>
                        </a>

                        <a href="{{ route('student.catalog.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.catalog.*') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="ml-3">Каталог курсів та вебінарів</span>
                        </a>

                        <a href="{{ route('student.transactions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.transactions*') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="ml-3">Історія платежів</span>
                        </a>

                        <a href="{{ route('student.certificates') }}" class="flex items-center justify-between px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.certificates*') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <span class="ml-3">Сертифікати</span>
                            </span>
                            @if($newCertificatesCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $newCertificatesCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('student.profile.show') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition {{ request()->routeIs('student.profile.*') ? 'bg-teal-50 text-teal-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="ml-3">Профіль</span>
                        </a>

                        @if(auth()->guard('web')->check() && auth()->guard('web')->user()->id === 1)
                            <a href="{{ route('admin.courses.index') }}" class="flex items-center px-6 py-3 text-blue-600 hover:bg-blue-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="ml-3">Адмін</span>
                            </a>
                        @endif
                    </nav>

                    <div class="py-4 border-t border-gray-200">
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="ml-3">Вихід</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex flex-col flex-1 overflow-hidden">
                <header class="bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between h-16 px-6">
                        <div class="flex items-center flex-1 gap-6">
                            <button class="lg:hidden text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('student.my-courses') }}" class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Мої курси
                                </a>
                                <button type="button" onclick="openCalendarModal()" class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Календар
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <form action="{{ route('student.catalog.index') }}" method="GET" class="relative hidden md:block">
                                <input type="text" name="search" placeholder="Пошук курсів..." class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </form>

                            <a href="{{ route('student.profile.show') }}" class="relative block">
                                @if(auth()->user()->hasProfilePhoto())
                                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-10 h-10 rounded-full object-cover hover:ring-2 hover:ring-teal-500 transition">
                                @else
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-teal-500 text-white font-semibold hover:bg-teal-600 transition">
                                        {{ auth()->user()->initials }}
                                    </div>
                                @endif
                            </a>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-900 hover:text-teal-600 transition">LifeScanEducation</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('student.login') }}" class="text-gray-700 hover:text-gray-900">Вхід</a>
                        <a href="{{ route('student.register') }}" class="px-4 py-2 text-white bg-teal-500 rounded-lg hover:bg-teal-600 transition">Реєстрація</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    @endauth

    @stack('scripts')

    @auth
        @if(auth()->user() instanceof \App\Domains\Student\Models\Student)
            @include('student.dashboard.partials.calendar-modal', ['calendarData' => $calendarData ?? []])
        @endif
    @endauth
</body>
</html>
