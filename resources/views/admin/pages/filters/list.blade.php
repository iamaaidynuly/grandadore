@extends('admin.layouts.app')
@section('title', 'Фильтры продуктов')
@section('content')
    <section class="style-default-bright">
        <div class="section-header">

            <div class="newProd-toggle">
                <a href="{{ route('admin.filters.add') }}"
                   class="text-cyan"><i class="mdi mdi-plus-box"></i>Добавить</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div style="width: 100%;">
                    <ul style="padding: 0; margin: 0; margin-top: 15px" class="k-accordion-container">
                        @if(count($filters) > 0)
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-striped m-b-0 columns-middle">
                                        <thead>
                                        <tr>
                                            <th style="text-align: left">Название</th>
                                            <th style="text-align: right">Действие</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td style="text-align: left" class="page-title">Цвет</td>
                                            <td style="text-align: right">
                                                <a href="{{ route('admin.colorFilters.list') }}">
                                                    <i class="icon-btn edit" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @foreach($filters as $filter)
                                            <tbody class="table-sortable"
                                                   data-action="{{ route('admin.categories.sort') }}">
                                            <tr class="page-row" data-id="{{ $filter->id }}">
                                                <td style="text-align: left" class="page-title">{{ $filter->name }}</td>
                                                <td style="text-align: right">
                                                    <a href="{{ route('admin.filters.edit', ['id' => $filter->id]) }}">
                                                        <i class="icon-btn edit" aria-hidden="true"></i>
                                                    </a>
                                                    <i class="icon-btn delete  clt-tool clt-delete-tool deleteTrigger "
                                                       title="Удалить" data-id="{{ $filter->id }}"
                                                       aria-hidden="true"></i>

                                                </td>
                                            </tr>
                                            </tbody>

                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @else
                            <h2>Нет информации</h2>
                        @endif


                    </ul>
                </div><!--end .col -->
            </div><!--end .row -->
        </div>
    </section>

    <!-- BEGIN SIMPLE MODAL MARKUP -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="simpleModalLabel">Вниамние!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить данный фильтр?</p>
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
            $.get('/admin/items/filters/delete/' + cid, function (response) {
                if (response) {
                    that.parent().parent().remove();
                    deleteModal.modal('hide');
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
        }


    </script>
@endpush

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/jquery-ui-1.12.1/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/jquery-multilevel-accordion/accordion.css') }}">
@stop

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
