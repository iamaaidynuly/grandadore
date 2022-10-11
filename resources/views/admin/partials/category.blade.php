@php $count++ @endphp
<tr class="page-row" data-id="{{ $category->id }}">
    <td class="page-title">{{ $category->name }}</td>
    <td style="text-align: right">
        @if($category->deep < env('CATEGORY_DEEP'))
            <a href="{{ route('admin.category.list', ['parent_id' => $category->id]) }}">
                <span>Подразделы</span>
                <sup>({{ count($category->children) }})</sup>
            </a>
        @endif
        <a href="{{ route('admin.filters.filterCategory', ['id' => $category->id]) }}" class="ml-3">
            <span>Фильтры</span>
            <sup>({{ count($category->filters) }})</sup>
        </a>
                <span style="color : #0a90eb">главная</span>
            <input type="checkbox" class="in_home asd" style="color : #0a90eb" data-id="{{ $category->id }}" {{ $category->in_home == 1 ? "checked" : "" }} onchange="change($(this).attr('data-id'))">
        <a href="{{ route('admin.category.edit', ['id' => $category->id]) }} "
           {!! tooltip('Редактировать') !!} class="ml-2 icon-btn text-success fas fa-pencil-alt"> </a>
        <a href="javascript:void(0)" class="fa fa-trash  deleteTrigger icon-btn delete"
           {!! tooltip('Удалить') !!} data-id="{{ $category->id }}"
           aria-hidden="true"></a>
    </td>
</tr>


@modal(['id'=>'pageDeleteModal', 'centered'=>true, 'loader'=>true,
'saveBtn'=>'Удалить',
'saveBtnClass'=>'btn-danger',
'closeBtn' => 'Отменить',
'form'=>['id'=>'pageDeleteForm', 'action'=>'javascript:void(0)']])
@slot('title')Удаление страницы@endslot
<input type="hidden" id="pdf-page-id">
<p class="font-14">Вы действительно хотите удалить страницу &Lt;<span id="pdm-title"></span>&Gt;?</p>
@endmodal
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
@push('css')
@endpush
@push('js')
    {{--    <script>--}}
    {{--        var pageId = $('#pdf-page-id'),--}}
    {{--            modalTitle = $('#pdm-title'),--}}
    {{--            blocked = false,--}}
    {{--            modal = $('#pageDeleteModal');--}}
    {{--        loader = modal.find('.modal-loader');--}}
    {{--        function modalError() {--}}
    {{--            loader.removeClass('shown');--}}
    {{--            blocked = false;--}}
    {{--            toastr.error('Возникла проблема!');--}}
    {{--            modal.modal('hide');--}}
    {{--        }--}}
    {{--        modal.on('show.bs.modal', function(e){--}}
    {{--            if (blocked) return false;--}}
    {{--            var $this = $(this),--}}
    {{--                button = $(e.relatedTarget),--}}
    {{--                thisPageRow = button.parents('.page-row');--}}
    {{--            pageId.val(thisPageRow.data('id'));--}}
    {{--            modalTitle.html(thisPageRow.find('.page-title').html());--}}

    {{--        }).on('hide.bs.modal', function(e){--}}
    {{--            if (blocked) return false;--}}
    {{--        });--}}
    {{--        $('#pageDeleteForm').on('submit', function(){--}}
    {{--            if (blocked) return false;--}}
    {{--            blocked = true;--}}
    {{--            var thisPageId = pageId.val();--}}
    {{--            if (thisPageId && thisPageId.match(/^[1-9][0-9]{0,9}$/)) {--}}
    {{--                loader.addClass('shown');--}}
    {{--                $.ajax({--}}
    {{--                    url: '{!! route('admin.pages.delete') !!}',--}}
    {{--                    type: 'post',--}}
    {{--                    dataType: 'json',--}}
    {{--                    data: {--}}
    {{--                        _token: csrf,--}}
    {{--                        _method: 'delete',--}}
    {{--                        page_id: thisPageId,--}}
    {{--                    },--}}
    {{--                    error: function(e){--}}
    {{--                        modalError();--}}
    {{--                        console.log(e.responseText);--}}
    {{--                    },--}}
    {{--                    success: function(e){--}}
    {{--                        if (e.success) {--}}
    {{--                            loader.removeClass('shown');--}}
    {{--                            blocked = false;--}}
    {{--                            toastr.success('Страница удалено');--}}
    {{--                            modal.modal('hide');--}}
    {{--                            $('.page-row[data-id="'+thisPageId+'"]').fadeOut(function(){--}}
    {{--                                $(this).remove();--}}
    {{--                            });--}}
    {{--                        }--}}
    {{--                        else modalError();--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            }--}}
    {{--            else modalError();--}}
    {{--        });--}}
    {{--    </script>--}}
@endpush
<script>


    function change(id) {
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route('in_home')}}',
            data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                'id' : id ,

            },
        });
    }




</script>
