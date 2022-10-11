@extends('admin.layouts.app')
@section('titleSuffix')| <a href="{!! route('admin.delivery_regions.add') !!}" class="text-cyan"><i
        class="mdi mdi-plus-box"></i> добавить</a>

        <a href="{!! route('admin.minimum_total_cost.edit', ['id'=>1]) !!}" class="text-cyan">Минимальная общая стоимость</a>
@endsection
@section('content')
    @if(count($items))
        <div class="card">
            <div class="table-responsive p-2">
                <table class="table table-striped m-b-0 columns-middle init-dataTable">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Населенные пункты</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="item-row" data-id="{!! $item->id !!}">
                            <td class="item-title">{{ $item->title}}</td>
                            <td><a href="{{ route('admin.delivery_cities.main', ['id'=>$item->id]) }}">Перейти к
                                    населенным пунктам ({!! $item->cities_count !!})</a></td>
                            <td>
                                <a href="{{ route('admin.delivery_regions.edit', ['id'=>$item->id]) }}"
                                   {!! tooltip('Редактировать') !!} class="icon-btn edit"></a>
                                @if(auth()->user()->role==1 )
                                    <span class="d-inline-block" style="margin-left:4px;" data-toggle="modal"
                                          data-target="#itemDeleteModal">
                                        <a href="javascript:void(0)"
                                           class="icon-btn delete" {!! tooltip('Удалить') !!}></a>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h4 class="text-danger">@lang('admin/all.empty')</h4>
    @endif
    @modal(['id'=>'itemDeleteModal', 'centered'=>true, 'loader'=>true,
    'saveBtn'=>'Удалить',
    'saveBtnClass'=>'btn-danger',
    'closeBtn' => 'Отменить',
    'form'=>['id'=>'itemDeleteForm', 'action'=>'javascript:void(0)']])
    @slot('title')Удаление региона@endslot
    <input type="hidden" id="pdf-item-id">
    <p class="font-14">Вы действительно хотите удалить регион &Lt;<span id="pdm-title"></span>&Gt;?</p>
    @endmodal
@endsection
@push('css')
    @css(aApp('datatables/datatables.css'))
@endpush
@push('js')
    @js(aApp('datatables/datatables.js'))
    <script>
        var itemId = $('#pdf-item-id'),
            modalTitle = $('#pdm-title'),
            blocked = false,
            modal = $('#itemDeleteModal');
        loader = modal.find('.modal-loader');

        function modalError() {
            loader.removeClass('shown');
            blocked = false;
            toastr.error('Возникла проблема!');
            modal.modal('hide');
        }

        modal.on('show.bs.modal', function (e) {
            if (blocked) return false;
            var $this = $(this),
                button = $(e.relatedTarget),
                thisItemRow = button.parents('.item-row');
            itemId.val(thisItemRow.data('id'));
            modalTitle.html(thisItemRow.find('.item-title').html());

        }).on('hide.bs.modal', function (e) {
            if (blocked) return false;
        });
        $('#itemDeleteForm').on('submit', function () {
            if (blocked) return false;
            blocked = true;
            var thisItemId = itemId.val();
            if (thisItemId && thisItemId.match(/^[1-9][0-9]{0,9}$/)) {
                loader.addClass('shown');
                $.ajax({
                    url: '{!! route('admin.delivery_regions.delete') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: csrf,
                        _method: 'delete',
                        item_id: thisItemId,
                    },
                    error: function (e) {
                        modalError();
                        console.log(e.responseText);
                    },
                    success: function (e) {
                        if (e.success) {
                            loader.removeClass('shown');
                            blocked = false;
                            toastr.success('Регион удален.');
                            modal.modal('hide');
                            $('.item-row[data-id="' + thisItemId + '"]').fadeOut(function () {
                                $(this).remove();
                            });
                        } else modalError();
                    }
                });
            } else modalError();
        });
        $('.init-dataTable').dataTable();
    </script>
@endpush
