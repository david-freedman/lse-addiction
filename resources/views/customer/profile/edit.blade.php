@extends('layouts.app')

@section('title', 'Редагувати профіль')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Редагувати профіль</h2>

        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Новий Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Поточний: {{ $customer->email }}</p>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Новий Телефон</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+380XXXXXXXXX"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Поточний: {{ $customer->phone }}</p>
                @error('phone')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6">
                <p class="text-sm">Після зміни email або телефону вам буде відправлено код підтвердження</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Зберегти зміни
                </button>
                <a href="{{ route('customer.profile.show') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Скасувати
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
