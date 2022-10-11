@extends('admin.pages.banners.layout')
@section('title', 'Информация')
@section('body')

    @bannerBlock(['title'=>'Контакты'])
    <div class="row">
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Эл. почта отправки письма'])
            @banner('data.contact_email')
            @endcard
        </div>
        <div class="col-12 col-dxl-6">
            @cards(['title'=>'Эл. почты', 'banners'=>'contacts', 'id'=>2])
                @banner('email')
            @endcards
        </div>
        <div class="col-12 col-dxl-6">
            @cards(['title'=>'Телефоны', 'banners'=>'contacts'])
            @banner('phone', 'Телефон')
            @endcards
        </div>
        <div class="col-12 col-dxl-6">
            @cards(['banners'=>'address'])
            @banner('text', 'Адрес')
            @endcards
        </div>
        <div class="col-12 col-dxl-6">
            @cards(['title'=>'Ссылки социальных сетей', 'banners'=>'socials'])
            @banner('icon', 'Икона (32X32)')
            @banner('title', 'Название')
            @banner('url', 'Ссылка')
            @endcards
        </div>
        <div class="col-12 col-dxl-6">
            @cards(['banners'=>'rates','title'=>'Курсы валют (1 тенге равно - ?)'])
            @banner('ruble', 'Курс рубля')
            @endcards
        </div>
    </div>
    @endbannerBlock
    {{--    @bannerBlock(['title'=>'Контент страниц'])--}}
    {{--    <div class="row">--}}
    {{--        <div class="col-12 col-dxl-6">--}}
    {{--            @card(['title'=>'Логотипы'])--}}
    {{--            @banner('data.header_logo', 'Верхний логотип (156 X 90)')--}}
    {{--            @banner('data.menu_logo', 'Нижний логотип (156 X 90)')--}}
    {{--            @endcard--}}
    {{--        </div>--}}
    {{--        <div class="col-12 col-dxl-6">--}}
    {{--            @card(['title'=>'Карта'])--}}
    {{--            @banner('data.iframe', 'Ссылка')--}}
    {{--            @endcard--}}
    {{--        </div>--}}
    {{--        <div class="col-12 col-dxl-6">--}}
    {{--            @cards(['title'=>'Иконки способов оплат', 'banners'=>'payments'])--}}
    {{--                @banner('image', 'Изображение ((<=80)x(<=30))')--}}
    {{--                @banner('title', 'Название')--}}
    {{--                @banner('active', 'Неактивно|Активно')--}}
    {{--            @endcards--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    @endbannerBlock--}}
    @bannerBlock(['title'=>'SEO'])
    <div class="row">
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Названии'])
            @banner('seo.title_suffix', 'Суффикс названии')
            @endcard
        </div>
    </div>
    @endbannerBlock
@endsection
