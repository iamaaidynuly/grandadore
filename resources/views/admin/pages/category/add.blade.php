@extends('admin.layouts.app')
@section('title', 'Добавление категории')
@section('content')
    <section class="addItem-container ">
        <div class="section-header custom-section-header col-lg-offset-1 col-md-10">
            <div class="breadcrumbs-container clearfix">
                <ul class="breadcrumb pull-left">
                    <li><a href="{{ route('admin.category.list') }}">Категории | </a></li>
                    @if($parent)
                        @if(count($onlyParents) > 0)
                            @foreach($onlyParents as $subParent)
                                <li>
                                    <a href="{{ route('admin.category.list', ['category' => $subParent->id]) }}"> {{ $subParent->name }}
                                        | </a>
                                </li>
                            @endforeach
                        @endif
                        <li style="margin-left: 5px">
                            <a href="{{ route('admin.category.list', ['category' => $parent->id]) }}">   {{ $parent->name }} </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div style="width: 100%;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form"
                          action="{{ route('admin.category.add', ['parent_id' => $parent ? $parent->id : '']) }}"
                          method="post" enctype="multipart/form-data">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row" style="flex-direction: column">
                                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                    <input type="text" name="name[{!! $iso !!}]" class="form-control" placeholder="Название"
                                           value="{{ old('name'.$iso )}}">
                                    @endbylang
                                    @if(!empty($parent))
                                        <div class="card">
                                            <div class="c-title">Изображение (280 x 280)</div>
                                            <div class="c-body">
                                                <div class="custom-file c-file">
                                                    <input type="file" name="image"
                                                           class="custom-file-input c-file-input"
                                                           data-original-title="Выберите Изображение..." id="image">
                                                    <label class="custom-file-label c-file-label" for="image">Выберите
                                                        Изображение...</label>
                                                    <div class="invalid-feedback">Выберите Изображение</div>
                                                </div>
                                            </div>
                                        </div>
                                        @seo(['item'=>$categoryData??null])@endseo
                                    @endif
                                    <input placeholder="" type="number" style="display:none;"
                                           value="{{ $parent ? $parent->id : '' }}" name="parent_id">
                                    {{--@widget('Admin\metaForms', ['data' => false])--}}
                                </div>
                            </div>
                            <div class="card-actionbar">
                                <div class="card-actionbar-row">
                                    <button type="submit" class="btn ink-reaction btn-raised btn-primary"
                                            name="create">Добавить
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')


@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/materialadmin/css/theme-default/libs/select2/select2.css') }}">
    <style>
        ul.breadcrumb {
            background-color: #e5e6e6 !important;
        }
    </style>
@endsection
