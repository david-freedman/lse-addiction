@extends('layouts.new-app')

@section('title', 'Події у запісі')

@section('content')
<main class="main-page">
    <x-breadcrumbs :items="[
        ['url' => route('home'), 'title' => 'Головна'],
        ['title' => 'Події у запісі']
    ]" />

    <section class="main-page__courses courses">
        <div class="courses__container">
            <h1 class="courses__title title">
                події у запісі
            </h1>

            <div class="courses__nav">
                <a href="{{ $viewModel->filterUrl(App\Domains\Course\Enums\CourseType::Upcoming) }}"
                   class="courses__link button {{ $viewModel->isUpcomingFilter() ? 'button--fill' : '' }}">
                    Майбутні події
                </a>
                <a href="{{ $viewModel->filterUrl(App\Domains\Course\Enums\CourseType::Recorded) }}"
                   class="courses__link button {{ $viewModel->isRecordedFilter() ? 'button--fill' : '' }}">
                    Курси у запісі
                </a>
                <a href="{{ $viewModel->filterUrl(App\Domains\Course\Enums\CourseType::Free) }}"
                   class="courses__link button {{ $viewModel->isFreeFilter() ? 'button--fill' : '' }}">
                    безкоштовні
                </a>
            </div>

            @if($viewModel->hasNoCourses())
                <div class="courses__empty">
                    <p>На даний момент немає доступних курсів у цій категорії.</p>
                </div>
            @else
                <div class="courses__items">
                    @foreach($viewModel->courses() as $course)
                        <div class="courses__item">
                            <x-course-card :course="$course" />
                        </div>
                    @endforeach
                </div>

                {{ $viewModel->courses()->links('pagination.courses') }}

                @if($viewModel->courses()->hasMorePages())
                    <a href="{{ $viewModel->courses()->nextPageUrl() }}" class="courses__more button button--fill">
                        Показати ще
                    </a>
                @endif
            @endif
        </div>
    </section>

    <x-faq-section />
</main>
@endsection
