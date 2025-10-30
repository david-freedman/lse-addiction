<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LSE Addiction')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition">LSE Addiction</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('customer.profile.show') }}" class="text-gray-700 hover:text-gray-900">Профіль</a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Вихід</button>
                        </form>
                    @else
                        <a href="{{ route('customer.login') }}" class="text-gray-700 hover:text-gray-900">Вхід</a>
                        <a href="{{ route('customer.register') }}" class="text-gray-700 hover:text-gray-900">Реєстрація</a>
                    @endauth
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
</body>
</html>
