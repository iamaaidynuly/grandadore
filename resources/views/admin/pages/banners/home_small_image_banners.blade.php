@extends('admin.pages.banners.layout')
@section('title', 'Контент главной страницы')
@php $back_url = route('admin.pages.main') @endphp
@section('body')
    @bannerBlock(['title'=>'Контент'])
    <div class="row">
        <div class="col-12 col-dxl-4" style="height: 500px;background: white">
            @card(['title'=>'Изображение'])
            @banner('content.left', 'Изображение ( 580 x 260)')
            @endcard
        </div>
        <div class="col-12 col-dxl-4" style="height: 500px;background: white">
            @card(['title'=>'Изображение'])
            @banner('content.right', 'Изображение (830 x 260)')
            @endcard
        </div>
    </div>
    @endbannerBlock
@endsection
