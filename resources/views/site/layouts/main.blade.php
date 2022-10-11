 <!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="theme-color" content="#0D1112">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="g4vlSKK3N_4RiR7BE3ZsDd3eBNr1Vqt41AocScZaQdU" />

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title> {{ $seo['title'] ?? $title ?? '' }} </title>
    @if(!empty($seo['keywords']))
        <meta name="keywords" content="{{ $seo['keywords'] }}">
    @endif
    @if(!empty($seo['description']))
        <meta name="description" content="{{ $seo['description'] }}">
    @endif
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet preload" href="{{ asset('css/roboto-fontface.css') }}" as="style" crossorigin="anonymous" type="text/css">
    {{--<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/lazy-load.css') }}">

    {{--<link rel="stylesheet" href="{{ asset('css/media.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">--}}

    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/bootstrap.min.css') }}">
{{--    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/swiper-bundle.min.css') }}">--}}
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/swiper.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/main.css') }}">
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/home.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('assets/rating-bundle/rating-bundle.css') }}">
    @yield('css')
    @stack('css')

<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MJDMW6D');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
{{--Verification: 4953e55a0c745466--}}
@include('site.layouts.header', ['headerSidebar' => $headerSidebar ?? true, 'disableSmallBasket' => false])

<main>
    <!-- <i class="fab fa-accessible-icon"></i> -->
    @include('site.components.breadcrumb')
    @yield('content')
</main>
@include('site.layouts.footer')


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJDMW6D"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(87024374, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/87024374" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
    window.customConfig = {
        changeRatingUrl: '{{ route('products.changeRating') }}',
        userAuthenticated: {{ authUser() ? 1 : 0 }},
        userIsAdmin: {{ authUser() && authUser()->isAdmin() ? 1 : 0 }},

        addFavoriteUrl: '{{ route('user.favorite.add') }}',
        removeFavoriteUrl: '{{ route('user.favorite.destroy') }}',
        fetchFavoritesUrl: '{{ route('user.favorite.get') }}',

        fetchProductsUrl: '{{ route('product.getPortion') }}',
        priceRangeUrl: '{{ route('product.getPriceRange') }}',

        addBasketItemUrl: '{{ route('cabinet.basket.add') }}',
        fetchSmallBasketUrl: '{{ route('cabinet.basket.getSmallBasket') }}',
        fetchBasketItemsUrl: '{{ route('cabinet.basket.get') }}',
        updateBasketItemUrl: '{{ route('cabinet.basket.update') }}',
        removeBasketItemUrl: '{{ route('cabinet.basket.delete') }}',
    };
</script>
{{--<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/lazyLoad-bundle.js') }}"></script>
<script src="{{ asset('js/favorites-bundle.js') }}"></script>
<script src="{{ asset('js/basket-bundle.js') }}"></script>
<script src="{{ asset('js/basket-calculator.js') }}"></script>--}}

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>--}}
<script src="{{ asset('js/lazyLoad-bundle.js') }}"></script>
<script src="{{ asset('assets/rating-bundle/rating-bundle.js') }}"></script>
<script src="{{ asset('js/favorites-bundle.js') }}"></script>
<script src="{{ asset('js/basket-bundle.js') }}"></script>
<script src="{{ asset('js/basket-calculator.js') }}"></script>
<script src="{{ asset('js/swiper.min.js') }}"></script>
@if(count($notifications = session()->get('notifications', [])))
    <script>
        @foreach(session()->get('notifications ', []) as $notification)
            toastr['{{ $notification['type'] }}']('{{ $notification['text'] }}');
        @endforeach
        <?php session()->forget('notifications') ?>
    </script>
@endif
@yield('js')
@stack('js')
</body>
</html>

