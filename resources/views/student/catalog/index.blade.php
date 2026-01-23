@extends('layouts.app')

@section('title', 'Каталог курсів та вебінарів')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Каталог курсів та вебінарів</h1>
        <p class="text-gray-600">Знайдіть ідеальний курс або запишіться на вебінар</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form action="{{ route('student.catalog.index') }}" method="GET" class="flex items-center gap-4">
            @if($tab === 'webinars')
                <input type="hidden" name="tab" value="webinars">
            @endif
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $tab === 'webinars' ? 'Пошук вебінарів за назвою...' : 'Пошук курсів за назвою...' }}"
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
            class="px-4 py-3 font-semibold transition-colors {{ $tab === 'courses' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-gray-900' }}"
        >
            Курси ({{ $coursesCount }})
        </a>
        <a
            href="{{ route('student.catalog.index', ['tab' => 'webinars']) }}"
            class="px-4 py-3 font-semibold transition-colors {{ $tab === 'webinars' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-gray-900' }}"
        >
            Вебінари ({{ $webinarsCount }})
        </a>
    </div>

    @if($tab === 'webinars')
        <div class="mb-6 flex items-center gap-2">
            <a
                href="{{ route('student.catalog.index', ['tab' => 'webinars', 'webinar_filter' => 'all', 'search' => request('search')]) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $webinarFilter === 'all' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                Всі ({{ $webinarsCount }})
            </a>
            <a
                href="{{ route('student.catalog.index', ['tab' => 'webinars', 'webinar_filter' => 'live', 'search' => request('search')]) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $webinarFilter === 'live' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                Наживу ({{ $liveWebinarsCount }})
            </a>
            <a
                href="{{ route('student.catalog.index', ['tab' => 'webinars', 'webinar_filter' => 'recorded', 'search' => request('search')]) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $webinarFilter === 'recorded' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                У записі ({{ $recordedWebinarsCount }})
            </a>
        </div>
    @endif

    @if($tab === 'courses')
        @if($courses->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Курсів не знайдено</h3>
                <p class="text-gray-600">Наразі немає доступних курсів</p>
            </div>
        @else
            <div class="grid gap-6 mb-8" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));">
                @foreach($courses as $course)
                    <x-course-card :course="$course" :show-purchase-button="true" :individual-discount="$course->individual_discount ?? null" />
                @endforeach
            </div>

            @if($courses->hasPages())
                <div class="flex justify-center">
                    {{ $courses->links() }}
                </div>
            @endif
        @endif
    @else
        @if($webinars->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Вебінарів не знайдено</h3>
                <p class="text-gray-600">Наразі немає запланованих вебінарів</p>
            </div>
        @else
            <div class="grid gap-6 mb-8" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 350px), 1fr));">
                @foreach($webinars as $webinar)
                    <x-catalog-webinar-card :webinar="$webinar" :is-registered="$webinar->is_registered" />
                @endforeach
            </div>

            @if($webinars->hasPages())
                <div class="flex justify-center">
                    {{ $webinars->links() }}
                </div>
            @endif
        @endif
    @endif
</div>

@if($tab === 'courses')
    @include('student.catalog.partials.purchase-modal')
@else
    @include('student.webinar.partials.register-modal')
@endif
@endsection
