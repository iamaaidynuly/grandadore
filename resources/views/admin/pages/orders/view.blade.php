@extends('admin.layouts.app')
@section('content')
    <div class="card">
        @if(!empty($item))
            <div class="card-body">

                <div class="view-line"><span class="view-label">Номер заказа:</span> N{{ $item->id }}</div>
                @if ($item->user)
                    <div class="view-line"><span class="view-label">Пользователь:</span> <a
                            href="{{ route('admin.users.view', ['id' => $item->user->id]) }}">{{ $item->user->email ?? $item->user->phone ?? $item->user->id }}</a>
                    </div>
                @endif
                <div class="view-line"><span class="view-label">ФИО:</span> {{ $item->name??'-' }}</div>
                <div class="view-line"><span class="view-label">Телефон:</span> {{ $item->phone??'-' }}</div>
                <div class="view-line"><span
                        class="view-label">Дата:</span> {{ $item->created_at->format('d.m.Y H:i')??'-' }}</div>
                <div class="view-line"><span class="view-label">Метод доставки:</span> {{ $item->delivery_method_name }}
                </div>
                @if($item->delivery)
                    <div class="view-line"><span class="view-label">Регион:</span> {{ $item->region_name }}</div>
                    <div class="view-line"><span class="view-label">Населенный пункт:</span> {{ $item->city_name }}
                    </div>
                    <div class="view-line"><span class="view-label">Адрес:</span> {{ $item->address }}</div>
                    <div class="view-line"><span class="view-label">Цена доставки:</span> {{ $item->delivery_price ?: 'Бесплатно' }}
                    </div>
                @else
                    <div class="view-line"><span class="view-label">Точка самовывоза:</span>
                        @if ($item->pickup_point)
                            <a href="{{ route('admin.pickup_points.edit', ['id'=>$item->pickup_point->id]) }}">{{ $item->pickup_point_address }}</a>
                        @else
                            {{ $item->pickup_point_address }}
                        @endif
                    </div>
                @endif
                <div class="view-line">
                    <span class="view-label">Метод оплаты:</span>
                    {{ $item->payment_method_name }}
                    @if ($item->payment_method=='bank' && $item->paid==0 && $item->paid_request==1)
                        <span class="text-warning">(Ожидание подверждения)</span>
                    @endif
                </div>
                <div class="view-line"><span class="view-label">Сумма:</span> {{ $item->sum }}</div>
                <div class="view-line"><span class="view-label">Статус:</span> {!! $item->status_html !!}</div>
                @if($item->status_type=='new')
                    <div class="pt-2">
                        <button class="btn btn-success mr-2" data-toggle="modal" data-target="#acceptOrderModal">
                            Принять
                        </button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#denyOrderModal">Отклонить
                        </button>
                    </div>
                    @push('modals')
                        @modal(['id'=>'acceptOrderModal', 'saveBtn'=>'Принять', 'saveBtnClass'=>'btn-success','closeBtn'
                        => 'Отменить', 'centered'=>true,
                        'form'=>['method'=>'post','action'=>route('admin.orders.respond', ['id'=>$item->id])]])
                        @slot('title')Принять заказ?@endslot
                        <input type="hidden" name="status" value="1">
                        @csrf @method('patch')
                        <p class="font-weight-bold text-success">Принять заказ?</p>
                        @endmodal
                        @modal(['id'=>'denyOrderModal', 'saveBtn'=>'Отклонить', 'saveBtnClass'=>'btn-danger', 'closeBtn'
                        => 'Отменить', 'centered'=>true,
                        'form'=>['method'=>'post','action'=>route('admin.orders.respond', ['id'=>$item->id])]])
                        @slot('title')Отклонить заказ?@endslot
                        <input type="hidden" name="status" value="0">
                        @csrf @method('patch')
                        <p class="text-danger font-weight-bold">Отклонить заказ?</p>
                        @endmodal
                    @endpush
                @elseif($item->status_type=='pending')
                    <div class="view-line"><span
                            class="view-label">Статус оплаты:</span> {!! $item->paid?'<span class="text-success">оплачен</span>':'<span class="text-danger">не оплачен</span>' !!}
                    </div>
                    <div class="pt-2">
                        <button class="btn btn-info" data-toggle="modal" data-target="#changeOrderStatusModal">Изменить
                            процесс
                        </button>
                    </div>
                    @push('modals')
                        @modal(['id'=>'changeOrderStatusModal', 'saveBtn'=>'Сохранить', 'saveBtnClass'=>'btn-success',
                        'closeBtn' => 'Отменить', 'centered'=>true,
                        'form'=>['method'=>'post','action'=>route('admin.orders.change_process', ['id'=>$item->id])]])
                        @slot('title')Изменение процесса заказа@endslot
                        @csrf @method('patch')
                        <div>
                            <div>Статус</div>
                            <select id="process-select" name="process" class="select2" style="width:100%;">
                                @foreach($process as $process_status=>$process_name)
                                    <option
                                        value="{{ $process_status }}" {!! $item->process==$process_status?'selected':null !!}>{{ $process_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-2">
                            @labelauty(['id'=>'paid', 'label'=>'Оплачен',
                            'checked'=>$item->paid?true:false])@endlabelauty
                        </div>
                        <div id="ifAllChecked" class="text-danger font-14 pt-2" style="display: none">Заказ станет
                            выполненным
                        </div>
                        @push('js')
                            <script>
                                var paidCheckbox = $('#paid'),
                                    processSelect = $('#process-select'),
                                    ifAllChecked = $('#ifAllChecked'),
                                    checkProcess = function () {
                                        if (paidCheckbox.is(':checked') && processSelect.val() === '3') ifAllChecked.show();
                                        else ifAllChecked.hide();
                                    };
                                paidCheckbox.on('change', checkProcess);
                                processSelect.on('change', checkProcess);
                            </script>
                        @endpush
                        @endmodal
                    @endpush
                @endif
                <div class="pt-3">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Название</th>
                            <th>Цена</th>
                            @foreach($item->items as $orderItem)
                            @if($orderItem->color_id)
                            <th>Цвет</th>
                            @endif

                            @if($orderItem->size_id)
                            <th>Размер</th>
                            @endif
                            @endforeach
                            <th>Кол-во</th>
                            <th>Кол-во на складе</th>
                            @if(Request::segment(3) !=='sell')
                            @else
                                <th>Сумма</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($item->items as $orderItem)

                            <tr>
                                <td>{{ $orderItem->code ?? $orderItem->item->code ?? '-/-' }}</td>
                                <td> <a target="_blank" href="/product/{{ $orderItem->url ?? $orderItem->item->url }}" >{{ $orderItem->name ?? $orderItem->item->name }}</a> </td>
                                <td> {{ $orderItem->price }} </td>
                                @if($orderItem->color_id)
                                @foreach($color as $val)
                                @if($orderItem->color_id == $val->id)
                                <td> {{ $val->name }} </td>
                                @endif
                                @endforeach
                                @else
                                    <td></td>
                                @endif
                                @if($orderItem->size_id)
                                @foreach($size as $val)
                                    @if($orderItem->size_id == $val->id)
                                        <td> {{ $val->name }} </td>
                                    @endif
                                @endforeach
                                @else
                                    <td></td>
                                @endif
                                <td> {{ $orderItem->count }} </td>
                                <td>{{ $orderItem->item ? $orderItem->item->count : '-/-' }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                @if(Gate::check('admin') && ($item->status_type=='new' || $item->status_type=='declined'))
                    <div class="pt-5">
                        <button class="btn btn-outline-danger mr-1" data-toggle="modal" data-target="#deleteUserModal">
                            Удалить заказ
                        </button>
                    </div>
                    @push('modals')
                        @modal(['id'=>'deleteUserModal', 'saveBtn'=>'УДАЛИТЬ НАВСЕГДА', 'saveBtnClass'=>'btn-danger',
                        'closeBtn' => 'Отменить', 'centered'=>true,
                        'form'=>['method'=>'post','action'=>route('admin.orders.delete')]])
                        @slot('title')<span class="text-danger font-weight-bold">УДАЛЕНИЕ ЗАКАЗА</span>@endslot
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        @csrf @method('delete')
                        <p>Вы дейстительно хотите <span
                                class="text-danger font-weight-bold">УДАЛИТЬ ЗАКАЗ НАВСЕГДА</span>?</p>
                        @endmodal
                    @endpush
                @endif
            </div>
        @else


        @endif
    </div>
    @stack('modals')
@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    <script>
        $('.select2').select2();
    </script>
@endpush
@push('css')
    @css(aApp('select2/select2.css'))
@endpush
