@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-16">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">
                Подтвердите e-mail
            </h1>

            <p class="text-gray-600 text-center mb-6">
                Мы отправили письмо со ссылкой для подтверждения вашего адреса электронной почты.
                Если письмо не пришло, вы можете запросить его повторно.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded text-sm mb-6 text-center">
                    Новая ссылка подтверждения отправлена на ваш e-mail.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                @csrf
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition"
                >
                    Отправить письмо ещё раз
                </button>
            </form>

            <p class="text-sm text-gray-500 text-center mt-6">
                Указали неправильный e-mail?
                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">
                    Измените адрес в профиле
                </a>
            </p>
        </div>
    </div>
@endsection
