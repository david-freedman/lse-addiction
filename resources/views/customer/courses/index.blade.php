@extends('layouts.app')

@section('title', 'Мої курси')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Мої курси</h1>
        <a href="{{ route('customer.courses.browse') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Переглянути всі курси
        </a>
    </div>

    @if($viewModel->hasNoCourses())
        <div class="bg-white shadow-md rounded-lg px-8 py-12 text-center">
            <p class="text-gray-600 mb-4">Ви ще не записані на жоден курс.</p>
            <a href="{{ route('customer.courses.browse') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                Переглянути доступні курси
            </a>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg px-8 py-6 mb-4">
            <p class="text-gray-600">Всього курсів: <span class="font-semibold text-gray-900">{{ $viewModel->coursesCount() }}</span></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($viewModel->courses() as $course)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                    @if($course->banner)
                        <div class="w-full aspect-[16/9] overflow-hidden bg-gray-100">
                            <img src="{{ Storage::disk('public')->url($course->banner) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Без банера</span>
                        </div>
                    @endif

                    <div class="px-6 py-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($course->description, 150) }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-700 font-semibold">{{ number_format($course->price, 2, ',', ' ') }} грн</span>
                            <span class="text-sm text-gray-500">{{ $course->coach->name }}</span>
                        </div>

                        @if($course->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($course->tags->take(3) as $tag)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Активний</span>
                            <a href="{{ route('customer.courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Детальніше →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
