@push('css')
    <link rel="stylesheet" href="{{ asset('css/personal-infobar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/userpanel.css') }}">
@endpush
<div class="personal__panel">
    <a href="{{ route('cabinet.profile') }}"
       class="personal-link {{ isset($active) && $active == 'settings' ? 'active' : '' }}">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-user" src="{{ asset('images/personal-user.svg') }}" alt="Мои данные*"
                 title="Мои данные*">
        </div>
        <span>Мои данные</span>
    </a>
    <a href="{{ route('cabinet.profile.favorite') }}"
       class="personal-link {{ isset($active) && $active == 'favorite' ? 'active' : '' }}">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-heart" src="{{ asset('images/001-heart.svg') }}" alt="Избранные*" title="Избранные*">
        </div>
        <span>Избранные</span>
        <span class="cabinet-orders-count cabinet-orders-count_position"><sup class="favorite__sup">0</sup></span>
    </a>

    <a href="{{ route('cabinet.profile.basket') }}"
       class="personal-link {{ isset($active) && $active == 'basket' ? 'active' : '' }}">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-shopping" src="{{ asset('images/001-shopping55.svg') }}" alt="Моя корзина*"
                 title="Моя корзина*">
        </div>
        <span>Моя корзина</span>
        <span class="cabinet-orders-count cabinet-orders-count_position"><sup
                    class="basket__sup indicator-value basket-items-count"></sup></span>
    </a>

    <a href="{{ route('cabinet.profile.orders.active') }}"
       class="personal-link {{ isset($active) && $active == 'history' ? 'active' : '' }}">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-filing" src="{{ asset('images/001-filing-cabinet.svg') }}" alt="Активные заказы*"
                 title="Активные заказы*">
        </div>
        <span>Активные заказы</span>
        @if(isset($activeOrdersCount))
            <span class="cabinet-orders-count">{{ $activeOrdersCount > 99 ? '99+' : $activeOrdersCount }}</span>
        @endif
    </a>

    <a href="{{ route('cabinet.profile.orders.history') }}"
       class="personal-link {{ isset($active) && $active == 'history' ? 'active' : '' }}">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-filing" src="{{ asset('images/001-filing-cabinet.svg') }}" alt="Архив заказов*"
                 title="Архив заказов*">
        </div>
        <span>Архив заказов</span>
        @if(isset($archiveOrdersCount))
            <span class="cabinet-orders-count">{{ $archiveOrdersCount > 99 ? '99+' : $archiveOrdersCount }}</span>
        @endif
    </a>

    <a href="{{ route('logout') }}" class="personal-link icon-width personal-close"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
        <div class="icon-wrap d-flex justify-content-center align-items-center">
            <img class="personal-logout" src="{{ asset('images/001-log-out.svg') }}" alt="Выход*" title="Выход*">
        </div>
        <span>Выход</span>
    </a>

    <form action="{{ route('logout') }}" method="post" id="logout-form">
        @csrf
    </form>
</div>

