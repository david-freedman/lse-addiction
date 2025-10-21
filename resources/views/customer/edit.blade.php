@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-3xl font-bold mb-4">Мой профиль</h1>

        <form method="POST" action="{{ route('customers.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Имя</label>
                <input type="text" name="name" value="{{ $user->name }}" class="border rounded p-2 w-full">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="border rounded p-2 w-full">
            </div>

            <div class="mb-3">
                <label>Телефон</label>
                <input type="text" name="phone" value="{{ $user->phone }}" class="border rounded p-2 w-full">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Сохранить</button>
        </form>
    </div>
@endsection
