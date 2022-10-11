@extends('admin.layouts.app')
@section('titleSuffix')| <a href="{!! route('admin.pickup_points.add') !!}" class="text-cyan"><i
        class="mdi mdi-plus-box"></i> добавить</a>@endsection
@section('content')
    @if(count($items))
        <div class="card">
            <div class="table-responsive ">
                <table class="table table-striped m-b-0 columns-middle">
                    <thead>
                    <tr>
                        <th>Адрес</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody class="table-sortable" data-action="{{ route('admin.pickup_points.sort') }}">
                    @foreach($items as $item)
                        <tr class="item-row" data-id="{!! $item->id !!}">
                            <td class="item-title">{{ $item->address}}</td>
                            @if($item->active)
                                <td class="text-success">Активно</td>
                            @else
                                <td class="text-danger">Неактивно</td>
                            @endif
                            <td>
                                <a href="{{ route('admin.pickup_points.edit', ['id'=>$item->id]) }}"
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
    @slot('title')Удаление точки самовывоза@endslot
    <input type="hidden" id="pdf-item-id">
    <p class="font-14">Вы действительно хотите удалить точку самовывоза &Lt;<span id="pdm-title"></span>&Gt;?</p>
    @endmodal
@endsection
@push('js')
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
                    url: '{!! route('admin.pickup_points.delete') !!}',
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
                            toastr.success('Точка самовывоза удалена.');
                            modal.modal('hide');
                            $('.item-row[data-id="' + thisItemId + '"]').fadeOut(function () {
                                $(this).remove();
                            });
                        } else modalError();
                    }
                });
            } else modalError();
        });
    </script>
@endpush
