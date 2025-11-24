@extends('admin.auth.layouts.guest')

@section('title', 'Вхід')
@section('heading', 'Вхід для адміністраторів')

@section('content')
<form class="space-y-5" method="POST" action="{{ route('admin.login.send') }}">
    @csrf

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
            Email адреса
        </label>
        <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required
            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            placeholder="admin@example.com"
            value="{{ old('email') }}"
        >
        @error('email')
            <p class="mt-1.5 text-sm text-error-600">{{ $message }}</p>
        @enderror
    </div>

    @error('rate_limit')
        <div class="rounded-lg border border-error-200 bg-error-50 px-4 py-3">
            <p class="text-sm text-error-700">{{ $message }}</p>
        </div>
    @enderror

    <button
        type="submit"
        class="w-full rounded-lg bg-brand-500 px-5 py-3 text-center text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20"
    >
        Отримати код
    </button>
</form>
@endsection
