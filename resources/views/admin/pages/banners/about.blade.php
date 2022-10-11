@extends('admin.pages.banners.layout')
@section('title', 'Контент страницы о нас')
@php $back_url = route('admin.pages.main') @endphp
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
            @banner('content.image', 'Изображение (1440x350)')
            @endcard
        </div>
    </div>
    @endbannerBlock
@endsection
