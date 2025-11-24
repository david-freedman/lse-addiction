@extends('layouts.app')

@section('title', 'Каталог курсів та вебінарів')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Каталог курсів та вебінарів</h1>
        <p class="text-gray-600">Знайдіть ідеальний курс або запишіться на вебінар</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form action="{{ route('student.catalog.index') }}" method="GET" class="flex items-center gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    placeholder="Пошук курсів за назвою..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                >
            </div>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                Пошук
            </button>
        </form>
    </div>

    <div class="mb-6 flex items-center gap-4 border-b border-gray-200">
        <a
            href="{{ route('student.catalog.index') }}"
            class="px-4 py-3 font-semibold transition-colors {{ request('type') === null ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-gray-900' }}"
        >
            Курси в записі ({{ $recordedCount }})
        </a>
        <a
            href="{{ route('student.catalog.index', ['type' => 'upcoming']) }}"
            class="px-4 py-3 font-semibold transition-colors {{ request('type') === 'upcoming' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-gray-900' }}"
        >
            Майбутні вебінари ({{ $upcomingCount }})
        </a>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Курсів не знайдено</h3>
            <p class="text-gray-600">Наразі немає доступних курсів у цій категорії</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($courses as $course)
                <x-course-card :course="$course" :show-purchase-button="true" />
            @endforeach
        </div>

        @if($courses->hasPages())
            <div class="flex justify-center">
                {{ $courses->links() }}
            </div>
        @endif
    @endif
</div>

@include('student.catalog.partials.purchase-modal')
@endsection
