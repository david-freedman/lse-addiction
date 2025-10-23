@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">
            Результаты поиска:
            <span class="text-blue-600">"{{ $query }}"</span>
        </h1>

        @if ($products->isEmpty())
            <p class="text-gray-600">Ничего не найдено 😕</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                        <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                        <p class="text-gray-600 mb-3">{{ Str::limit($product->description, 100) }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:underline">
                            Подробнее →
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
