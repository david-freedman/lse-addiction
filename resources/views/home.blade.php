@extends('layouts.app')

@section('title', 'Головна - LSE Addiction')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h1 class="text-5xl font-bold text-gray-900 mb-6">
            LSE Addiction
        </h1>

        <p class="text-xl text-gray-600 mb-12 max-w-2xl mx-auto">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec ultricies velit. Nunc rhoncus tellus et mauris eleifend, id euismod neque hendrerit.
        </p>

        @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('customer.login') }}"
                   class="bg-white hover:bg-gray-50 text-blue-600 font-bold py-3 px-8 rounded-lg border-2 border-blue-600 transition duration-200 w-full sm:w-auto">
                    Увійти
                </a>
                <a href="{{ route('customer.register') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl w-full sm:w-auto">
                    Зареєструватися
                </a>
            </div>
        @else
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
                <p class="text-gray-600 mb-6">Ви вже увійшли в систему</p>
                <a href="{{ route('customer.profile.show') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 inline-block">
                    Перейти до профілю
                </a>
            </div>
        @endguest
    </div>
</div>
@endsection
