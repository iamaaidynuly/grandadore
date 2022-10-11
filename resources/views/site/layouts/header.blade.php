<header>
    <style>
        @if(isset($_GET['response']))
        @if($_GET['response']==301)
              .login__block {

            display: none;

        }

        @endif
        @endif
    </style>
    <div class="container-fluid position-relative">
        <div class="header__top d-flex justify-content-between align-items-center">
            <div class="header__group d-flex flex-row-reverse flex-sm-row justify-content-between justify-content-lg-start align-items-center">
                <div class="burger__div d-none d-sm-block d-lg-none">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>

                <div class="search__icons d-sm-none">
                    <img class="icon__search" src="{{ asset('images/001-magnifiying-glass.svg') }}"
                         alt="{{ t('search.search') }}" title="{{ t('search.search') }}">
                    <img class="icon__search-close" src="{{ asset('images/cancel.svg') }}" alt="{{ t('search.close') }}"
                         title="{{ t('search.close') }}">
                </div>

                <div class="group__call d-none d-lg-flex flex-column justify-content-start align-items-center">
                    {{--                    <a href="tel:{{ $infos->contacts[0]->phone }}" class="call__whatsapp">{{ t('whatsapp.call') }}</a>--}}
                    <a href="tel:{{ $infos->contacts[0]->phone }}"
                       class="call__number">{{ $infos->contacts[0]->phone }}</a>
                    <div class="mr-auto popup-open" id="myBtn">Заказать звонок</div>
                </div>

                @if($infos->socials)
                    <div class="group__social d-none d-lg-flex align-items-center">
                        @foreach($infos->socials as $row=>$socials)
                            @if($socials->icon)
                                <a class="link__social center link__facebook"
                                   target="_blank"
                                   rel="nofollow noopener noreferrer"
                                   href="{{ $socials->url }}" title="{{ $socials->title }}">
                                    <img class="img-fluid" src="{{ asset('u/banners/'.$socials->icon) }}"
                                         alt="{{ $socials->title }}" title="{{ $socials->title }}">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <a class="link__logo" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" class="header__logo" alt="{{ $seo['title'] ?? $title ?? '' }}"
                     title="{{ $seo['title'] ?? $title ?? '' }}">
            </a>
            <div class="group__icons-tablet d-flex justify-content-between align-items-center d-lg-none">
                <a href="tel:{{ $infos->contacts[0]->phone }}" class="mr-4">
                    <img class="phone-tablet" src="{{ asset('images/phone-tablet.svg') }}"
                         alt="{{ $infos->contacts[0]->phone }}" title="{{ $infos->contacts[0]->phone }}">
                </a>
                <a target="_blank"
                   rel="nofollow noopener noreferrer"
                   href="/Google-map-href">
                    <img class="map-icon" src="{{ asset('images/map-icon.svg') }}" alt="*" title="*">
                </a>
            </div>

            <div class="header__group d-none d-lg-flex align-items-center">
                <div class="group__money d-flex align-items-center" title="">
                    <div class="d-flex flex-row align-items-center">
                        <div class="d-flex flex-column image-money-wrap">
                            <a href="{{ url('/') }}" title="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28.729" height="25.609"
                                     viewBox="0 0 28.729 25.609">
                                    <g id="home" transform="translate(0.001 -27.798)">
                                        <g id="Group_7323" data-name="Group 7323" transform="translate(-0.001 27.798)">
                                            <g id="Group_7322" data-name="Group 7322">
                                                <path id="Path_13113" data-name="Path 13113"
                                                      d="M28.423,37.912,14.8,27.942a.746.746,0,0,0-.882,0L.3,37.912a.746.746,0,0,0,.882,1.2l13.177-9.648,13.177,9.648a.746.746,0,0,0,.882-1.2Z"
                                                      transform="translate(0.001 -27.798)"/>
                                            </g>
                                        </g>
                                        <g id="Group_7325" data-name="Group 7325" transform="translate(3.167 39.286)">
                                            <g id="Group_7324" data-name="Group 7324">
                                                <path id="Path_13114" data-name="Path 13114"
                                                      d="M78.1,232.543a.746.746,0,0,0-.746.746v11.881H71.381v-6.486a3.733,3.733,0,1,0-7.465,0v6.486H57.945V233.29a.746.746,0,0,0-1.493,0v12.628a.746.746,0,0,0,.746.746h7.464a.746.746,0,0,0,.744-.688.564.564,0,0,0,0-.058v-7.232a2.24,2.24,0,1,1,4.479,0v7.232a.546.546,0,0,0,0,.057.746.746,0,0,0,.744.689H78.1a.746.746,0,0,0,.746-.746V233.29A.747.747,0,0,0,78.1,232.543Z"
                                                      transform="translate(-56.452 -232.543)"/>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                {{-- <i class="fas fa-home" style="color: #000; font-size: 24px; margin-right: 14px;"></i>--}}
                            </a>
                            <a class="icon-link" href="{{ url('/') }}">Главная страница</a>
                        </div>
                        <div class="d-flex flex-column image-money-wrap">
                            <div class="image__money-open">
                                <a href="{{ url('/oplata-i-dostavka') }}">
                                    <img class="image__money" src="{{ asset('images/cash.svg') }}" alt="" title="">
                                </a>
                            </div>
                            <a class="icon-link" href="{{ url('/oplata-i-dostavka') }}">Оплата и Доставка</a>
                        </div>

                        <div class="d-flex flex-column image-note-wrap">
                            <div class="image__note-open">
                                <a href="{{ page('about') }}">
                                    <img class="image__note" src="{{ asset('images/note.svg') }}" alt="" title="">
                                </a>
                            </div>
                            <a class="icon-link2" href="{{ page('about') }}">О нас</a>
                        </div>
                    </div>

                </div>
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        @if(app()->getLocale() == 'ru')
                            <span class="active__language">Рус</span>
                            <img src="{{ asset('images/ru.svg') }}" class="img-fluid" alt="Рус" title="Рус">
                        @else
                            <span class="active__language">Каз</span>
                            <img src="{{ asset('images/kz.svg') }}" class="img-fluid" alt="Каз" title="Каз">
                        @endif
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(app()->getLocale() == 'ru')
                            @php $anotherLang = 'kz' ; @endphp
                        @else
                            @php $anotherLang = 'ru' ; @endphp
                        @endif

                        {{--@foreach($languages as $lang)--}}

                        <a href="{{ url(\LanguageManager::getUrlWithLocale(/*$lang->iso*/$anotherLang)) }}"
                           class="dropdown-item">
                            {{--@if($lang->iso == 'ru')
                                <span class="dropdown__language mr-1">Рус</span>
                                <img width="26" height="18" src="{{ asset('images/ru.svg') }}" class="img-fluid" alt="Рус" title="{{ $lang->title }}">
                            @else
                                <span class="dropdown__language mr-1">Каз</span>
                                <img width="26" height="18" src="{{ asset('images/kz.svg') }}" class="img-fluid" alt="Каз" title="{{ $lang->title }}">
                            @endif--}}

                            @if(app()->getLocale()== 'ru')
                                <span class="dropdown__language mr-1">Каз</span>
                                <img width="26" height="18" src="{{ asset('images/kz.svg') }}" class="img-fluid"
                                     title="">
                            @else
                                <span class="dropdown__language mr-1">Рус</span>
                                <img width="26" height="18" src="{{ asset('images/ru.svg') }}" class="img-fluid"
                                     title="">
                            @endif

                        </a>
                        {{--@endforeach--}}
                    </div>
                </div>

                <div class="d-flex align-items-center group__icons">
                    <a class="icon-link-a user-a"
                       href="{{ authUser() ? route('cabinet.profile') : route('login') }}">
                        <img
                                {{--                            class="user-001{{ authUser() ? '' : ' image__userWeb' }}"--}}
                                src="{{ asset('images/user-001.svg') }}"
                                alt="{{ authUser() ? t('login.cabinet') : t('login.login') }}"
                                title="{{ authUser() ? t('login.cabinet') : t('login.login') }}">
                    </a>

                    @if(\Illuminate\Support\Facades\Auth::check())
                        <div class="d-flex group__sup">
                            <a class="icon-link-a d-flex mini-favorites-open"
                               href="{{ authUser() ? route('cabinet.profile.favorite') : 'javascript:void(0)' }}">
                                <img class="star-001"
                                     src="{{ asset('images/001-star.svg') }}"
                                     alt="{{ t('login.favorite') }}"
                                     title="{{ t('login.favorite') }}">
                                <sup class="favorite__sup">0</sup>
                            </a>
                        </div>

                    @endif

                    <div class="d-flex group__sup">
                        <a class="basket-icon icon-link-a d-flex mini-basket-open mini-basket-openWeb"
                           href="javascript:void(0){{--{{ route('cabinet.profile.basket') }}--}}">
                            <img class="shopping-bag" src="{{ asset('images/shopping-bag.svg') }}"
                                 alt="{{ t('basket.basket') }}" title="{{ t('basket.basket') }}">
                            <sup class="basket__sup indicator-value basket-items-count"></sup>
                        </a>
                    </div>

                </div>
            </div>


            @if(!Auth::user())

                <div class="login__block">
                    <span class="log__in">{{ t('login.authorize') }}</span>

                    <div class="d-flex flex-row reg-soc-group">
                        <div class="reg-mail reg-login-soc-icon">
                            <a href="{{ route('socialAuth.mailru.login') }}">
                                <i class="fas fa-at"></i>
                            </a>
                        </div>

                        <div class="reg-facebook reg-login-soc-icon">
                            <a href="{{ route('socialAuth.facebook.login') }}">
                                <i class="fab fa-facebook-square"></i>
                            </a>
                        </div>

                        <div class="reg-gmail reg-login-soc-icon">
                            <a href="{{ route('socialAuth.google.login') }}">
                                <i class="fab fa-google"></i>
                            </a>
                        </div>
                    </div>

                    <form class="login__form" action="{{ route('login.post') }}" method="post">
                        @csrf
                        <div class="w-100">
                            <div class="form-group">
                                <input type="text"
                                       class="form-control login__input"
                                       name="email"
                                       id="exampleInputEmail1"
                                       placeholder="{{ t('Login Page.login') }}"
                                       value="{{ old('email') }}">
                            </div>
                            @if($errors->has('email'))
                                <span class="input-alert text-danger">{{ $errors->first('email') }}</span>
                            @endif

                            <div class="form-group position-relative">
                                <input type="password"
                                       class="form-control login__input login__input__password"
                                       name="password"
                                       style="padding-right: 45px !important;"
                                       id="exampleInputPassword1"
                                       placeholder="{{ t('Login Page.Password') }}"
                                       value="{{ old('password') }}">
                                <img class="eye" src="{{ asset('images/eye.svg') }}"
                                     alt="{{ t('Login Page.Password_show') }}"
                                     title="{{ t('Login Page.Password_show') }}">
                                @if($errors->has('password'))
                                    <span class="input-alert text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            @if($errors->has('global'))
                                <div class="form-group">
                                    <p class="text-center input-alert mt-3" style="border:2px solid red">
                                        {{ $errors->first('global') }}
                                    </p>
                                </div>
                            @endif
                        </div>


                        <button type="submit" class="btn btn-primary ">{{ t('Login Page Button.LOG IN') }}</button>

                        <div class="form__help">
                            <a href="{{ url('reset') }}"
                               class="password__help form-link">{{ t('Login Page.Forgot password?') }}</a>
                            <a href="{{ url('register') }}"
                               class="registration form-link">{{ t('Register Page.register') }}</a>
                        </div>
                    </form>
                </div>

            @endif

            {{--            ////////////////////////////--}}


            @if(!$disableSmallBasket)
                @include('site.components.small-basket.index')
            @endif

        </div>

        <div class="header__bottom d-none d-lg-flex align-items-center">

            <nav class="nav__main">
                <ul class="ul__main">
                    <li class="li__main li__catalog2">
                        <a class="nav-link" href="javascript:void(0)">{{ t('Products.catalogue') }}</a>
                    </li>
                    @foreach($menu_pages as $page)
                        @if($page->static == 'brands' && $brandsCount == 0)
                            @continue
                        @endif
                        <li class="li__main">
                            <a class="nav-link"
                               href="{{ route('page', ['url'=>$page->static==$homepage->static?null:$page->url]) }}">
                                {{ $page->title }}
                                @if(($current_page ?? null) === $page->id)
                                    <span class="sr-only">(current)</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="search__icons d-none d-lg-block">
                <img class="icon__search" src="{{ asset('images/001-magnifiying-glass.svg') }}"
                     alt="{{ t('search.search') }}" title="{{ t('search.search') }}">
                <img class="icon__search-close" src="{{ asset('images/cancel.svg') }}" alt="{{ t('search.close') }}"
                     title="{{ t('search.close') }}">
            </div>
        </div>

        <form class="form__header form__header-s" action="{{ route('products.search') }}" method="get">

            <input type="text"
                   name="searchQuery"
                   placeholder="{{ t('search.placeholder') }}"
                   class="search__input"
                   aria-label="search"
                   aria-describedby="basic-addon2"
                   value="{{ request()->query('searchQuery') }}">

            <button type="submit" class="search__button">
                {{ t('search.search') }}
            </button>

        </form>
    </div>

    <div id="basket-myModalID" class="basket-modal">
        <span class="close">&times;</span>
        <div class="basket-modal-content">

        </div>
    </div>

    <div id="authorization-myModalID" class="authorization-modal">
        <span class="close2">&times;</span>
        <div class="authorization-modal-content">

        </div>
    </div>

    <div class="menu__mobile">
        <div class="header__top d-flex justify-content-between align-items-center d-sm-none">
            <div
                    class="header__group d-flex flex-row-reverse flex-sm-row justify-content-between justify-content-lg-start align-items-center">
                <div class="burger__div d-none d-sm-block d-lg-none">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>

                <div class="search__icons2 d-sm-none">
                    <img class="icon__search" src="{{ asset('images/001-magnifiying-glass.svg') }}"
                         alt="{{ t('search.search') }}" title="{{ t('search.search') }}">
                    <img class="icon__search-close" src="{{ asset('images/cancel.svg') }}" alt="{{ t('search.close') }}"
                         title="{{ t('search.close') }}" style="display: none;">
                </div>

                <div class="group__call d-none d-lg-flex flex-column justify-content-start align-items-center">
                    <a href="tel:{{ $infos->contacts[0]->phone }}" class="call__whatsapp">{{ t('whatsapp.call') }}</a>
                    <a href="tel:{{ $infos->contacts[0]->phone }}"
                       class="call__number">{{ $infos->contacts[0]->phone }}</a>
                </div>

                @if($infos->socials)
                    <div class="group__social d-none d-lg-flex align-items-center">
                        @foreach($infos->socials as $row=>$socials)
                            @if($socials->icon)
                                <a class="link__social center link__facebook"
                                   target="_blank"
                                   rel="nofollow noopener noreferrer"
                                   href="{{ $socials->url }}" title="{{ $socials->title }}">
                                    <img class="img-fluid" src="{{ asset('u/banners/'.$socials->icon) }}"
                                         alt="{{ $socials->title }}" title="{{ $socials->title }}">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>


            <a class="link__logo"
               href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" class="header__logo" alt="{{ $seo['title'] ?? $title ?? '' }}"
                     title="{{ $seo['title'] ?? $title ?? '' }}">
            </a>

            <div class="group__icons-tablet d-flex justify-content-between align-items-center d-lg-none">
                <a href="tel:{{ $infos->contacts[0]->phone }}" class="mr-4">
                    <img class="phone-tablet" src="{{ asset('images/phone-tablet.svg') }}"
                         alt="{{ $infos->contacts[0]->phone }}" title="{{ $infos->contacts[0]->phone }}">
                </a>
                <a target="_blank"
                   href="/Google-map-href">
                    <img class="map-icon" src="{{ asset('images/map-icon.svg') }}" alt="" title="">
                </a>
            </div>

            <div class="header__group d-none d-lg-flex align-items-center">

                <div class="d-flex flex-row align-items-center">

                    <div class="d-flex flex-column image-money-wrap">
                        1
                        <div class="image__money-open">
                            <img class="image__money" src="{{ asset('images/cash.svg') }}" alt="" title="">
                        </div>
                    </div>

                    <div class="d-flex flex-column image-note-wrap">
                        <div class="image__note-open">
                            <img class="image__note" src="{{ asset('images/note.svg') }}" alt="" title="">
                        </div>
                    </div>

                </div>

                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        @if(app()->getLocale() == 'ru')
                            <span class="active__language">Рус</span>
                            <img src="{{ asset('images/ru.svg') }}" class="image__flag" alt="Рус" title="Рус">
                        @else
                            <span class="active__language">Каз</span>
                            <img src="{{ asset('images/kz.svg') }}" class="image__flag" alt="Каз" title="Каз">
                        @endif
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                        @foreach($languages as $lang)
                            <a href="{{ url(\LanguageManager::getUrlWithLocale($lang->iso)) }}" class="dropdown-item">
                                @if($lang->iso == 'ru')
                                    <span class="dropdown__language mr-1">Рус</span>
                                    <img width="26" height="18" src="{{ asset('images/ru.svg') }}" class="img-fluid"
                                         alt="{{ $lang->title }}" title="{{ $lang->title }}">
                                @else
                                    <span class="dropdown__language mr-1">Каз</span>
                                    <img width="26" height="18" src="{{ asset('images/kz.svg') }}" class="img-fluid"
                                         alt="{{ $lang->title }}" title="{{ $lang->title }}">
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex align-items-center group__icons">
                    <a class="icon-link-a user-a"
                       href="{{ authUser() ? route('cabinet.profile') : 'javascript:void(0)' }}">
                        <img class="user-001"
                             src="{{ asset('images/user-001.svg') }}"
                             alt="{{ authUser() ? t('login.cabinet') : t('login.login') }}"
                             title="{{ authUser() ? t('login.cabinet') : t('login.login') }}">
                    </a>

                    <div class="d-flex group__sup">
                        <a class="icon-link-a d-flex mini-favorites-open"
                           href="{{ authUser() ? route('cabinet.profile.favorite') : 'javascript:void(0)' }}">
                            <img class="star-001"
                                 src="{{ asset('images/001-star.svg') }}"
                                 alt="{{ t('login.favorite') }}"
                                 title="{{ t('login.favorite') }}">
                            <sup class="favorite__sup">0</sup>
                        </a>
                    </div>

                    <div class="d-flex group__sup sup-right">
                        <a class="icon-link-a d-flex mini-basket-open mini-basket-openWeb"
                           href="javascript:void(0){{--{{ route('cabinet.profile.basket') }}--}}">
                            <img class="shopping-bag" src="{{ asset('images/shopping-bag.svg') }}"
                                 alt="{{ t('basket.basket') }}" title="{{ t('basket.basket') }}">
                            <sup class="basket__sup indicator-value basket-items-count"></sup>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <form class="form__header2" action="{{ route('products.search') }}" method="get">

            <input type="text"
                   name="searchQuery"
                   placeholder="{{ t('search.placeholder') }}"
                   class="search__input"
                   aria-label="search"
                   aria-describedby="basic-addon2"
                   value="{{ request()->query('searchQuery') }}">

            <button type="submit" class="search__button">
                {{ t('search.search') }}
            </button>

        </form>

        <div class="flags__mobile d-flex mb-5">
            @foreach($languages as $lang)
                <div>
                    <a href="{{ url(\LanguageManager::getUrlWithLocale($lang->iso)) }}" class="dropdown-item">
                        @if($lang->iso == 'ru')
                            <span class="dropdown__language">Рус</span>
                            <img width="26" height="18" src="{{ asset('images/ru.svg') }}" class="img-fluid"
                                 alt="{{ $lang->title }}" title="{{ $lang->title }}">
                        @else
                            <span class="dropdown__language">Каз</span>
                            <img width="26" height="18" src="{{ asset('images/kz.svg') }}" class="img-fluid"
                                 alt="{{ $lang->title }}" title="{{ $lang->title }}">
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        @include('site.components.categories-list')

        <ul class="complementary" title="">
            <li class="mb-4">
                <a href="{{ page('about') }}" class="d-flex align-items-center">
                    <span class="m-icon-wrap">
                        <img class="image__money" src="{{ asset('images/cash.svg') }}" alt="Оплата и доставка*"
                             title="Оплата и доставка*">
                    </span>
                    <span>Оплата и доставка</span>
                </a>
            </li>

            <li>
                <a href="{{ page('about') }}" class="d-flex align-items-center">
                    <span class="m-icon-wrap">
                        <img src="{{ asset('images/note.svg') }}" class="image__note" alt="О нас*" title="О нас*">
                    </span>
                    <span>О нас</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="menu__bottom__tablet-fixed">
        <div class="container-fluid">
            <div class="menu__tablet-fixed-content d-flex justify-content-between align-items-center">
                <a href="{{ url('/') }}">
                    <img class="image__home" src="{{ asset('images/home.png') }}"
                         alt="{{ $seo['title'] ?? $title ?? '' }}" title="{{ $seo['title'] ?? $title ?? '' }}">
                </a>
                <a class="user-margin" href="{{ authUser() ? route('cabinet.profile') : 'javascript:void(0)' }}">
                    <img class="user-001 image__userTablet"
                         src="{{ asset('images/user-001.svg') }}"
                         alt="{{ authUser() ? t('login.cabinet') : t('login.login') }}"
                         title="{{ authUser() ? t('login.cabinet') : t('login.login') }}">
                </a>

                <div class="burger__div-bottom-wrapper d-flex justify-content-center align-items-center d-sm-none">
                    <div class="burger__div-bottom">
                        <div class="bar1"></div>
                        <div class="bar2"></div>
                        <div class="bar3"></div>
                    </div>
                </div>

                <div class="d-flex group__sup">
                    <a class="icon-link-a d-flex mini-favorites-open"
                       href="{{ authUser() ? route('cabinet.profile.favorite') : 'javascript:void(0)' }}">
                        <img class="star-001" src="{{ asset('images/001-star.svg') }}" alt="{{ t('login.favorite') }}"
                             title="{{ t('login.favorite') }}">
                        <sup class="favorite__sup" style="display:none">0</sup>
                    </a>
                </div>

                <div class="d-flex group__sup">
                    <a class="icon-link-a d-flex mini-basket-open mini-basket-openTablet"
                       href="javascript:void(0){{--{{ route('cabinet.profile.basket') }}--}}">
                        <img class="shopping-bag" src="{{ asset('images/shopping-bag.svg') }}"
                             alt="{{ t('basket.basket') }}" title="{{ t('basket.basket') }}">
                        {{--                        <sup class="favorite__sup indicator-value basket-items-count"></sup>--}}
                        <sup class="basket__sup indicator-value basket-items-count"></sup>
                    </a>
                </div>

                <div class="search__icons d-none d-sm-block">
                    <img class="icon__search" src="{{ asset('images/001-magnifiying-glass.svg') }}"
                         alt="{{ t('search.search') }}" title="{{ t('search.search') }}">
                    <img class="icon__search-close" src="{{ asset('images/cancel.svg') }}" alt="{{ t('search.close') }}"
                         title="{{ t('search.close') }}" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            @if($errors->any())
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{ route('contact.sendMessage')}}" method="post">
                @csrf
                <div class="input-field mb-3">
                    <label for="name">ФИО
                        <span class="first-letter">*</span>
                    </label>
                    <input id="name" type="text" class="form-control nice-input" name="name"
                           value="{{ authUser() ? authUser()->name : "" }}">
                </div>

                <div class="input-field mb-3">
                    <label for="phone">Контактный телефон
                        <span class="first-letter">* </span>
                    </label>
                    <input id="phone" type="text" class="form-control nice-input masked-phone-inputs" name="phone"
                           value="{{ authUser() ? authUser()->phone : "" }}">
                </div>

                <div class="input-field mb-3">
                    <label for="email">Email
                        <span class="first-letter">* </span>
                    </label>
                    <input id="email" type="email" class="form-control nice-input" name="email"
                           value="{{ authUser() ? authUser()->email : "" }}">
                </div>
                <button class="btn btn-send" type="submit">Отправить</button>
            </form>

        </div>

    </div>
</header>
<script src="https://www.google.com/recaptcha/api.js"></script>
