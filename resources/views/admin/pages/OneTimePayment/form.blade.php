@extends('admin.layouts.app')
@section('content')
    <form
        action="{!! $edit?route('admin.one-time-payment.edit', ['id'=>$item->id]):route('admin.one-time-payment.add') !!}"
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
                <div class="card p-3 ">
                    <p class="mb-3">Цена одноразовой услуги</p>
                    <input min="0" type="number" max="9999999" style="text-indent: 10px;padding: 5px"
                           placeholder="Цена Пакета (мин:0)" name="price" id="package_price"
                           value="{{(!empty($item->price)?$item->price:null)}}">
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
