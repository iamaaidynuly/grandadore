@extends('admin.pages.banners.layout')
@section('title', 'Контент главной страницы')
@php $back_url = route('admin.pages.main') @endphp
@section('body')
    @bannerBlock(['title'=>'Контент главной страницы'])
    <div class="row">
        <div class="col-12 col-dxl-6">
            @card(['title'=>'Контент'])
            @banner('main_banner.title', 'Заголовок H1')
            @banner('main_banner.content', 'Текст')
            @endcard
        </div>
    </div>
    @endbannerBlock
@endsection
