@extends('layouts.app')

@section('content')
    <div class="container max-w-3xl mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Личный кабинет</h1>

        <p class="text-lg">
            Добро пожаловать, <strong>{{ $user->name ?? 'Гость' }}</strong>!
        </p>

        <p class="mt-4 text-gray-600">
            Здесь в будущем появится информация о ваших заказах, уведомления и настройки профиля.
        </p>
    </div>
@endsection
