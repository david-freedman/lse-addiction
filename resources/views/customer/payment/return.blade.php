@extends('layouts.app')

@section('title', 'Результат оплати')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        @if($status === 'success')
            <div class="text-center">
                <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">Оплата успішна!</h1>
                <p class="text-gray-600 mb-6">{{ $message }}</p>

                @if(isset($transaction))
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Номер транзакції:</span>
                            <span class="font-bold text-gray-900">{{ $transaction->transaction_number }}</span>
                        </div>
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Сума:</span>
                            <span class="font-bold text-gray-900">{{ $transaction->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Дата:</span>
                            <span class="font-bold text-gray-900">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                @endif

                <div class="space-y-3">
                    <a href="{{ route('customer.my-courses') }}" class="block w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Перейти до моїх курсів
                    </a>
                    <a href="{{ route('customer.transactions') }}" class="block w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg transition">
                        Переглянути транзакції
                    </a>
                </div>
            </div>
        @elseif($status === 'pending')
            <div class="text-center">
                <div class="bg-yellow-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">Оплата обробляється</h1>
                <p class="text-gray-600 mb-6">{{ $message }}</p>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        Зазвичай обробка платежу займає до 5 хвилин. Ви отримаєте повідомлення на email, коли оплата буде завершена.
                    </p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('customer.transactions') }}" class="block w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Переглянути статус транзакції
                    </a>
                    <a href="{{ route('home') }}" class="block w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg transition">
                        Повернутися на головну
                    </a>
                </div>
            </div>
        @else
            <div class="text-center">
                <div class="bg-red-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">Помилка оплати</h1>
                <p class="text-gray-600 mb-6">{{ $message }}</p>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-red-800">
                        Якщо гроші були списані з вашого рахунку, вони будуть автоматично повернені протягом 3-5 робочих днів.
                    </p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('customer.transactions') }}" class="block w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Спробувати ще раз
                    </a>
                    <a href="{{ route('home') }}" class="block w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg transition">
                        Повернутися на головну
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
