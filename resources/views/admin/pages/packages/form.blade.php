@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.packages.edit', ['id'=>$item->id]):route('admin.packages.add') !!}"
          method="post" enctype="multipart/form-data">
        @csrf @method($edit?'patch':'put')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                    <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Название"
                           value="{{ old('title.'.$iso, tr($item, 'title', $iso)) }}">
                    @endbylang
                </div>
                <div class="card px-3 pt-3">
                    <p class="mb-3">Название продавца (компании)</p>

                    @labelauty(['id'=>'title_company', 'label'=>'Неактивно|Активно',
                    'checked'=>oldCheck('title_company', ($edit &&
                    empty($item->title_company))?false:true)])@endlabelauty
                </div>
                <div class="card p-3 ">
                    <p class="mb-3">Количество изображений</p>

                    <input min="1" type="number" max="9999999" style="text-indent: 10px;padding: 5px"
                           placeholder="Количество товаров (мин:0)" name="count_images" id="count_images"
                           value="{{( !empty($item->count_images)?$item->count_images:null)}}">
                </div>
                <div class="card p-3 ">
                    <p class="mb-3">Цена Пакета</p>
                    <input min="0" type="number" max="9999999" style="text-indent: 10px;padding: 5px"
                           placeholder="Цена Пакета (мин:0)" name="package_price" id="package_price"
                           value="{{($item->package_price)}}">
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card px-3 pt-3">
                    <p class="mb-3">Выбор города из списка</p>
                    @labelauty(['id'=>'check_city', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('check_city',
                    ($edit && empty($item->check_city))?false:true)])@endlabelauty
                </div>
                <div class="card p-3 ">
                    <p class="mb-3">Количество товаров</p>
                    <input min="1" type="number" max="9999999" style="text-indent: 10px;padding: 5px"
                           placeholder="Количество товаров (мин:0)" name="count_products" id="count_products"
                           value="{{( !empty($item->count_products)?$item->count_products:null)}}">
                </div>
                <div class="card px-3 pt-3">
                    <p class="mb-3">Показ каждого нового товара на главной странице площадки сроком 3 дня.</p>

                    @labelauty(['id'=>'show_in_home', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('show_in_home',
                    ($edit && empty($item->show_in_home))?false:true)])@endlabelauty
                </div>
                <div class="card px-3 pt-3">
                    <p class="mb-3">Использовать модули со стикерами – скидка, новинка.</p>
                    @labelauty(['id'=>'stickers', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('stickers', ($edit
                    && empty($item->stickers))?false:true)])@endlabelauty
                </div>

            </div>

        </div>

        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
@endsection
@push('js')
    @ckeditor
@endpush
