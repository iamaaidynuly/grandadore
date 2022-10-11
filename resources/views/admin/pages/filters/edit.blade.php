@extends('admin.layouts.app')
@section('title', 'Редактирование фильтра')
@section('content')
    <section class="addItem-container">
        <div class="container-fluid">
            <div class="row">
                <div class="w-100">
                    @if($errors->any())
                        <ul class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <form class="form" action="{{ route('admin.filters.edit', ['id' => $filter->id]) }}" method="post">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="w-100">
                                        <div class="form-group">
                                            @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                            <input type="text" name="name[{!! $iso !!}]" class="form-control" id="name"
                                                   placeholder="Название Фильтра" value="{{$filter->name}}">
                                            @endbylang
                                        </div>
                                        <?php
                                        if ($filter->status) {
                                            $status = true;
                                        } else {
                                            $status = false;
                                        }
                                        ?>
                                        @labelauty(['id'=>'status', 'label'=>'Неактивно|Активно',
                                        'checked'=>$status])@endlabelauty

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="char-add-row" style="font-size: 22px; float: left;">
                                            <span>Критерии</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card card-underline" style="margin:0;">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <td>
                                                        Название
                                                    </td>
                                                    <td>
                                                        Удалить
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody class="criteria-container">
                                                @if($filter->criteria)
                                                    @foreach($filter->criteria as $i => $criterion)
                                                        <tr class="added-criterion-row">
                                                            <td>
                                                                @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                                                <input placeholder="Введите название"
                                                                       class="form-control characteristics-inputs"
                                                                       name="criterion[old][{{ $i }}][name][{!! $iso !!}]"
                                                                       type="text"
                                                                       value="{{ $criterion['name'] }}">
                                                                <input type="text" name="name[{!! $iso !!}]" class="form-control" id="name"
                                                                       placeholder="Название Фильтра" value="{{$filter->name}}">
                                                                @endbylang
                                                            </td>

                                                            <td class="text-center">
                                                                <a class="icon-btn delete delete-criterion-item"
                                                                   data-id="{{$criterion->id}}"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            <input placeholder="Введите название"
                                                                   class="form-control characteristics-inputs"
                                                                   name="criterion[new][0][name]"
                                                                   type="text"
                                                                   value="">
                                                        </td>

                                                        <td class="text-center">
                                                            <a class="icon-btn delete delete-criterion-item"
                                                               data-id="{{$criterion->id}}"></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                            <i class="icon-btn add char-add-row" onclick="addCriterionRow()"></i>
                                        </div><!--end .card -->
                                    </div>
                                </div>

                            </div>
                        </div><!--end .card-body -->
                        <div class="card-actionbar">
                            <div class="card-actionbar-row">
                                <button type="submit" class="btn ink-reaction btn-raised btn-primary"
                                        name="editFilter">Сохранить
                                </button>
                            </div>
                        </div>
                    </form>
                </div><!--end .col -->
            </div>
        </div>
    </section>
@stop
@push('js')

    <script type="text/javascript">
        var charContainer = $('.criteria-container');

        function charRow(index) {
            return '<tr class="added-criterion-row">' +
                '<td>' +
                '<input placeholder="Введите название" class="form-control characteristics-inputs" name="criterion[new][' + index + '][name]" type="text" value="">' +
                '</td>' +

                '<td class="text-center">' +
                '<i class="icon-btn delete delete-criterion-item"></i>' +
                '</td>' +
                '</tr>';
        }

        $(document).on('click', '.delete-criterion-item', function () {
            var that = $(this);
            console.log(that.parents('tr'));
            if (that.parents('tr').hasClass('added-criterion-row')) {
                $.get('/admin/items/filters/criterion/delete/' + that.data('id'), function (response) {
                    if (response) {
                        that.parents('tr').remove();
                    }
                });
            } else {
                that.parents('tr').remove();
            }
        });

        function addCriterionRow() {
            var index = charContainer.find('.added-criterion-row').length;
            charContainer.append(charRow(index));
        }
    </script>
@endpush
@section('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">


@stop
@section('styles')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/materialadmin/css/theme-default/libs/multi-select/multi-select.css?1424887857') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/materialadmin/css/theme-default/libs/bootstrap-tagsinput/bootstrap-tagsinput.css?1424887862') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/materialadmin/css/theme-default/libs/typeahead/typeahead.css?1424887863') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/materialadmin/css/theme-default/libs/select2/select2.css?1424887856') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('assets/materialadmin/css/theme-default/libs/toastr/toastr.css?1425466569') }}"/>
@stop
