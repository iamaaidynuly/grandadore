@extends('admin.layouts.app')
@section('title', 'Добавление фильтра')
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
                    <form class="form" action="{{ route('admin.colorFilters.add') }}" method="post" autocomplete="off">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Название Фильтра" value="{{ old('name' )}}">
                                            @endbylang
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <input type="text" name="hex_color" class="form-control" id="hex_color"
                                                   placeholder="HEX код цвета" value="{{ old('hex_color' )}}">
                                        </div>
                                    </div>
                                </div>
                            </div><!--end .card-body -->
                            <div class="card-actionbar">
                                <div class="card-actionbar-row">
                                    <button type="submit" class="btn ink-reaction btn-raised btn-primary"
                                            name="addFilter">Создать фильтр
                                    </button>
                                </div>
                            </div>
                        </div><!--end .card -->
                    </form>
                </div><!--end .col -->
            </div><!--end .row -->
        </div>
    </section>
@stop
@push('css')
    <link rel="stylesheet" media="screen" type="text/css" href="{{ aAdmin('css/colorpicker.css') }}" />
@endpush
@push('js')
    <script src="{{ aAdmin('js/colorpicker.js') }}"></script>
    <script type="text/javascript">
        var charContainer = $('.criteria-container');

        function charRow(index) {
            return '<tr class="added-criterion-row">' +
                '<td>' +
                '<input placeholder="Введите название" class="form-control characteristics-inputs" name="criterion[new][' + index + '][name]" type="text" value="">' +
                '</td>' +

                '<td class="text-center">' +
                '<a class="icon-btn delete delete-criterion-item"></a>' +
                '</td>' +
                '</tr>';
        }

        $(document).on('click', '.delete-criterion-item', function () {
            $(this).parents('tr').remove();
        });

        function addCriterionRow() {
            var index = charContainer.find('tr').length;
            charContainer.append(charRow(index));
        }

        $('#hex_color').ColorPicker({
            onChange: function (hsb, hex, rgb) {
                $('#hex_color').val(hex);
            }
        });
    </script>
@endpush
