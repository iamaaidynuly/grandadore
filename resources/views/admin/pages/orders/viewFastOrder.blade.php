@extends('admin.layouts.app')
@section('content')
    <div class="card">
        @if(!empty($item))
            <div class="card-body">

                <div class="view-line"><span class="view-label">Номер заказа:</span> N{{ $item->id }}</div>
                {{--@if ($item->user)
                    <div class="view-line"><span class="view-label">Пользователь:</span> <a
                            href="{{ route('admin.users.view', ['id' => $item->user->id]) }}">{{ $item->user->email }}</a>
                    </div>
                @endif--}}
                <div class="view-line"><span class="view-label">ФИО:</span> {{ $item->name??'-' }}</div>
                <div class="view-line"><span class="view-label">Телефон:</span> {{ $item->nomer??'-' }}</div>
                <div class="view-line"><span
                        class="view-label">Дата:</span> {{ $item->created_at->format('d.m.Y H:i')??'-' }}</div>
                <div class="view-line"><span class="view-label">Город:</span> {{ $item->gorod??'-' }}</div>
                <div class="view-line"><span class="view-label">Адрес доставки:</span> {{ $item->dostavka??'-' }}</div>


                <div class="view-line"><span class="view-label">Сумма:</span> {{ request()->sum }}</div>
                <div class="pt-2">
                    <select  id="status">
                        <option value="1"></option>
                        <option value="2" {{ $item->status == 2 ?  "selected":"" }}>Отклонить</option>
                        <option value="3" {{ $item->status == 3 ?  "selected":"" }}>Принять</option>
                    </select>

                </div>
    </div>

            <div class="pt-3">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                    </tr>
                    </thead>

                    @foreach($item->itemsNew()->get() as $pivot)
                        <tr>
                        <td><a href="/product/{{ $pivot->url }}" >
                            {{ $pivot->title }} </a>
                        </td>
                        <td> {{ $pivot->price }}</td>
                        <td> {{$item->items()->where('item_id',$pivot->id)->first()->count }}  </td>
                    </tr>
                    @endforeach
        @endif
                    <input type="text" id="user" value="{{ $item->id }}" hidden>
                    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script>
                $('body').on('change','#status',function (){
                    change($('#user').val(),$(this).val())
                })
                function change(id,status) {
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('admin.orders.changeStatus') }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id' : id ,
                            'status' : status ,

                        },
                        success: function (data) {
                            return data ;
                        }
                    });
                }
       </script>

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


