@if(auth()->user()->role==1 || auth()->user()->role==2)
    @alink(['icon'=>'fas fa-paperclip', 'title'=>'Заказы','counter'=>($new_orders_count)+ ($pending_orders_count) + $done_orders_count +$declined_orders_count])

    @if($new_orders_count)
        @alink(['url'=>route('admin.orders.new'), 'icon'=>'fas fa-plus-circle', 'title'=>'Новые Заказы','counter'=>($new_orders_count)])@endalink
    @endif

    @if($pending_orders_count)
        @alink(['url'=>route('admin.orders.pending'), 'icon'=>'fas fa-tasks', 'title'=>'Невыполненные Заказы','counter'=>($pending_orders_count)])@endalink
    @endif

    @alink(['url'=>route('admin.orders.done'), 'icon'=>'fas fa-check-circle', 'title'=>'Выполненные Заказы','counter'=>($done_orders_count)])@endalink

    @if($declined_orders_count)
        @alink(['url'=>route('admin.orders.declined'), 'icon'=>'fas fa-times-circle', 'title'=>'Отклоненные Заказы','counter'=>($declined_orders_count)])@endalink
    @endif
    @php /** bistri zakaz */ @endphp

        @if(isset($bistri_status))
        @alink(['url'=>route('admin.orders.bistri'), 'icon'=>'fas fa-users', 'title'=>'Быстрые заказы','counter'=>($bistri_status)])@endalink
        @endif
    @endalink
@endif
@php /**zakazat zvonok */ @endphp
@if(isset($allZakazat) && $allZakazat->count()>0)
    @alink(['url'=>route('admin.orders.call'), 'icon'=>'fas fa-tasks', 'title'=>'Заказы Звонков','counter'=>($allZakazat->count())])@endalink
@endif
@if(auth()->user()->role==1 )
    @alink(['url'=>route('admin.category.list'), 'icon'=>'fas fa-list-alt', 'title'=>'Категории']) @endalink
@endif

@if(auth()->user()->role==1)
    @alink(['url'=>route('admin.filters.list'), 'icon'=>'fas fa-filter', 'title'=>'Фильтры']) @endalink
@endif
@if(auth()->user()->role==1)
    @alink(['url'=>route('admin.brands.main'), 'icon'=>'fas fa-globe', 'title'=>'Бренды']) @endalink
@endif
@if(auth()->user()->role==1 || auth()->user()->role==3)
    @alink(['url'=>route('admin.pages.main'), 'icon'=>'mdi mdi-receipt', 'title'=>'Страницы'])@endalink
@endif



@alink(['url'=>route('admin.support.main'), 'icon'=>'fas fa-question', 'title'=>'Поддержка'])@endalink
@alink(['url'=>route('admin.short_links.main'), 'icon'=>'fas fa-question', 'title'=>'Короткие ссылки'])@endalink

@if(auth()->user()->role==1 )
{{--    @alink(['url'=>route('admin.packages.main',['packages']), 'icon'=>'fa fa-gift', 'title'=>'Пакеты'])@endalink--}}
{{--    @alink(['url'=>route('admin.one-time-payment.main'), 'icon'=>'fa fa-gift', 'title'=>'Услуги с одноразовой оплатой'])@endalink--}}

@endif

{{--@alink(['url'=>route('admin.reviews.main'), 'icon'=>'fas fa-question', 'title'=>'Отзывы'])@endalink--}}


@alink(['url'=>route('admin.languages.main'), 'icon'=>'mdi mdi-translate', 'title'=>'Языки']) @endalink
@if(auth()->user()->role==1)
    @alink(['icon'=>'fas fa-user', 'title'=>'Администраторы'])
    {{--    @alink(['url'=>route('admin.users.main'), 'icon'=>'fas fa-users', 'title'=>'Пользователи сайта']) @endalink--}}
    {{--    @alink(['url'=>route('admin.users.view.magazine'), 'icon'=>'fas fa-users', 'title'=>'Магазины']) @endalink--}}
    @alink(['url'=>route('admin.users.main',['role'=>2]), 'icon'=>'fas fa-users', 'title'=>'Оператор-Модератор(ы)']) @endalink
    @alink(['url'=>route('admin.users.main',['role'=>3]), 'icon'=>'fas fa-users', 'title'=>'Контент-менеджер(ы)']) @endalink

    @endalink
@endif

{{--@alink(['url'=>route('admin.items.index'), 'icon'=>'fas fa-shopping-cart', 'title'=>'Товары']) @endalink--}}

@alink(['url'=>route('admin.delivery_regions.main'), 'icon'=>'fas fa-globe-europe', 'title'=>'Доставка и цены'])@endalink


@if(auth()->user()->role==1 || auth()->user()->role==3)
    @alink(['icon'=>'fas fa-desktop', 'title'=>'Баннеры  на Главной','url'=>route('admin.banners',['home_big_image_banners'])])
    @endalink
    {{--    @alink(['url'=>route('admin.users.statistics'), 'icon'=>'fas fa-globe-europe', 'title'=>'Статистика магазинов'])@endalink--}}
@endif
{{--somovivoz --}}
@if(auth()->user()->role==1 || auth()->user()->role==3)
    @alink(['icon'=>'fa fa-home','title'=>'Самовывоз' , 'url'=>route('admin.address')])
    @endalink
@endif


@if(auth()->user()->role==1 || auth()->user()->role==3)
    @alink(['icon'=>'far fa-comment-dots','title'=>'Отзывы' ,'counter'=>($countnewMessage) , 'url'=>route('admin.comment')])
    @endalink
@endif

@if(auth()->user()->role==1 || auth()->user()->role==3)
    @alink(['icon'=>'fa fa-search','title'=>'Популярные поиски' , 'url'=>route('admin.search')])
    @endalink
@endif








