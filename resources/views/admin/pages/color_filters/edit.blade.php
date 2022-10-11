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
                    <form class="form" action="{{ route('admin.colorFilters.edit', ['id' => $filter->id]) }}" method="post" autocomplete="off">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Название Фильтра" value="{{ old('name') ?? $filter->name }}">
                                            @endbylang
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <input type="text" name="hex_color" class="form-control" id="hex_color"
                                                   placeholder="HEX код цвета" value="{{ old('hex_color' ) ?? $filter->hex_color }}">
                                        </div>
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
@push('css')
    <link rel="stylesheet" media="screen" type="text/css" href="{{ aAdmin('css/colorpicker.css') }}" />
@endpush
@push('js')
    <script src="{{ aAdmin('js/colorpicker.js') }}"></script>
    <script type="text/javascript">
        $('#hex_color').ColorPicker({
            color: `#${$('#hex_color').val()}`,
            onChange: function (hsb, hex, rgb) {
                $('#hex_color').val(hex);
            }
        });

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
