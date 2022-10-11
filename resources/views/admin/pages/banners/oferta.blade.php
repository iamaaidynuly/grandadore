@extends('admin.pages.banners.layout')
@section('title', 'Контент страницы  Публичная оферта ')
@section('body')
    @bannerBlock(['title'=>'Контент'])
    <div class="row">
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Контент'])
            @banner('content.title', 'Название блока')
            @banner('content.content', 'Текст')
            @endcard
        </div>
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Изображение заголовка'])
            @banner('content.image', 'Изображение (рек шир. 1920 )')
            @endcard

        </div>
    </div>
    @endbannerBlock
@endsection
