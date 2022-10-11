@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Products')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/product-list.js') }}"></script>
    <script src="{{ asset('js/viewModel-bundle.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="container-fluid productlist">
        <div class="productlist_flexbox row">
{{--            row--}}
            @if(count($childCategories = $category->children) || count($childCategories = $category->parent->children))
                <div class="d-none d-lg-flex flex-column categories_block">
                    <h1 class="filter-block-title">
                        {{isset($category->parent->name) ? $category->parent->name : ""}}
                    </h1>
                    <div class="categories_block_space"></div>
                    @foreach($childCategories as $child)
                        <span class="categories__span">
                            <a href="{{ route('products.category.list',['url'=>$child->url]) }}"
                               class="categories-link">
                                {{ $child->name }}
                            </a>
                        </span>
                    @endforeach
                </div>
            @endif
            <div class="col-12 col-lg-9 d-flex flex-column position-static filter__web__structure">
                <div class="filter__block">
                   <div class="filter-top_block">
                        <div class="filter_btn_block">
                            <button type="reset" class="cancel_btn" value="Reset"><i class="fas fa-times"></i>Отменить</button>
                            <button type="submit" class="accept_btn"><i class="fas fa-check"></i>Пременить</button>
                        </div>
                        <div class="filter_block_closer"><svg id="_002-close-button" data-name="002-close-button" xmlns="http://www.w3.org/2000/svg" width="12.494" height="12.494" viewBox="0 0 12.494 12.494">
                                <g id="close">
                                    <path id="Path_12565" data-name="Path 12565" d="M12.494,1.249,11.245,0l-5,5-5-5L0,1.249l5,5-5,5,1.249,1.249,5-5,5,5,1.249-1.249-5-5Z"/>
                                </g>
                            </svg>
                        </div>
                   </div>
                    <div class="filter__div d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center filter__button">
                            <span>{{ t('Products.price filter') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter__content">
                            <div class="range-block">
                                <div class="product-types active">
                                    <div class="range-slider-section">
                                        <div class="range-slider">
                                            <input type="text" class="js-range-slider" value=""/>
                                        </div>
                                        <div class="extra-controls">
                                            <label for="from" id="mainForm">{{ t('Products.price filter from') }}
                                                <input type="text" class="form-control js-input-from" id="from">
                                            </label>
                                            <label for="to" id="mainForm">{{ t('Products.price filter to') }}
                                                <input type="text" class="form-control js-input-to" id="to">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="d-flex justify-content-between align-items-center filter__button">
                        <span>Цена</span><i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter__content">
                        <div class="range-block">
                            <div id="slider-range"
                                 class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
                                 aria-disabled="false">
                                <div class="ui-slider-range range-color ui-widget-header ui-corner-all"
                                     style="left: 0%; width: 100%; background: #E0E0E0 !important"></div><a
                                    class="ui-slider-handle ui-state-default  ui-corner-all" href="#"
                                    style="left: 0%;"></a><a
                                    class="ui-slider-handle ui-state-default ui-corner-all" href="#"
                                    style="left: 100%;"></a>
                            </div>
                            <div class="range-content">
                                <div class="range-input-group">
                                    <label class="range__label range__label-min p-0 m-0 mr-2"
                                           for="amount_min">От</label>
                                    <input class="range-input mr-2 js-range-slider" type="number" id="amount_min">
                                    <label class="range__label range__label-max p-0 m-0 mr-2"
                                           for="amount_min">До</label>
                                    <input class="range-input" type="number" id="amount_max">
                                </div>
                            </div>
                        </div>
                    </div>--}}

                    @if(count($colorFilters))
                        <div class="filter__div d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center filter__button">
                                <span>{{ t('Products.color filter') }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>

                            <div class="filter__content color-select-input">
                                @foreach($colorFilters as $colorFilter)
                                    <div class="color-select d-flex changecolor align-items-center mb-3"
                                         data-color="#{{ $colorFilter->hex_color }}" data-id="{{ $colorFilter->id }}">
                                        <div class="color mr-2"
                                             style="background-color: {{ '#'.$colorFilter->hex_color }};">
                                            <img class="colorCheck" src="{{ asset('images/001-checked.svg') }}"
                                                 alt="{{ $colorFilter->name }}" title="{{ $colorFilter->name }}">
                                        </div>
                                        <span class="color__name">{{ $colorFilter->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($filters))
                        @foreach($filters as $filter)
                            <div class="filter__div d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center filter__button">
                                    <span>{{ $filter->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="filter__content">
                                    @foreach($filter->criteria as $criterion)
                                        <div class="filter-item filter-criteria" data-criterion="{{ $criterion->id }}">
                                            {{ $criterion->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="small__sorting d-flex align-items-center">
                    <div class="mobile-filters-open d-sm-none">
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="21.826" height="17.956" viewBox="0 0 21.826 17.956">--}}
{{--                            <g transform="translate(0 0)">--}}
{{--                                <path d="M10.1,57.531a.774.774,0,0,0-1.068.077L6.207,60.793V48.065a.763.763,0,0,0-1.526,0V60.793L1.856,57.608a.776.776,0,0,0-1.068-.077A.776.776,0,0,0,.711,58.6l4.161,4.656a.708.708,0,0,0,1.108,0l4.2-4.656A.77.77,0,0,0,10.1,57.531Z" transform="translate(-0.533 -45.567)"/>--}}
{{--                                <path d="M230.777,4.943,226.653.287a.715.715,0,0,0-1.145,0l-4.2,4.656a.774.774,0,0,0,.077,1.068.736.736,0,0,0,1.068-.077l2.825-3.185V15.44a.767.767,0,0,0,.763.763.734.734,0,0,0,.763-.726V2.752l2.825,3.185a.758.758,0,1,0,1.145-.994Z" transform="translate(-209.129 0)"/>--}}
{{--                            </g>--}}
{{--                        </svg>--}}
                        <svg id="_001-filter-results-button" data-name="001-filter-results-button" xmlns="http://www.w3.org/2000/svg" width="23.52" height="15.68" viewBox="0 0 23.52 15.68">
                            <g id="filter" transform="translate(0 0)">
                                <path id="Path_12558" data-name="Path 12558" d="M9.147,92.18h5.227V89.566H9.147ZM0,76.5v2.613H23.52V76.5Zm3.92,9.146H19.6V83.033H3.92Z" transform="translate(0 -76.5)"/>
                            </g>
                        </svg>

                    </div>
                    <div class="sort__dropdown-wrapper">
                        <div class="dropdown">
                            <div class="form-group sorting-section">
                                <label for="sort">Сортировка по:</label>
                                <div class="sorting-section-drop dropdown">
                                    <button class="btn dropdown-toggle sorting-btn " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button" data-sorting="1">сначала новые</button>
                                        <button class="dropdown-item" type="button" data-sorting="2">сначала старые</button>
                                        <button class="dropdown-item" type="button" data-sorting="3">по убыванию цены</button>
                                        <button class="dropdown-item" type="button" data-sorting="4">по возрастанию цены</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="modal__buttons d-flex align-items-center justify-content-between">
                            <button class="d-flex justify-content-center align-items-center button-close">
                                <i class="fas fa-times mr-3"></i>
                                <span>{{ t('Products.filter cancel') }}</span>
                            </button>

                            <button class="d-flex justify-content-center align-items-center button-apply">
                                <i class="fas fa-check mr-3"></i>
                                <span>{{ t('Products.filter apply') }}</span>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="" id="products-wrapper"></div>
            </div>
        </div>
    </div>
    {{--<div class="container-fluid productlist" style="display: none">
        <div class="row">
            @if(count($childCategories = $category->children) || count($childCategories = $category->parent->children))
                <div class="d-none d-lg-flex flex-column col-lg-2">
                    <h1 class="filter-block-title">
                        {{$category->parent->name}}
                    </h1>
                    @foreach($childCategories as $child)
                        <span>
                            <a href="{{ route('products.category.list',['url'=>$child->url]) }}" class="categories-link">
                                {{ $child->name }}
                            </a>
                        </span>
                    @endforeach
                </div>
            @endif

            <div class="col-12 col-lg-10 d-flex flex-column position-static filter__web__structure">
                <div class="filter__block ">

                    @if(count($brands))
                        <div class="filter__div mb-3 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center filter__button">
                                <span>Бренды*</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="filter_brand-details filter__content">
                                @foreach($brands as $brand)
                                    <div class="brand">{{ $brand->title }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    --}}{{--<div class="collapse-menu-bar">
                        <div class="d-flex justify-content-between align-items-center collapse-menu-title active ">
                            <span>Цена</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="11.876" height="6.276"
                                 viewBox="0 0 11.876 6.276">
                                <path id="Path_728" data-name="Path 728"
                                      d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                      transform="translate(0 118.789) rotate(-90)"/>
                            </svg>
                        </div>
                        <div class="product-types active">
                            <div class="range-slider-section">
                                <div class="range-slider">
                                    <input type="text" class="js-range-slider" value=""/>
                                </div>
                                <div class="extra-controls">
                                    <label for="from">От
                                        <input type="text" class="form-control js-input-from" id="from">
                                    </label>
                                    <label for="to">До
                                        <input type="text" class="form-control js-input-to" id="to">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>--}}{{--
                    <div class="filter__div mb-3 d-flex flex-column position-static">
                        <div class="d-flex justify-content-between align-items-center filter__button">
                            <span>Цена*</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter_price-details filter__content">
                            <div class="range-block">
                                <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
                                    <div class="ui-slider-range range-color ui-widget-header ui-corner-all" style="left: 0%; width: 100%; background: #E0E0E0 !important"></div>
                                    <a class="ui-slider-handle ui-state-default  ui-corner-all" href="#" style="left: 0%;"></a>
                                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a>
                                </div>
                                <div class="range-content">
                                    <div class="range-input-group">
                                        <label class="range__label p-0 m-0 mr-2" for="amount_min">От</label>
                                        <input class="range-input mr-2" type="number" id="amount_min">
                                        <label class="range__label p-0 m-0 mr-2" for="amount_min">До</label>
                                        <input class="range-input" type="number" id="amount_max">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($colorFilters))
                        <div class="filter__div mb-3 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center filter__button">
                                <span>Цвет*</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="filter_color-details filter__content" data-colorselect>
                                @foreach($colorFilters as $colorFilter)
                                    <div class="d-flex changecolor align-items-center mb-3" data-color="#{{ $colorFilter->hex_color }}" data-id="{{ $colorFilter->id }}">
                                        <div class="color mr-2" style="background-color: #{{ $colorFilter->hex_color }};">
                                            <img class="colorCheck" src="{{ asset('images/001-checked.svg') }}" alt="{{ $colorFilter->name }}" title="{{ $colorFilter->name }}">
                                        </div>
                                        <span class="color__name">{{ $colorFilter->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($filters))
                        @foreach($filters as $filter)
                            <div class="filter__div mb-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center filter__button">
                                    <span>{{ $filter->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="filter_content-details filter__content">
                                    @foreach($filter->criteria as $criterion)
                                        <div class="filter-item filter-criteria" data-criterion="{{ $criterion->id }}">
                                            {{ $criterion->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>

                <div class="small__sorting d-flex align-items-center">
                    <img class="d-sm-none filter__button__mobile ml-3" id="myBtn" src="{{ asset('images/001-filter-results-button.png') }}" alt="Сортировка по:*" title="Сортировка по:*">
                    <div class="sort__dropdown-wrapper">
                        <span class="text d-none d-sm-inline">Сортировка по:*</span>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSorting" data-toggle="dropdown">
                                <span class="d-none d-sm-inline">По популярности*</span>
                                <img class="d-sm-none sort__button" src="{{ asset('images/001-sort.png') }}" alt="Сортировка по:*" title="Сортировка по:*">
                                <img class="d-none d-sm-inline ml-2" src="{{ asset('images/arrowselect.png') }}" alt="Сортировка по:*" title="Сортировка по:*">
                            </button>

                            <div class="dropdown-menu sorting-btn" aria-labelledby="dropdownSorting">
                                <ul>
                                    <li class="text-center d-flex justify-content-center dropdown-item m-0 p-0" data-sorting="0">
                                        <a class="dropdown-link" href="javascript:void(0)">По рейтингу*</a>
                                    </li>
                                    <li class="text-center d-flex justify-content-center dropdown-item m-0 p-0" data-sorting="1">
                                        <a class="dropdown-link" href="javascript:void(0)">По популярности*</a>
                                    </li>
                                    <li class="text-center d-flex justify-content-center dropdown-item m-0 p-0" data-sorting="2">
                                        <a class="dropdown-link" href="javascript:void(0)">По обновлению*</a>
                                    </li>
                                    <li class="text-center d-flex justify-content-center dropdown-item m-0 p-0" data-sorting="3">
                                        <a class="dropdown-link" href="javascript:void(0)">По скидке*</a>
                                    </li>
                                    <li class="text-center d-flex justify-content-center dropdown-item m-0 p-0" data-sorting="4">
                                        <a class="dropdown-link" href="javascript:void(0)">По минимальной цене*</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal filter-control-panel">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="modal__buttons d-flex align-items-center justify-content-between">
                            <button class="d-flex justify-content-center align-items-center button-close">
                                <i class="fas fa-times mr-3"></i>
                                <span>Отменить*</span>
                            </button>

                            <button class="d-flex justify-content-center align-items-center button-apply">
                                <i class="fas fa-check mr-3"></i>
                                <span>Применить*</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row items" id="products-wrapper">
                    @for($i = 0; $i < 20; $i++)
                        <div class="col-6 col-lg-4">
                            <div class="mycard d-flex justify-content-center align-items-center flex-column text-center">
                                <div class="position-relative">
                                    <img class="w-100" src="{{ asset('images/1958074-1.png') }}" alt="" title="">
                                    <div class="card__buttons d-flex flex-column">
                                        <div class="card-btn d-flex justify-content-center align-items-center mb-2">
                                            <img src="{{ asset('images/hearth.svg') }}" alt="" title="">
                                        </div>

                                        <div class="card-btn d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('images/basket.svg') }}" alt="" title="">
                                        </div>
                                    </div>
                                </div>

                                <h3><a class="item__name" href="#">Майка бельевая</a></h3>

                                <div class="amount">
                                    <div class="down">
                                        <img src="{{ asset('images/counterarrowleft.png') }}" alt="" title="">
                                    </div>

                                    <input class="amountInp" type="number" value="1">

                                    <div class="up">
                                        <img src="{{ asset('images/counterarrowright.png') }}" alt="" title="">
                                    </div>
                                </div>

                                <div class="star-rating">
                                    <span class="fas fa-star rating" data-rating="1"></span>
                                    <span class="far fa-star rating" data-rating="2"></span>
                                    <span class="far fa-star rating" data-rating="3"></span>
                                    <span class="far fa-star rating" data-rating="4"></span>
                                    <span class="far fa-star rating" data-rating="5"></span>
                                    <input type="hidden" name="rating" class="rating-value" value="3">
                                </div>

                                <div class="prices d-flex flex-column flex-sm-row justify-content-center align-items-center">
                                    <p class="price m-0 mr-sm-2">7.500.000<span class="tenge">₸</span></p>
                                    <p class="price-old m-0">18.500.000<span class="tenge">₸</span></p>
                                </div>

                            </div>
                        </div>
                    @endfor
                </div>

                <nav class="page__navigation" aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="6.275" height="11.876" viewBox="0 0 6.275 11.876">
                                    <path id="Контур_12513"
                                          data-name="Контур 12513"
                                          d="M113.326,5.938,118.689.574A.336.336,0,0,0,118.214.1l-5.6,5.6a.335.335,0,0,0,0,.475l5.6,5.6a.338.338,0,0,0,.236.1.328.328,0,0,0,.236-.1.335.335,0,0,0,0-.475Z"
                                          transform="translate(-112.513)"/>
                                </svg>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                        <li class="page-item"><a class="page-link" href="#">7</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">8</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="6.276" height="11.876" viewBox="0 0 6.276 11.876">
                                    <path id="Контур_728"
                                          data-name="Контур 728"
                                          d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                          transform="translate(-112.513)"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>--}}






    {{--<div class="container">
        <div class="product-list d-flex">
            <div class="product-left-bar w-100">
                <div class="collapse-menu m-2 mr-3">
                    @if(count($childCategories = $category->children) || count($childCategories = $category->parent->children))
                        <div class="collapse-menu-bar">
                            <div class="d-flex justify-content-between align-items-center collapse-menu-title active ">
                                <span>Категории</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.876" height="6.276"
                                     viewBox="0 0 11.876 6.276">
                                    <path id="Path_728" data-name="Path 728"
                                          d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                          transform="translate(0 118.789) rotate(-90)"/>
                                </svg>
                            </div>
                            <div class="product-types active">
                                <ul>
                                    @foreach($childCategories as $child)
                                        <li data-category="{{ $child->url }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                 viewBox="0 0 10 10">
                                                <circle id="Ellipse_62" data-name="Ellipse 62" cx="5" cy="5" r="5"
                                                        fill="#666a75"/>
                                            </svg>
                                            <a href="{{ route('products.category.list',['url'=>$child->url]) }}">{{ $child->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="collapse-menu-bar">
                        <div class="d-flex justify-content-between align-items-center collapse-menu-title active ">
                            <span>Цена</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="11.876" height="6.276"
                                 viewBox="0 0 11.876 6.276">
                                <path id="Path_728" data-name="Path 728"
                                      d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                      transform="translate(0 118.789) rotate(-90)"/>
                            </svg>
                        </div>
                        <div class="product-types active">
                            <div class="range-slider-section">
                                <div class="range-slider">
                                    <input type="text" class="js-range-slider" value=""/>
                                </div>
                                <div class="extra-controls">
                                    <label for="from">От
                                        <input type="text" class="form-control js-input-from" id="from">
                                    </label>
                                    <label for="to">До
                                        <input type="text" class="form-control js-input-to" id="to">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($colorFilters))
                        <div class="collapse-menu-bar">
                            <div
                                class="d-flex justify-content-between align-items-center collapse-menu-title color-select-button active ">
                                <span>Цвет</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.876" height="6.276"
                                     viewBox="0 0 11.876 6.276">
                                    <path id="Path_728" data-name="Path 728"
                                          d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                          transform="translate(0 118.789) rotate(-90)"/>
                                </svg>
                            </div>
                            <div class="product-types active">
                                <div class="color-select"></div>
                                <select class="color-select-input" data-colorselect multiple>
                                    @foreach($colorFilters as $colorFilter)
                                        <option value="{{ $colorFilter->id }}" data-color="#{{ $colorFilter->hex_color }}">{{ $colorFilter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @foreach($filters as $filter)
                        <div class="collapse-menu-bar">
                            <div class="d-flex justify-content-between align-items-center collapse-menu-title  active ">
                                <span>{{ $filter->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.876" height="6.276"
                                     viewBox="0 0 11.876 6.276">
                                    <path id="Path_728" data-name="Path 728"
                                          d="M117.975,5.938,112.612.574A.336.336,0,0,1,113.087.1l5.6,5.6a.335.335,0,0,1,0,.475l-5.6,5.6a.338.338,0,0,1-.236.1.328.328,0,0,1-.236-.1.335.335,0,0,1,0-.475Z"
                                          transform="translate(0 118.789) rotate(-90)"/>
                                </svg>
                            </div>
                            <div class="product-types size-part active">
                                <ul>
                                    @foreach($filter->criteria as $criterion)
                                        <li class="filter-criteria" data-criterion="{{ $criterion->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                 viewBox="0 0 10 10">
                                                <circle id="Ellipse_62" data-name="Ellipse 62" cx="5" cy="5" r="5"
                                                        fill="#666a75"/>
                                            </svg>
                                            <p>{{ $criterion->name }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                    <div class="filter-control-panel">
                        <button class="btn btn-secondary ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17.459" height="12.821"
                                 viewBox="0 0 17.459 12.821">
                                <g id="tick" transform="translate(0 0)">
                                    <g id="Group_1383" data-name="Group 1383" transform="translate(0 0)">
                                        <path id="Path_724" data-name="Path 724"
                                              d="M17.2,68.253a.873.873,0,0,0-1.235,0L5.51,78.712l-4.02-4.02A.873.873,0,0,0,.255,75.926l4.637,4.637a.873.873,0,0,0,1.235,0L17.2,69.488A.873.873,0,0,0,17.2,68.253Z"
                                              transform="translate(0 -67.997)" fill="#fff"/>
                                    </g>
                                </g>
                            </svg>
                            Применить
                        </button>
                        <button onclick="CloseFilterBtn()" class="btn btn-outline-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17.813" height="18.629"
                                 viewBox="0 0 17.813 18.629">
                                <defs>
                                    <clipPath id="clip-path">
                                        <rect id="Rectangle_923" data-name="Rectangle 923" width="17.813"
                                              height="18.629" transform="translate(0)" fill="#1f2535" stroke="#707070"
                                              stroke-width="1"/>
                                    </clipPath>
                                </defs>
                                <g id="Mask_Group_43" data-name="Mask Group 43" clip-path="url(#clip-path)">
                                    <g id="menu" transform="translate(2.456 2.456)">
                                        <g id="Group_1315" data-name="Group 1315"
                                           transform="translate(1.064 0) rotate(45)">
                                            <g id="Group_1314" data-name="Group 1314">
                                                <path id="Path_715" data-name="Path 715"
                                                      d="M17.093,0H.72A.737.737,0,0,0,0,.753a.737.737,0,0,0,.72.753H17.093a.737.737,0,0,0,.72-.753A.737.737,0,0,0,17.093,0Z"
                                                      fill="#1f2535"/>
                                            </g>
                                        </g>
                                        <g id="Group_1317" data-name="Group 1317"
                                           transform="translate(0 12.596) rotate(-45)">
                                            <g id="Group_1316" data-name="Group 1316">
                                                <path id="Path_716" data-name="Path 716"
                                                      d="M17.093,0H.72A.737.737,0,0,0,0,.753a.737.737,0,0,0,.72.753H17.093a.737.737,0,0,0,.72-.753A.737.737,0,0,0,17.093,0Z"
                                                      fill="#1f2535"/>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            Отменить
                        </button>
                    </div>
                </div>
            </div>
            <div style="min-width: calc(100% - 300px)">
                <div class="filter-section">
                    <div class="filter-section-button btn">Фильтровать</div>
                    <div class="form-group sorting-section">
                            <label for="sort">Сортировать:</label>
                            <div class="sorting-section-drop dropdown">
                            <button class="btn dropdown-toggle sorting-btn " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <button class="dropdown-item" type="button" data-sorting="0">по умолчанию</button>
                                <button class="dropdown-item" type="button" data-sorting="1">по убыванию цены</button>
                                <button class="dropdown-item" type="button" data-sorting="2">по возрастанию цены</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pl-xl-3" id="products-wrapper">
                </div>
            </div>
        </div>
    </div>--}}
@endsection
@push('js')
    <script>
        $('.sorting-section-drop > .dropdown-menu >.dropdown-item').click(function () {
            $('.sorting-section-drop > .dropdown-menu >.dropdown-item').removeClass('active');
            const textBtn = $(this).text();
            $(this).addClass('active');
            $('.sorting-section-drop > .sorting-btn').text(textBtn).attr('data-sorting', $(this).attr('data-sorting'));

            viewModel.setSortingType($(this).attr('data-sorting'));

            viewModel.fetchProducts();
        });
    </script>
@endpush