<div class="personal-tablet d-lg-none">
    <div class="dropdown d-flex justify-content-start">
        <button class="personal-dropdown-mobile-button dropdown-toggle personal-link active d-flex justify-content-start align-items-center"
                type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <div class="icon-wrap d-flex justify-content-center align-items-center">
                <!-- <img class="personal-user" src="images/personal-user.svg" alt="" title=""> -->
                <img class="personal-user" src="{{ asset('images/personal-user.svg') }}" alt="Мои данные*"
                     title="Мои данные*">
            </div>
            <span>Мои данные</span>
        </button>

        <div class="dropdown-menu tablet-dropdown" aria-labelledby="dropdownMenuButton3">
            <a href="{{ route('cabinet.profile.favorite') }}"
               class="dropdown-item personal-link d-flex justify-content-start">
                <div class="icon-wrap d-flex justify-content-center align-items-center">
                    <!-- <img class="personal-heart" src="images/001-heart.svg" alt="" title=""> -->
                    <img class="personal-heart" src="{{ asset('images/001-heart.svg') }}" alt="Избранные*"
                         title="Избранные*">
                </div>
                <span>Избранные</span>
            </a>

            <a class="dropdown-item personal-link d-flex justify-content-start"
               href="{{ route('cabinet.profile.basket') }}">
                <div class="icon-wrap d-flex justify-content-center align-items-center">
                    <!-- <img class="personal-shopping" src="images/001-shopping55.svg" alt="" title=""> -->
                    <img class="personal-shopping" src="{{ asset('images/001-shopping55.svg') }}" alt="Моя корзина*"
                         title="Моя корзина*">
                </div>
                <span>Моя корзина</span>
            </a>

            <a class="dropdown-item personal-link d-flex justify-content-start"
               href="{{ route('cabinet.profile.orders.active') }}">
                <div class="icon-wrap d-flex justify-content-center align-items-center">
                    <!-- <img class="personal-filing" src="images/001-filing-cabinet.svg" alt="" title=""> -->
                    <img class="personal-filing" src="{{ asset('images/001-filing-cabinet.svg') }}"
                         alt="Активные заказы*" title="Активные заказы*">
                </div>
                <span>Активные заказы</span>
            </a>

            <a class="dropdown-item personal-link d-flex justify-content-start"
               href="{{ route('company.profile.orders.history') }}">
                <div class="icon-wrap d-flex justify-content-center align-items-center">
                    <!-- <img class="personal-filing" src="images/001-filing-cabinet.svg" alt="" title=""> -->
                    <img class="personal-filing" src="{{ asset('images/001-filing-cabinet.svg') }}"
                         alt="Активные заказы*" title="Активные заказы*">
                </div>
                <span>Архив заказов</span>
            </a>

            {{--            <a class="dropdown-item personal-link d-flex justify-content-start" href="#">--}}
            {{--                <div class="icon-wrap d-flex justify-content-center align-items-center">--}}
            {{--                    <!-- <img class="personal-filing" src="images/001-filing-cabinet.svg" alt="" title=""> -->--}}
            {{--                    <img class="personal-filing" src="{{ asset('images/001-filing-cabinet.svg') }}"--}}
            {{--                         alt="Активные заказы*" title="Активные заказы*">--}}
            {{--                </div>--}}
            {{--                <span>Поддержка</span>--}}
            {{--            </a>--}}

            <a class="dropdown-item personal-link d-flex justify-content-start"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                <div class="icon-wrap d-flex justify-content-center align-items-center">
                    <!-- <img class="personal-logout" src="images/001-log-out.svg" alt="" title=""> -->
                    <img class="personal-logout" src="{{ asset('images/001-log-out.svg') }}" alt="Выход*"
                         title="Выход*">
                </div>
                <span>Выход</span>
            </a>

        </div>
    </div>
</div>


<div class="personal-info-left-bar" style="display: none">
    <div class="left-bar">
        <ul>
            <li class="{{ isset($active) && $active == 'settings' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile') }}">Мои данные</a>
            </li>
            <li class="{{ isset($active) && $active == 'favorite' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile.favorite') }}">Избранные</a>
            </li>
            <li class="{{ isset($active) && $active == 'basket' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile.basket') }}">Моя корзина</a>
            </li>
            <li class="{{ isset($active) && $active == 'history' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile.orders.active') }}">Активные заказы</a>
            </li>
            <li class="{{ isset($active) && $active == 'history' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile.orders.history') }}">Архив заказов</a>
            </li>
            <li class="{{ isset($active) && $active == 'support' ? 'active' : '' }}">
                <a href="{{ route('cabinet.profile.support') }}">Поддержка</a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Выход</a>
                <form action="{{ route('logout') }}" method="post" id="logout-form">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>



@push('js')
    <script>
        let elements = $('.personal-info-left-bar a');
        const url = window.location.href.split('?')[0];

        $.each(elements, function () {
            if ($(this).attr('href') === url) {
                $(this).closest('li').addClass('active');
            }
        });
    </script>
@endpush
