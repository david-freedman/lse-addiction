@extends('layouts.app')

@section('title', $viewModel->name())

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $viewModel->name() }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.courses.edit', $course) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Редагувати
            </a>
            <a href="{{ route('admin.courses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Назад до списку
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        @if($viewModel->bannerUrl())
            <div class="w-full aspect-[21/9] overflow-hidden bg-gray-100">
                <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->name() }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="px-8 py-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Статус</label>
                    @if($course->status === 'published')
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded">{{ $viewModel->statusLabel() }}</span>
                    @elseif($course->status === 'draft')
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded">{{ $viewModel->statusLabel() }}</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded">{{ $viewModel->statusLabel() }}</span>
                    @endif
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Ціна</label>
                    <p class="text-gray-900 text-lg font-semibold">{{ $viewModel->price() }} грн</p>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Коуч</label>
                    <p class="text-gray-900">{{ $viewModel->coachName() }}</p>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Записано учасників</label>
                    <p class="text-gray-900">{{ $viewModel->enrolledCount() }}</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-bold mb-2">Опис</label>
                <div class="text-gray-900 prose">
                    {!! nl2br(e($viewModel->description())) !!}
                </div>
            </div>

            @if(!empty($viewModel->tags()))
                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Теги</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($viewModel->tags() as $tag)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="border-t pt-4">
                <label class="block text-gray-600 text-sm font-bold mb-2">Створено</label>
                <p class="text-gray-700 text-sm">{{ $viewModel->createdAt() }}</p>
            </div>
        </div>
    </div>

    @if($course->customers->isNotEmpty())
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-8 py-4 bg-gray-50 border-b">
                <h2 class="text-xl font-bold text-gray-900">Записані учасники</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ім'я</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата запису</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($course->customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ $customer->name }} {{ $customer->surname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $customer->email->value }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $customer->pivot->enrolled_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($customer->pivot->status === 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Активний</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">Скасовано</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
