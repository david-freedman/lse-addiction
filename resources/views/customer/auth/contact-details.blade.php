@extends('layouts.app')

@section('title', 'Контактні дані')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
        <h2 class="text-2xl font-bold mb-2 text-gray-900">Контактні дані</h2>
        <p class="text-gray-600 mb-6">Заповніть, будь ласка, додаткову інформацію про себе</p>


        <form action="{{ route('customer.contact-details') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="surname" class="block text-gray-700 text-sm font-bold mb-2">Прізвище</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('surname') border-red-500 @enderror"
                    required>
                @error('surname')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Ім'я</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="birthday" class="block text-gray-700 text-sm font-bold mb-2">Дата народження</label>
                <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('birthday') border-red-500 @enderror"
                    required>
                @error('birthday')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Місто</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror"
                    required>
                @error('city')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Продовжити
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
