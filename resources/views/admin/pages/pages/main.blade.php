@extends('admin.layouts.app')
@section('titleSuffix')| <a href="{!! route('admin.pages.add') !!}" class="text-cyan"><i class="mdi mdi-plus-box"></i>
    добавить</a>@endsection
@section('content')
    @if(count($pages))
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped m-b-0 columns-middle">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody class="table-sortable" data-action="{{ route('admin.pages.sort') }}">
                    @foreach($pages as $page)
                        <tr class="page-row" data-id="{!! $page->id !!}">
                            <td class="page-title">{{ $page->a('title') }}</td>
                            @if($page->active)
                                <td class="text-success">Активно</td>
                            @else
                                <td class="text-danger">Неактивно</td>
                            @endif

                            <td>
                                <a href="{{ route('admin.pages.edit', ['id'=>$page->id]) }}"
                                   {!! tooltip('Редактировать') !!} class="icon-btn fas fa-pencil-alt text-success"></a>
                                @if (!$page->static)
                                    <a href="{{ route('admin.gallery', ['gallery'=>'pages', 'key'=>$page->id]) }}" {!! tooltip('Галерея') !!} class="icon-btn gallery"></a>
                                    {{--<a href="{{ route('admin.video_gallery', ['gallery'=>'pages', 'key'=>$page->id]) }}" {!! tooltip('Видеогалерея') !!} class="icon-btn video-gallery"></a>--}}
                                    <span class="d-inline-block" style="margin-left:4px;" data-toggle="modal" data-target="#pageDeleteModal">
                                        <a href="javascript:void(0)" class="icon-btn delete" {!! tooltip('Удалить') !!}></a>
                                    </span>

                                @else
                                    @if(array_key_exists($page->static, $content_pages))
                                        <a href="{{ $content_pages[$page->static] }}"
                                           {!! tooltip('Контент') !!} class="icon-btn text-orange far fa-newspaper"></a>
                                    @endif
                                    @if(array_key_exists($page->static, $gallery_pages))
                                        <a href="{{ $gallery_pages[$page->static] }}"
                                           {!! tooltip('Галерея') !!} class="icon-btn gallery"></a>
                                    @endif
                                    @if(array_key_exists($page->static, $video_gallery_pages))
                                        <a href="{{ $video_gallery_pages[$page->static] }}"
                                           {!! tooltip('Видеогалерея') !!} class="icon-btn video-gallery"></a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(auth()->user()->role==1 || auth()->user()->role==3)
                    <div class="py-2">
                        <a href="{{route('admin.main_slider.main')}}" class="text-orange fas fa-image px-4"
                           style="font-size: 22px"> Слайдер</a>
                    </div>
                @endif

                @if(auth()->user()->role==1 || auth()->user()->role==3)
                    <div class="py-2">
                        <a href="{{route('admin.news.main')}}" class="text-success fa fa-newspaper px-4"
                           style="font-size: 22px"> Новости</a>
                    </div>
                @endif
                @if(auth()->user()->role==1 || auth()->user()->role==3)
                    <div class="py-2">
                        <a href="{{route('admin.banners', ['page'=>'info'])}}" class="text-info fas fa-info-circle px-4"
                           style="font-size: 22px"> Информация</a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <h4 class="text-danger">@lang('admin/all.empty')</h4>
    @endif
    @modal(['id'=>'pageDeleteModal', 'centered'=>true, 'loader'=>true,
    'saveBtn'=>'Удалить',
    'saveBtnClass'=>'btn-danger',
    'closeBtn' => 'Отменить',
    'form'=>['id'=>'pageDeleteForm', 'action'=>'javascript:void(0)']])
    @slot('title')Удаление страницы@endslot
    <input type="hidden" id="pdf-page-id">
    <p class="font-14">Вы действительно хотите удалить страницу &Lt;<span id="pdm-title"></span>&Gt;?</p>
    @endmodal
@endsection
@push('css')
@endpush
@push('js')
    <script>
        var pageId = $('#pdf-page-id'),
            modalTitle = $('#pdm-title'),
            blocked = false,
            modal = $('#pageDeleteModal');
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
                thisPageRow = button.parents('.page-row');
            pageId.val(thisPageRow.data('id'));
            modalTitle.html(thisPageRow.find('.page-title').html());

        }).on('hide.bs.modal', function (e) {
            if (blocked) return false;
        });
        $('#pageDeleteForm').on('submit', function () {
            if (blocked) return false;
            blocked = true;
            var thisPageId = pageId.val();
            if (thisPageId && thisPageId.match(/^[1-9][0-9]{0,9}$/)) {
                loader.addClass('shown');
                $.ajax({
                    url: '{!! route('admin.pages.delete') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: csrf,
                        _method: 'delete',
                        page_id: thisPageId,
                    },
                    error: function (e) {
                        modalError();
                        console.log(e.responseText);
                    },
                    success: function (e) {
                        if (e.success) {
                            loader.removeClass('shown');
                            blocked = false;
                            toastr.success('Страница удалено');
                            modal.modal('hide');
                            $('.page-row[data-id="' + thisPageId + '"]').fadeOut(function () {
                                $(this).remove();
                            });
                        } else modalError();
                    }
                });
            } else modalError();
        });
    </script>
@endpush
