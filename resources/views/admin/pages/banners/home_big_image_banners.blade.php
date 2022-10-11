@extends('admin.pages.banners.layout')
@section('title', 'Контент главной страницы ')
@php $back_url = route('admin.pages.main') @endphp
@section('body')
    @bannerBlock(['title'=>'Контент'])
    <div class="row w-100">
        <div class="col-12 m-3" style="background: white">
            <div class="col-12">
                @card(['title'=>'Изображение'])
                @banner('content.top', 'Изображение (1410 x 450)')
                @endcard
            </div>
            <div class="col-12">
                @card(['title'=>'Ссылка'])
                @banner('content.top-link', 'Ссылка для изображение')
                @endcard
            </div>
        </div>

        <div class="col-12 m-3" style="background: white">
            <div class="col-12">
                @card(['title'=>'Изображение'])
                @banner('content.bottom', 'Изображение (1410 x 450)')
                @endcard
            </div>
            <div class="col-12">
                @card(['title'=>'Ссылка'])
                @banner('content.bottom-link', 'Ссылка для изображение')
                @endcard
            </div>
        </div>
        <div class="col-12 m-3">
            <div class="row w-100">
                <div class="col-12 col-md-5  " style="background: white">
                    <div class="col-12">
                        @card(['title'=>'Изображение'])
                        @banner('content.left', 'Изображение (700 x 300)')
                        @endcard
                    </div>
                    <div class="col-12">
                        @card(['title'=>'Ссылка'])
                        @banner('content.left_link', 'Ссылка для изображение')
                        @endcard
                    </div>
                </div>
                <div class="col-12 col-md-5 offset-1" style="background: white;">
                    <div class="col-12">
                        @card(['title'=>'Изображение'])
                        @banner('content.right', 'Изображение (700 x 300)')
                        @endcard
                    </div>
                    <div class="col-12">
                        @card(['title'=>'Ссылка'])
                        @banner('content.right_link', 'Ссылка для изображение')
                        @endcard
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endbannerBlock
@endsection
