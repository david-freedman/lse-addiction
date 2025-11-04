@extends('layouts.app')

@section('title', $viewModel->name())

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $viewModel->name() }}</h1>
        <a href="{{ route('customer.my-courses') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Назад
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        @if($viewModel->bannerUrl())
            <div class="w-full aspect-[21/9] overflow-hidden bg-gray-100">
                <img src="{{ $viewModel->bannerUrl() }}" alt="{{ $viewModel->name() }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="px-8 py-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $viewModel->price() }} грн</p>
                    <p class="text-gray-600">Коуч: <span class="font-semibold">{{ $viewModel->coachName() }}</span></p>
                </div>

                <div class="text-right">
                    @if($viewModel->isEnrolled())
                        <span class="inline-block px-4 py-2 bg-green-100 text-green-800 font-semibold rounded mb-2">
                            Ви записані на цей курс
                        </span>
                        <form action="{{ route('customer.courses.unenroll', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Ви впевнені, що хочете скасувати запис на цей курс?')">
                                Скасувати запис
                            </button>
                        </form>
                    @elseif($viewModel->canEnroll())
                        <form action="{{ route('customer.courses.enroll', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Записатися на курс
                            </button>
                        </form>
                    @elseif(!$viewModel->isPublished())
                        <span class="inline-block px-4 py-2 bg-gray-100 text-gray-600 rounded">
                            Курс недоступний
                        </span>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Про курс</h2>
                <div class="text-gray-700 leading-relaxed prose max-w-none">
                    {!! nl2br(e($viewModel->description())) !!}
                </div>
            </div>

            @if(!empty($viewModel->tags()))
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Теги</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($viewModel->tags() as $tag)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="border-t pt-4">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Записано учасників:</span> {{ $viewModel->enrolledCount() }}
                    </div>
                    <div>
                        <span class="font-semibold">Статус:</span>
                        @if($course->status === 'published')
                            <span class="text-green-600 font-semibold">{{ $viewModel->statusLabel() }}</span>
                        @else
                            <span class="text-gray-600">{{ $viewModel->statusLabel() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
