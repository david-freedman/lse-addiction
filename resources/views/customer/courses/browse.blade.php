@extends('layouts.app')

@section('title', 'Доступні курси')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Доступні курси</h1>
        <a href="{{ route('customer.my-courses') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Мої курси
        </a>
    </div>

    @if($viewModel->hasNoCourses())
        <div class="bg-white shadow-md rounded-lg px-8 py-12 text-center">
            <p class="text-gray-600">На даний момент немає доступних курсів.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($viewModel->courses() as $course)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                    @if($course->banner)
                        <div class="w-full aspect-[16/9] overflow-hidden bg-gray-100">
                            <img src="{{ Storage::disk('public')->url($course->banner) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full aspect-[16/9] bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">{{ substr($course->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="px-6 py-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($course->description, 150) }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg text-gray-900 font-bold">{{ number_format($course->price, 2, ',', ' ') }} грн</span>
                            <span class="text-sm text-gray-500">{{ $course->coach->name }}</span>
                        </div>

                        @if($course->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($course->tags->take(3) as $tag)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('customer.courses.show', $course) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Детальніше
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $viewModel->courses()->links() }}
        </div>
    @endif
</div>
@endsection
