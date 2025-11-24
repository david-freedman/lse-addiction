<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Адмін Панель') - LSE</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body
    x-data="{ sidebarToggle: false }"
    x-init="$watch('sidebarToggle', value => localStorage.setItem('sidebarToggle', JSON.stringify(value))); sidebarToggle = JSON.parse(localStorage.getItem('sidebarToggle') || 'false')"
    class="bg-gray-50"
>
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            @include('admin.partials.header')

            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @include('admin.partials.breadcrumb')

                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
