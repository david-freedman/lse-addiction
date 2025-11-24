@extends('admin.auth.layouts.guest')

@section('title', 'Введіть код')
@section('heading', 'Введіть код верифікації')

@section('content')
<div class="mb-5 rounded-lg bg-gray-50 px-4 py-3">
    <p class="text-center text-sm text-gray-600">
        Код відправлено на <strong class="text-gray-900">{{ $email }}</strong>
    </p>
</div>

@if(session('success'))
    <div class="mb-5 rounded-lg border border-success-200 bg-success-50 px-4 py-3">
        <p class="text-sm text-success-700">{{ session('success') }}</p>
    </div>
@endif

<form class="space-y-5" method="POST" action="{{ route('admin.verify-login') }}">
    @csrf

    <div>
        <label for="code" class="mb-2 block text-sm font-medium text-gray-700">
            Код верифікації
        </label>
        <input
            id="code"
            name="code"
            type="text"
            maxlength="4"
            required
            autofocus
            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-center text-2xl tracking-widest text-gray-900 outline-none transition focus:border-brand-500 focus:bg-white"
            placeholder="----"
        >
        @error('code')
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
        Увійти
    </button>
</form>

<div class="mt-4 text-center">
    <form method="POST" action="{{ route('admin.verify-login.resend') }}" class="inline">
        @csrf
        <button
            type="submit"
            class="text-sm font-medium text-brand-600 transition hover:text-brand-700 hover:underline"
        >
            Відправити код повторно
        </button>
    </form>
</div>
@endsection
