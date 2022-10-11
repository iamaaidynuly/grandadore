@extends('admin.layouts.app')
@section('titleSuffix')| <a href="{!! route('admin.addAddress') !!}" class="text-cyan"><i
        class="mdi mdi-plus-box"></i> добавить</a>@endsection
@section('content')
    <style>
        tbody > tr > td > i {
            transition: all 300ms ease;
            font-size: 18px;
            margin: 0 1rem ;
        }

        tbody > [aria-expanded='true'] > td > i {
            transform: rotate(180deg);

        }

        tbody > tr:nth-child(odd):hover {
            cursor: pointer;
        }
    </style>
    <div class="card">
        <div class="table-responsive p-2">
            <table class="table table-striped m-b-0 columns-middle init-dataTable">
                <thead>
                <tr>
                    <th>N</th>
                    <th>Адрес</th>
                    <th>Title</th>
                    <th>Номер</th>
                    <th>Статус</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pickupPoint as $row=>$value)
                    <tr class="item-row thisRow{{$value->id}}" data-id="21212" data-toggle="collapse" href="#collapse{{$row}}" role="button"
                        aria-expanded="false" aria-controls="collapse{{$row}}" >
                        {{--<td><i class="fa fa-angle-down"></i><a href="/product/{{$otziv->item->url}}">{{$otziv->item->title}}</a></td>--}}
                        <td onclick="location.href='{{ route('admin.editAddress',['id'=>$value->id]) }}'">{{ $row+1 }}</td>
                        <td onclick="location.href='{{ route('admin.editAddress',['id'=>$value->id]) }}'">{{ $value->address }}</td>
                        <td onclick="location.href='{{ route('admin.editAddress',['id'=>$value->id]) }}'">{{ $value->title }}</td>
                        <td onclick="location.href='{{ route('admin.editAddress',['id'=>$value->id]) }}'">{{ $value->phone }}</td>
                        <td><select class="otzivStatus" data-id="{{$value->id}}" onchange="change($(this).attr('data-id'))">
                                <option value="0" {{ $value->active== 0 ? "Selected" : ""}}>не показать</option>
                                <option value="1" {{ $value->active== 1 ? "Selected" : ""}}>показать</option>
                            </select></td>
                        <td>
                        <form action="{{ route('admin.addressRemove') }}" method="post">
                            @csrf
                            <input type="text" name="id" value="{{ $value->id }}" hidden>
                        <button type="submit" class="btn btn-danger" >Удалить</button>
                        </form>
                        </td>
                    </tr>
                   {{-- <tr class="collapse thisRow{{$otziv->id}}" id="collapse{{$row}}">
                        <td> {{ $otziv->otziv }}</td>
                    </tr>--}}
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


        $('.init-dataTable').dataTable();


       function change(id) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.changeStatus2') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id' : id ,
                },
            });
        }

        function commentRemove(id) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.addressRemove') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id' : id ,
                },
                success: function () {
                    $('.thisRow'+id).remove();
                }
            });

        }

    </script>
@endpush
