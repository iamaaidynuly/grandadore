@extends('admin.pages.banners.layout')
@section('title', 'Контент страницы о нас')
@php $back_url = route('admin.news.main') @endphp
@section('body')
    @bannerBlock(['title'=>'Контент'])
    <div class="row">
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Контент'])
            @banner('content.title', 'Название блока')
            @banner('content.title1', 'Текст')
            @endcard
        </div>
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Изображение заголовка'])
            @banner('content.image', 'Изображение (рек шир. 1920 x 235)')
            @endcard

        </div>
    </div>
    @endbannerBlock
@endsection
