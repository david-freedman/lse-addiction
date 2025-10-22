@extends('layouts.app')

@section('content')
    <div class="container">
        <p class="mt-4">Добро пожаловать, {{ Auth::user()->name  ?? 'Гость' }}!</p>
    </div>
@endsection
