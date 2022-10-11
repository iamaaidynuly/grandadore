@extends('admin.layouts.app')
@section('content')

        <div class="card">


            <div class="card-body table-responsive">
                {{--                @empty($readonly)--}}
                {{--                    <div class="pb-2">--}}
                {{--                        <button class="btn btn-danger" data-toggle="modal" data-target="#clearHistoryModal">Очистка истории</button>--}}
                {{--                    </div>--}}
                {{--                @endempty--}}
                <table class="table table-striped m-b-0 columns-middle my-datatable">
                    <thead>
                    <tr>
                        <th>N</th>
                        <th>Имя</th>
                        <th>Номер</th>
                        <th>Электронная почта</th>
                        <th>Удалить</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($all as $key => $val)
                        <tr class="item-row" data-id="">
                            <td class="count">{{ $key }} </td>
                            <td class="name">{{ $val->name }}</td>
                            <td class="item-title">{{ $val->phone }}</td>
                            <td class="email">{{ $val->email }}</td>
                                <td class="item-title"></td>
                            <td> <button type="button" class="btn btn-danger del" data-id="{{ $val->id }}" onclick="change($(this).attr('data-id'))">Удалить</button>  </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


@endsection
@push('js')
    @js(aApp('datatables/datatables.js'))
    <script>
        $('.my-datatable').dataTable({
            order: [[1, 'desc']]
        });



        function change(id) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.orders.delete-call') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id' : id ,
                },
                success: function (response) {
                    location.reload() ;
                },
            });
        }

    </script>
@endpush
@push('css')
    @css(aApp('datatables/datatables.css'))
@endpush
