@extends('admin.layouts.app')

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
                    <th>Продукт</th>
                    <th>Имя</th>
                    <th>Адресс</th>
                    <th>Рейтинг</th>
                    <th>Статус</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                @foreach($otzivAll as $row=>$otziv)
                    <tr class="item-row thisRow{{$otziv->id}}" data-id="21212" data-toggle="collapse" href="#collapse{{$row}}" role="button"
                        aria-expanded="false" aria-controls="collapse{{$row}}">
                        <td><i class="fa fa-angle-down" title="Посмотреть отзыв"></i><a href="/product/{{isset($otziv->item) ? $otziv->item->url : ""}}">{{isset($otziv->item) ? $otziv->item->title : ""}}</a></td>
                        <td>{{ $otziv->name }}</td>
                        <td>{{ $otziv->email }}</td>
                        <td>{{ $otziv->star }}</td>
                        <td><select class="otzivStatus" data-id="{{$otziv->id}}" onchange="change($(this).attr('data-id'))">
                                <option value="0" {{ $otziv->status== 0 ? "Selected" :""}}>не показать</option>
                                <option value="1" {{ $otziv->status== 1 ? "Selected" :""}}>показать</option>
                            </select></td>
                        <td><button type="button" class="btn btn-danger" data-id="{{ $otziv->id  }}" onclick="commentRemove(this.getAttribute('data-id'))">Удалить</button></td>

                    </tr>
                    <tr class="collapse thisRow{{$otziv->id}}" id="collapse{{$row}}">
                        <td> {{ $otziv->otziv }}</td>
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


        function change(id,otziv_id) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.commentStatus') }}',
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
                url: '{{ route('admin.commentRemove') }}',
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
