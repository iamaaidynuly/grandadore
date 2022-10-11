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
                    <form class="form" action="{{ route('admin.filters.add') }}" method="post">
                        {{csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="" style="width: 100%">
                                        <div class="form-group">
                                            @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                                            <input type="text" name="name[{!! $iso !!}]" class="form-control" placeholder="Название Фильтра"
                                                   value="{{ old('name.'.$iso, tr($page, 'name', $iso)) }}">
                                            @endbylang
                                        </div>
                                    </div>
                                    @labelauty(['id'=>'status', 'label'=>'Неактивно|Активно',
                                    'checked'=>true])@endlabelauty

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
                                                @if(old('criterion') && count(old('criterion')['new']) > 0)
                                                    @foreach(old('criterion')['new'] as $i => $criterion)
                                                        <tr class="added-criterion-row">
                                                            <td>
                                                                <input placeholder="Введите название"
                                                                       class="form-control characteristics-inputs"
                                                                       name="criterion[new][{{ $i }}][name]"
                                                                       type="text"
                                                                       value="{{ $criterion['name'] }}">
                                                            </td>

                                                            <td class="text-center">
                                                                <a class="icon-btn delete delete-criterion-item"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else

                                                @endif
                                                </tbody>
                                            </table>
                                            <i class="icon-btn add char-add-row" onclick="addCriterionRow()"></i>
                                        </div><!--end .card -->
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
@push('js')

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
    </script>
@endpush
