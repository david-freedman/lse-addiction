<footer class="footer">
    <div class="footer__container">
        <div class="footer__wrapper">
            <div class="footer__logo">
                <img src="{{ asset('img/logo-min.svg') }}" alt="LifeScanEducation" class="ibg ibg--contain">
            </div>
            <div class="footer__body">
                <ul class="footer__menu">
                    <li>
                        <a href="#">
                            Про нас
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Навчання у нас
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Наші лектори
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            FAQ
                        </a>
                    </li>
                    <li>
                        @auth
                            <a href="{{ route('student.profile.show') }}">
                                Особистий кабінет
                            </a>
                        @else
                            <a href="{{ route('student.login') }}">
                                Особистий кабінет
                            </a>
                        @endauth
                    </li>
                </ul>
                <ul class="footer__menu">
                    <li>
                        <a href="#">
                            Наші контакти
                        </a>
                    </li>
                    <li>
                        <a href="tel:+380800331065">
                            +38 (080) 033 10 65
                        </a>
                    </li>
                    <li>
                        <a href="tel:+380675317570">
                            +38 (067) 531 75 70
                        </a>
                    </li>
                    <li>
                        <a href="tel:+380675317570">
                            +38 (067) 531 75 70
                        </a>
                    </li>
                    <li>
                        <a href="mailto:maillist@lifescan.com.ua">
                            maillist@lifescan.com.ua
                        </a>
                    </li>
                </ul>
                <div class="footer__social social-footer">
                    <h4 class="social-footer__title">
                        Підписуйся!
                    </h4>
                    <div class="social-footer__items">
                        <a href="#" class="social-footer__item _icon-s-fb">
                            Facebook
                        </a>
                        <a href="#" class="social-footer__item _icon-s-inst">
                            Instagram
                        </a>
                        <a href="#" class="social-footer__item _icon-s-viber">
                            Viber
                        </a>
                        <a href="#" class="social-footer__item _icon-s-wp">
                            Watsapp
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__copy">
            LifeScanEducation {{ date('Y') }}
        </div>
    </div>
</footer>
