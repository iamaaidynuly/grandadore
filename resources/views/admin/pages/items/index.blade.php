@extends('admin.layouts.app')
@if(isset($company))
    @section('title', 'Лист товаров '.$company->name)
@else
    @section('title', 'Лист товаров')
@endif
@section('titleSuffix')
    | <a href="{!! route('admin.items.import') !!}" class="text-cyan"><i class="mdi mdi-disk"></i>Импорт товаров .excel</a>
    | <a href="{!! route('admin.items.import.images') !!}" class="text-cyan"><i class="mdi mdi-image"></i>Импорт
        изображений .zip</a>
    | <a href="{!! route('admin.items.add') !!}" class="text-cyan"><i class="mdi mdi-plus-box"></i>Добавить товар
        вручную</a>
@endsection
@section('content')
    @if(!empty(old('page')))
        @php($page=old('page'))
    @else
        @php($page=1)
    @endif
    <div class="tab-content">
        <div id="menu1" class="tab-pane fade in active show">
            <input type="text" id="myInput" onclick="">

            @if(!empty($moderated_items))
                @if(count($moderated_items))
                    <div class="card p-2">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0 columns-middle my-datatable">
                                <thead>
                                <tr>
                                    <th style="width: 200px!important">Название</th>
                                    <th>Артикул</th>
                                    <th>Дата</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody class="table-sortable" data-action="{{ route('admin.items.sort') }}">
                                @foreach($moderated_items as $item)
                                    <tr class="item-row {{!count($item->categories)?'text-danger':''}}"
                                        data-id="{!! $item->id !!}">
                                        {{--<td>
                                            @if(!empty($item->company->first()))
                                                <div class="text-success">
                                                    <a href="{{ route('admin.users.view', ['id'=>$item->company->first()->users->first()->id]) }}" {!! tooltip('Посмотреть') !!}>{{$item->company->first()->users->first()->name}}</a>
                                                </div>
                                            @else
                                                --/--
                                            @endif
                                        </td>--}}
                                        <td class="item-title">{{ $item->a('title') }}</td>
                                        <td class="item-title">{{ $item->code }}</td>
                                        <td>
                                            <span class="d-none">{{ $item->created_at->format('Ymd') }}</span>{{ $item->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <a href="{{ route('admin.items.filter', ['id'=>$item->id]) }}" class="ml-3">
                                                <span>Фильтры</span>
                                                <sup>({{ count($item->filters()) }})</sup>
                                            </a>
                                            <a href="{{ route('admin.items.edit', ['id'=>$item->id]) }}"
                                               {!! tooltip('Редактировать') !!} class="ml-2 icon-btn text-success fas fa-pencil-alt"></a>
                                            <a href="{{ route('admin.gallery', ['gallery'=>'items_item', 'key'=>$item->id]) }}"
                                               {!! tooltip('Галерея') !!} class="ml-2 icon-btn gallery"></a>
                                            {{--                                   <a href="{{ route('admin.video_gallery', ['gallery'=>'items_item', 'key'=>$item->id]) }}" {!! tooltip('Видеогалерея') !!} class="icon-btn video-gallery"></a>--}}
                                            @if(auth()->user()->role==1)
                                                <span class="d-inline-block ml-2 " style="margin-left:4px;"
                                                      data-toggle="modal" data-target="#itemDeleteModal">
                                            <a href="javascript:void(0)"
                                               class="icon-btn delete" {!! tooltip('Удалить') !!}></a>
                                        </span>
                                            @endif
                                            @if(!empty($item->reviews) && count( $item->reviews))
                                                <a href="{{ route('admin.reviews.main', ['id'=>$item->id]) }}"
                                                   {!! tooltip('Отзывы') !!} class="icon-btn ml-2 ">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div style="display: none;" class="col-12 save-btn-fixed" title="Потвердить">
                                <button type="submit"></button>
                            </div>
                        </div>
                    </div>
                @else
                    <h4 class="text-danger">@lang('admin/all.empty')</h4>
                @endif
            @endif
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
@endsection
@push('css')
    @css(aApp('datatables/datatables.css'))
    <style>
        #navigation li {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            border: 1px solid rgba(60, 62, 61, 0.33);
            margin-bottom: -2px;
            overflow: hidden;

        }

        #navigation li:first-child {
            margin-right: 5px;
        }

        #navigation li a.active {
            background: white;
        }
    </style>
@endpush
@push('js')
    @js(aApp('datatables/datatables.js'))
    <script>
        // #myInput is a <input type="text"> element

        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();

                $(".my-datatable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });


        $('.moderate_many').click(function () {
            if ($('table').find('input:checked').length) {
                $('.save-btn-fixed').css('display', 'block')
            } else {
                $('.save-btn-fixed').css('display', 'none')

            }
        })
        var index = "{!! $page !!}";

        var url = "{!! route('admin.items.moderate')  !!}"

        var table = $('.my-datatable').dataTable({
            order: [[2, 'desc']],
            "pageLength": 100
        });


        $('a[data-dt-idx="' + index + '"]').trigger('click')

        $(document).on('click', '.page-link', function () {
            var page = $(this).data('dt-idx');
            $('.table-sortable').find('.moderate').each(function () {

                $(this).attr('href', url + '/' + $(this).data('id') + '/' + page);
            })
        })
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
                    url: '{!! route('admin.items.delete') !!}',
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
                            toastr.success('Товар удален');
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
