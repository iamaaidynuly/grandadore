@extends('admin.pages.banners.layout')
@section('title', 'Баннеры')
@php $back_url = route('admin.pages.main') @endphp
@section('body')
    <div class="row">
        <div class="col-12 col-dxl-6">
            @bannerBlock(['title'=>'Контент'])
            @card(['title' => 'Блок "Другие продукты"'])
            @banner('data.other_products', 'Название блока')
            @endcard
            @endbannerBlock
        </div>
    </div>
@endsection
