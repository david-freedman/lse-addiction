@props(['course'])

<div class="card-course">
    <a href="{{ route('customer.courses.show', $course) }}" class="card-course__image">
        @if($course->tags->isNotEmpty())
            <div class="card-course__tag">
                <span>{{ $course->tags->first()->name }}
                    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
                        <defs>
                            <filter id="goo">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />
                                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -4" result="textBlob" />
                                <feComposite in="SourceGraphic" in2="textBlob" operator="atop" />
                            </filter>
                        </defs>
                    </svg>
                </span>
            </div>
        @endif

        <div class="card-course__body">
            @if($course->label)
                <div class="card-course__label">
                    {{ $course->label }}
                </div>
            @endif

            @if($course->formatted_date)
                <div class="card-course__date">
                    {{ $course->formatted_date }}
                </div>
            @endif
        </div>

        <div class="card-course__logo">
            <img src="{{ asset('img/logo-white.svg') }}" alt="image" class="ibg ibg-contain">
        </div>
    </a>

    <div class="card-course__content">
        <h3 class="card-course__title">
            <a href="{{ route('customer.courses.show', $course) }}">{{ $course->name }}</a>
        </h3>
        <a href="{{ route('customer.courses.show', $course) }}" class="card-course__button button button--fill">
            Детальніше про вебінар
        </a>
    </div>
</div>
