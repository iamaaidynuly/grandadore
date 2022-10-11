@extends('admin.layouts.app')
{{--@section('titleSuffix')| <a href="{!! route('admin.one-time-payment.add') !!}" class="text-cyan"><i class="mdi mdi-plus-box"></i> добавить</a>@endsection--}}
@section('content')
    @if(count($items))
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped m-b-0 columns-middle">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)

                        <tr class="item-row" data-id="{!! $item->id !!}">
                            <td class="item-title">{{ $item->a('title') }}</td>
                            <td class="item-title">{{ $item->price}}</td>
                            <td>
                                <a href="{{ route('admin.one-time-payment.edit', ['id'=>$item->id]) }}"
                                   {!! tooltip('Редактировать') !!} class="icon-btn edit"></a>
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
    @slot('title')Удаление новости@endslot
    <input type="hidden" id="pdf-item-id">
    <p class="font-14">Вы действительно хотите удалить данная новость?</p>
    @endmodal
@endsection
@push('css')
@endpush
@push('js')
    <script>
        var itemId = $('#pdf-item-id'),
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
                    url: '{!! route('admin.one-time-payment.delete') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: csrf,
                        _method: 'delete',
                        item_id: thisItemId,
                    },
                    error: function (e) {
                        modalError();

                    },
                    success: function (e) {
                        if (e.success) {
                            loader.removeClass('shown');
                            blocked = false;
                            toastr.success('Новость удалена');
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
