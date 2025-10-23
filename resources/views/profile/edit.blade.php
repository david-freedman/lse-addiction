@extends('layouts.app')

@section('content')
    <div class="container max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Редактирование профиля</h1>

        {{-- ✅ Уведомление об успешном обновлении --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Email:</label>
                <input
                    type="email"
                    name="email"
                    value="{{ $user->email }}"
                    class="border rounded w-full p-2"
                >
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Сохранить изменения
            </button>
        </form>
    </div>
@endsection
