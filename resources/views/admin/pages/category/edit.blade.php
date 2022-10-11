@extends('admin.layouts.app')
@section('title', 'Редактирование категории')
@section('content')
    <section class="addItem-container">
        <div class="section-header custom-section-header col-lg-offset-1 col-md-10">
            <div class="breadcrumbs-container clearfix">
                <ul class="breadcrumb pull-left">
                    <li><a href="{{ route('admin.category.list') }}">Категории | </a></li>
                    @if($parent)
                        @if(count($onlyParents) > 0)
                            @foreach($onlyParents as $subParent)
                                <li>
                                    <a href="{{ route('admin.category.list', ['parent_id' => $subParent->id]) }}"> {{ $subParent->name }}
                                        |
                                    </a>
                                </li>
                            @endforeach
                        @endif
                        <li>
                            <a href="{{ route('admin.category.list', ['parent_id' => $parent->id]) }}">{{ $parent->name }}</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('admin.category.list', ['parent_id' => $categoryData->id]) }}">{{ $categoryData->name }}</a>
                        </li>

                    @endif
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-1 col-md-10">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form" action="{{ route('admin.category.edit', ['id' => $categoryData->id]) }}"
                          method="post" enctype="multipart/form-data">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row" style="flex-direction: column">
                                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                    <input type="text" name="name[{!! $iso !!}]" class="form-control"
                                           placeholder="Название"
                                           value="{{ old('name.'.$iso, tr($categoryData, 'name', $iso)) }}">
                                    @endbylang
                                    <div class="card">
                                        @if(!empty($parent))
                                            <div class="c-title">Изоброжение (280 x 280)</div>
                                            <div class="c-body">
                                                <div class="custom-file c-file">
                                                    <input type="file" name="image"
                                                           class="custom-file-input c-file-input"
                                                           data-original-title="Выберите изоброжение..." id="image">
                                                    <label class="custom-file-label c-file-label" for="image">Выберите
                                                        изоброжение...</label>
                                                    <div class="invalid-feedback">Выберите изоброжение</div>
                                                </div>
                                            </div>

                                            @seo(['item'=>$categoryData??null])@endseo
                                            @labelauty(['id'=>'footer', 'label'=>'Не видно в footer | Видно в
                                            footer','checked'=> ($categoryData->footer)?true:false])@endlabelauty
                                        @endif
                                        <input placeholder="" type="number" style="display:none;"
                                               value="{{ $parent ? $parent->id : '' }}" name="parent_id">
                                    </div>
                                    @if(!empty($parent))
                                        <div class="col-xs-12">
                                            @if($categoryData->image)
                                                <a href="javascript:void(0)" class="delete-category-image"
                                                   data-id="{{ $categoryData->id }}">Удалить изображение</a>
                                            @endif
                                            <img src="{{ asset('u/categories/'.$categoryData->image) }}" alt=""
                                                 class="img-responsive">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-actionbar">
                                <div class="card-actionbar-row">
                                    <button type="submit" class="btn ink-reaction btn-raised btn-primary"
                                            name="create">Сохранить
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
@push('js')
    <script>
        // $('.select2-search').select2({
        //     language: 'ru',
        //     allowClear: true
        // });

        $('.delete-category-image').click(function () {
            var id = $(this).data('id');
            var that = $('.delete-category-image');
            $.get('/admin/items/categories/deleteImage/' + id, function (response) {
                if (response) {
                    that.siblings('img').remove();
                    that.remove();
                }
            });
        });
    </script>
@endpush
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/materialadmin/css/theme-default/libs/select2/select2.css') }}">
    <style>
        ul.breadcrumb {
            background-color: #e5e6e6 !important;
        }
    </style>
@endsection
