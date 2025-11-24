<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Вхід') - LSE</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <main class="flex min-h-screen items-center justify-center">
                <div class="w-full max-w-md px-4">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-md">
                        <div class="border-b border-gray-200 px-6 py-5 text-center">
                            <div class="mb-2">
                                <span class="text-2xl font-bold text-brand-600">LSE</span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                @yield('heading', 'Адмін Панель')
                            </h2>
                        </div>

                        <div class="px-6 py-6">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
