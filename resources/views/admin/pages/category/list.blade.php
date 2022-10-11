@extends('admin.layouts.app')
@section('title', ($parent ? $parent->name.' - ' : '') . 'Категории продуктов')
@section('content')
    <section class="style-default-bright">
        <div class="section-header">
            <div class="breadcrumbs-container clearfix">
                <a href="{{ route('admin.category.add', ['parent_id' => $parent ? $parent->id : '']) }}"
                   class="text-cyan"><i class="mdi mdi-plus-box"></i>Добавить</a>
                <ul class="breadcrumb pull-left">
                    {{--                    <li><a href="{{ route('admin.category.list') }}">Категории</a></li>--}}
                    @if($parent)
                        @if(count($onlyParents) > 0)
                            @foreach($onlyParents as $subParent)
                                <li>
                                    <a href="{{ route('admin.category.list', ['category' => $subParent->id]) }}">{{ $subParent->name_ru }}</a>
                                </li>
                            @endforeach
                        @endif
                        <li>
                            <a href="{{ route('admin.category.list', ['category' => $parent->id]) }}">{{ $parent->name_hy }}</a>
                        </li>
                    @endif
                </ul>

            </div>
            <a href="{{ route('admin.category.list') }}">Категории</a>

        </div>
        @php $count=0; @endphp
        <ul class="k-accordion-container " style="padding: 0;">
            @if(count($categories) > 0)
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped m-b-0 columns-middle">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th style="text-align: right">Действие</th>
                            </tr>
                            </thead>
                            <tbody class="table-sortable" data-action="{{ route('admin.categories.sort') }}">
                            @foreach($categories as $category)
                                @include('admin.partials.category', ['category' => $category])
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else

                <h2>Нет информации</h2>
            @endif
        </ul>
    </section>

    <!-- BEGIN SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="simpleModalLabel">Внимание</h4>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить данную категорию?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary deleteCategory">Подтвердить</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END SIMPLE MODAL MARKUP -->
@stop
@push('js')
    {{--    <script src="{{ asset('assets/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('assets/jquery-multilevel-accordion/accordion.js') }}"></script>--}}
    <script>
        'use strict';
        var cid = '';
        var that;
        var deleteModal = $('#deleteModal');
        $('.deleteTrigger').click(function () {
            that = $(this);
            cid = that.data('id');
            deleteModal.modal('show');
        });
        $('.deleteCategory').click(function () {
            $.get('/admin/items/categories/delete/' + cid, function (response) {
                if (response) {
                    that.parent().parent().remove();
                    deleteModal.modal('hide');
                    setTimeout(function () {
                        initTree();
                    }, 200);
                }
            });
        });
        $(document).ready(function () {
            $('.k-accordion-container').sortable({
                forcePlaceholderSize: true,
                axis: 'y',
                items: 'li',
                handle: 'a',
                listType: 'ul',
                placeholder: 'menu-highlight',
                maxLevels: 4,
                opacity: .6,
                update: function (event, ui) {
                    var element = ui.item;
                    var id = element.data('id');
                    var parent_id = 0;
                    if (element.parent().parent('li').length > 0) {
                        parent_id = element.parent().parent('li').data('id');
                    }
                    //changeItemParent(id, parent_id);
                    changeItemsOrder(element);
                }
            });
        });

        function changeItemsOrder(element) {
            var elements = element.parent().children('li');
            var orderedItems = [];
            elements.each(function (i, e) {
                var that = $(e);
                var pushable = {
                    id: that.data('id'),
                    sortable: i + 1,
                    deep: that.parents('li').length
                };
                orderedItems.push(pushable)
            });
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    orderedItems: orderedItems
                },
                url: '/admin/items/categories/change/order',
                success: function (response) {
                    console.log(response);
                }
            });
        }

        function changeItemParent(id, parent_id) {
            $.get('/admin/items/categories/change/parent/' + id + '/' + parent_id, function (response) {
                if (response) {
                    console.log(response);
                } else {
                    alert('some error');
                }
            });
        }





    </script>
@endpush

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
