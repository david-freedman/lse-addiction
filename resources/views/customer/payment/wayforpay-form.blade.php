@extends('layouts.app')

@section('title', 'Оплата')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-teal-100 rounded-lg p-3">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Оплата замовлення</h1>
                <p class="text-sm text-gray-500">Безпечна оплата через WayForPay</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Номер замовлення:</span>
                <span class="font-bold text-gray-900">{{ $paymentData->orderReference }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-600">Сума до оплати:</span>
                <span class="font-bold text-2xl text-teal-600">{{ number_format($paymentData->amount, 2) }} {{ $paymentData->currency }}</span>
            </div>
            <div class="border-t border-gray-200 mt-4 pt-4">
                <p class="text-sm text-gray-600 mb-2">Товари:</p>
                @foreach($paymentData->products as $product)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">{{ $product->name }} × {{ $product->count }}</span>
                        <span class="text-gray-900">{{ number_format($product->price, 2) }} {{ $paymentData->currency }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <form id="wayforpay-form" method="POST" action="{{ config('payment.wayforpay.api_url') }}" accept-charset="utf-8">
            <input type="hidden" name="merchantAccount" value="{{ $paymentData->merchantAccount }}">
            <input type="hidden" name="merchantDomainName" value="{{ $paymentData->merchantDomainName }}">
            <input type="hidden" name="merchantSignature" value="{{ $paymentData->merchantSignature }}">
            <input type="hidden" name="orderReference" value="{{ $paymentData->orderReference }}">
            <input type="hidden" name="orderDate" value="{{ $paymentData->orderDate }}">
            <input type="hidden" name="amount" value="{{ $paymentData->amount }}">
            <input type="hidden" name="currency" value="{{ $paymentData->currency }}">
            <input type="hidden" name="merchantTransactionSecureType" value="{{ $paymentData->merchantTransactionSecureType }}">
            <input type="hidden" name="returnUrl" value="{{ $paymentData->returnUrl }}">
            <input type="hidden" name="serviceUrl" value="{{ $paymentData->serviceUrl }}">
            <input type="hidden" name="clientFirstName" value="{{ $paymentData->clientFirstName }}">
            <input type="hidden" name="clientLastName" value="{{ $paymentData->clientLastName }}">
            <input type="hidden" name="clientPhone" value="{{ $paymentData->clientPhone }}">
            <input type="hidden" name="clientEmail" value="{{ $paymentData->clientEmail }}">

            @if($paymentData->language)
                <input type="hidden" name="language" value="{{ $paymentData->language }}">
            @endif

            @if($paymentData->apiVersion)
                <input type="hidden" name="apiVersion" value="{{ $paymentData->apiVersion }}">
            @endif

            @if($paymentData->clientAccountId)
                <input type="hidden" name="clientAccountId" value="{{ $paymentData->clientAccountId }}">
            @endif

            @if($paymentData->orderTimeout)
                <input type="hidden" name="orderTimeout" value="{{ $paymentData->orderTimeout }}">
            @endif

            @if($paymentData->paymentSystems)
                <input type="hidden" name="paymentSystems" value="{{ $paymentData->paymentSystems }}">
            @endif

            @if($paymentData->defaultPaymentSystem)
                <input type="hidden" name="defaultPaymentSystem" value="{{ $paymentData->defaultPaymentSystem }}">
            @endif

            @foreach($paymentData->products as $index => $product)
                <input type="hidden" name="productName[]" value="{{ $product->name }}">
                <input type="hidden" name="productPrice[]" value="{{ $product->price }}">
                <input type="hidden" name="productCount[]" value="{{ $product->count }}">
            @endforeach

            <div class="space-y-4">
                <p class="text-sm text-gray-600 text-center mb-4">
                    Після натискання кнопки "Перейти до оплати" ви будете перенаправлені на безпечну сторінку оплати WayForPay
                </p>

                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-4 px-6 rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Перейти до оплати
                </button>
            </div>
        </form>

        <form action="{{ route('customer.payment.cancel', $paymentData->orderReference) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="block w-full text-center border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-lg transition">
                Скасувати
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-start gap-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div>
                    <p class="font-medium text-gray-900 mb-1">Безпечна оплата</p>
                    <p class="text-xs">Ваші платіжні дані захищені за допомогою SSL-шифрування. Ми не зберігаємо інформацію про вашу картку.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
