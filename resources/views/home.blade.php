@extends('layouts.new-app')

@section('content')
<main class="main-page">
    <div class="main-page__home home">
        <div class="home__hero hero">
            <div class="hero__video">
                <video autoplay muted loop playsinline>
                    <source src="{{ asset('video/hero-video.mp4') }}" type="video/mp4">
                </video>
            </div>
        </div>

        <section class="home__courses courses-home">
            <div class="courses-home__container">
                <h2 class="courses-home__title title">Курси, що стартують незабаром</h2>
                <div class="courses-home__items">
                    @foreach($courses as $course)
                        <div class="courses-home__item item-course">
                            <img src="{{ asset('img/' . $course->image) }}" alt="{{ $course->name }}" class="item-course__bg">
                            <h3 class="item-course__title title">{{ $course->name }}</h3>
                            <div class="item-course__text text">
                                <p>{{ $course->description }}</p>
                            </div>
                            <div class="item-course__footer">
                                @if($course->label)
                                    <div class="item-course__time">{{ $course->label }}</div>
                                @endif
                                <a href="{{ route('customer.courses.show', $course) }}" class="item-course__button button">Перейти</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="home__form-section form-section">
            <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="form-section__bg">
            <div class="form-section__container">
                <div class="form-section__body">
                    <h2 class="form-section__title">форма реєстрації</h2>
                    <div class="form-section__text">Залиште заявку і адміністратор підбере для Вас зручний час</div>
                </div>
                <a href="{{ route('customer.register') }}" class="form-section__button button">Зареєструватись</a>
            </div>
        </section>

        <section class="home__about about-home">
            <div class="about-home__container">
                <div class="about-home__header">
                    <div class="about-home__body">
                        <h2 class="about-home__title">про нас</h2>
                        <h3 class="about-home__subtitle title">Ласкаво просимо на платформу, яка змінює правила гри у світі радіології!</h3>
                    </div>
                    <div class="about-home__text text">
                        <p>Ми створили простір, де знання не знають меж, а навчання стає доступним для кожного спеціаліста.</p>
                    </div>
                </div>

                <div class="about-home__items">
                    <div class="about-home__item item-about-h">
                        <div class="item-about-h__image">
                            <img src="{{ asset('img/about-home/01.webp') }}" alt="Наша мета" class="ibg">
                        </div>
                        <div class="item-about-h__body">
                            <h3 class="item-about-h__title">Наша мета</h3>
                            <div class="item-about-h__text text">
                                <p>Створити освітнє середовище, де кожен лікар-рентгенолог може розвиватися професійно незалежно від локації та часу.</p>
                            </div>
                        </div>
                    </div>

                    <div class="about-home__item item-about-h">
                        <div class="item-about-h__image">
                            <img src="{{ asset('img/about-home/02.webp') }}" alt="Чому це важливо?" class="ibg">
                        </div>
                        <div class="item-about-h__body">
                            <h3 class="item-about-h__title">Чому це важливо?</h3>
                            <div class="item-about-h__text text">
                                <p>Якісна радіологія - це основа точної діагностики. Ми допомагаємо лікарям бути впевненими у своїх знаннях.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-home__quote">
                    <p>Наші матеріали розроблені провідними фахівцями галузі та адаптовані під сучасні виклики медицини.</p>
                </div>

                <div class="about-home__image">
                    <img src="{{ asset('img/about-home/main.webp') }}" alt="Про нас" class="ibg">
                </div>

                <div class="about-home__label text">
                    <p>Приєднуйтесь до спільноти професіоналів, які прагнуть розвитку та вдосконалення!</p>
                </div>
            </div>
        </section>

        <section class="home__form-section form-section">
            <img src="{{ asset('img/form-bg.webp') }}" alt="Image" class="form-section__bg">
            <div class="form-section__container">
                <div class="form-section__body">
                    <h2 class="form-section__title">форма реєстрації</h2>
                    <div class="form-section__text">Залиште заявку і адміністратор підбере для Вас зручний час</div>
                </div>
                <a href="{{ route('customer.register') }}" class="form-section__button button">Зареєструватись</a>
            </div>
        </section>

        <section class="home__speakers speakers-home">
            <div class="speakers-home__container">
                <h2 class="speakers-home__title title">Наші спікери</h2>
                <div class="speakers-home__slider splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($coaches as $coach)
                                <li class="splide__slide">
                                    <div class="item-speakers">
                                        <img src="{{ asset($coach->photo) }}" alt="{{ $coach->name }}" class="item-speakers__image">
                                        <div class="item-speakers__body">
                                            <div class="item-speakers__label"># {{ $coach->position }}</div>
                                            <h3 class="item-speakers__title">
                                                @php
                                                    $nameParts = explode(' ', $coach->name);
                                                    $lastName = array_shift($nameParts);
                                                    $restOfName = implode(' ', $nameParts);
                                                @endphp
                                                <span>{{ $lastName }}</span>
                                                {{ $restOfName }}
                                            </h3>
                                        </div>
                                        {{-- TODO: Create route for coach profile page --}}
                                        <a href="#" class="item-speakers__button button">Перейти</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="home__faq faq-section">
            <div class="faq-section__container">
                <h2 class="faq-section__title title">Часті питання</h2>
                <div data-spoilers class="faq-section__spoilers faq-spoilers">
                    @foreach($faqs as $faq)
                        <details class="faq-spoilers__item">
                            <summary class="faq-spoilers__title">
                                {{ $faq->question }} <span></span>
                            </summary>
                            <div class="faq-spoilers__body">
                                <div class="text">
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
