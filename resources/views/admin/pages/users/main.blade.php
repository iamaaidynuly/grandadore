@extends('admin.layouts.app')
@section('content')
    @if(isset($role))
        <a href="{{ route('admin.users.add.admin',[ 'role'=> $role]) }}" class="text-cyan"><i
                class="mdi mdi-plus-box"></i> Добавить {{$title}}</a>
    @else
        <a href="{{ route('admin.users.add',[ 'type' => $type]) }}" class="text-cyan"><i class="mdi mdi-plus-box"></i>
            Добавить {{$title}}</a>
    @endif
    @if(count($items))
        <div class="card mt-2">
            <div class="card-body table-responsive">
                <table class="table table-striped m-b-0 columns-middle my-datatable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Эл.почта</th>
                        <th>Статус</th>
                        @if(isset($type))
                            @if((int) auth()->user()->admin==1 && $type==1)
                                <th>Количество товаров</th>
                            @elseif((int) auth()->user()->admin==1 && $type==0)
                                <th>Количество заказов</th>
                            @endif
                        @endif
                        <th>Дата регистрации</th>

                        <th style="width: 200px;">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="item-row" data-id="{!! $item->id !!}">
                            <td class="item-id">{{ $item->id }}</td>
                            <td class="item-title"><a
                                    href="{{ route('admin.users.view', ['id'=>$item->id]) }}" {!! tooltip('Посмотреть') !!}>{{ $item->name }}</a>
                            </td>
                            <td class="item-title">{{ $item->email }}</td>

                            @if($item->active)
                                <td class="text-success d-flex justify-content-start align-items-center">
                                    <button type="button" data-toggle="modal" data-target="#blockUserModal{{$item->id}}"
                                            class="mr-2 button_block btn btn-{{ $item->active?'danger':'success' }}" {!! tooltip($item->active?'Блокировать':'Разблокировать') !!}>
                                        <i class="fas fa-user-times"></i></button>
                                    Активен
                                </td>
                            @else
                                <td class="text-danger d-flex justify-content-start align-items-center">
                                    <button type="button" data-toggle="modal" data-target="#blockUserModal{{$item->id}}"
                                            class="mr-2 button_block btn btn-{{ $item->active?'danger':'success' }}" {!! tooltip($item->active?'Блокировать':'Разблокировать') !!}>
                                        <i class="fas fa-user-check"></i></button>
                                    Блокирован
                                </td>

                            @endif
                            @modal(['id'=>'blockUserModal'.$item->id, 'centered'=>true,
                            'saveBtn'=>$item->active?'Блокировать':'Разблокировать',
                            'saveBtnClass'=>'btn-'.($item->active?'danger':'success'),
                            'closeBtn' => 'Отменить',
                            'form'=>['method'=>'post','action'=>route('admin.users.toggleActive')]])
                            @slot('title')Блокировка пользователя@endslot
                            <input type="hidden" name="active" value="{{ $item->active?0:1 }}">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="from_list" value="1">
                            @csrf @method('patch')
                            <p class="font-14">Вы действительно
                                хотите {{ $item->active?'блокировать':'разблокировать' }} данного пользователя?</p>
                            @endmodal @if(isset($type))

                                @if((int) auth()->user()->admin==1 && $type==1)
                                    <td>{{count($item->items)}}</td>
                                @elseif((int) auth()->user()->admin==1 && $type==0)
                                    <td>{{count($item->orders)}}</td>
                                @endif
                            @endif
                            <td><span
                                    class="d-none">{{ $item->created_at->format('Ymd') }}</span>{{ $item->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                @if(isset($type) && $type==1)
                                    <a href="{{ route('admin.gallery', ['gallery' => 'users', 'key' => $item->id]) }}"
                                       {!! tooltip('Галерея') !!} class="icon-btn gallery"></a>
                                    <a style="font-size: 15px" href="{{ route('admin.users.edit', ['id' => $item->id]) }}"
                                       {!! tooltip('Редактировать') !!} class="icon-btn edit"></a>
                                @endif
                                @if(isset($type) && $type==0)
                                    <a href="{{ route('admin.users.accept.email', ['id'=>$item->id]) }}"
                                       {!! tooltip('Подтвердить') !!} style="font-size: 14px">
                                        <span
                                            style="color:{{(!empty($item->verification))?'red':'green'}}"> {{(!empty($item->verification))?'Подтвердить':'Подтверждено'}}</span>
                                    </a>
                                @endif
                                @if(auth()->user()->role == 1)
                                    <span class="d-inline-block" data-toggle="modal" data-target="#itemDeleteModal"><a
                                            href="javascript:void(0)" style="font-size: 15px"
                                            class="icon-btn delete" {!! tooltip('Удалить') !!}></a></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @modal(['id'=>'itemDeleteModal', 'centered'=>true, 'loader'=>true,
        'saveBtn'=>'Удалить',
        'saveBtnClass'=>'btn-danger',
        'closeBtn' => 'Отменить',
        'form'=>['id'=>'itemDeleteForm', 'action'=>'javascript:void(0)']])
        @slot('title')Удаление Товара@endslot
        <input type="hidden" id="pdf-item-id">
        <p class="font-14">Вы действительно хотите удалить данная новость?</p>
        @endmodal
    @else
        <h4 class="text-danger">@lang('admin/all.empty')</h4>
    @endif

@endsection
@push('css')
    <style>
        .button_block {
            border-radius: 50%;
            height: 25px;
            width: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>

@endpush
@push('js')
    @js(aApp('datatables/datatables.js'))
    <script>
        var type = "{!! $title !!}";
        $('.my-datatable').dataTable({
            order: [],
            "pageLength": 100
        });
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
                    url: '{!! route('admin.users.delete') !!}',
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
                            toastr.success(type + 'удален');
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
@push('css')
    @css(aApp('datatables/datatables.css'))
@endpush
