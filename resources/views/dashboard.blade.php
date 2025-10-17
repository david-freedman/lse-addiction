@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-3xl font-bold mt-6">Личный кабинет</h1>
        <p class="mt-4">Добро пожаловать, {{ Auth::user()->name  ?? 'Гость' }}!</p>
    </div>
@endsection
