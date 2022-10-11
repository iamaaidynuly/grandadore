@extends('admin.layouts.app')

@section('title', 'Статистика')
@section('content')

    <div class="card-body table-responsive">
        <table class="w-100 my-datatable table table-striped m-b-0 columns-middle">
            <thead>
            <tr>
                <th>N</th>
                <th>Дата</th>
                <th>Магазин</th>
                <th>Сумма</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td style="font-weight: 700">Итого</td>
                <td style="font-weight: 700"></td>
                <td style="font-weight: 700"></td>
                <td style="font-weight: 700"></td>
            </tr>
            </tfoot>
            <tbody>
            @if(auth()->user()->role==1)
                @if(!empty($items) && count($items))
                    @foreach($items as $item)
                        {{--                    @dd($item['all_orders'])--}}
                        @if ((int) $item['item']->admin == 0 && (int) $item['item']->type==1)
                            @foreach($item['all_orders'] as $order)
                                <tr class="item-row">
                                    <td>{{ $order->id }}</td>
                                    <td><span
                                            class="d-none">{{ $order->created_at->format('Ymd') }}</span>{{ $order->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $item['item']->name }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{!! $order->statusHtml !!}</td>
                                </tr>
                            @endforeach

                        @endif
                    @endforeach
                @endif

            @endif
            </tbody>
        </table>
    </div>
@endsection
@push('css')
    <style>
        .dataTables_filter {
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endpush
@push('js')
    @js(aApp('datatables/datatables.js'))
    <script>
        $(document).ready(function () {
            // DataTable initialisation
            $('.my-datatable').DataTable(
                {
                    "paging": false,
                    "autoWidth": true,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        nb_cols = api.columns().nodes().length;
                        var j = 3;
                        while (j < nb_cols) {
                            var pageTotal = api
                                .column(j, {page: 'current'})
                                .data()
                                .reduce(function (a, b) {
                                    return Number(a) + Number(b);
                                }, 0);
                            // Update footer
                            $(api.column(j).footer()).html(pageTotal);
                            j++;
                        }
                    }
                }
            );
        });
        $(function () {
            var Accordion = function (el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;

                // Variables privadas
                var links = this.el.find('.link');
                // Evento
                links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
            }

            Accordion.prototype.dropdown = function (e) {
                var $el = e.data.el;
                $this = $(this),
                    $next = $this.next();

                $next.slideToggle();
                $this.parent().toggleClass('open');

                if (!e.data.multiple) {
                    $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
                }
                ;
            }

            var accordion = new Accordion($('.accordion'), false);
        });

    </script>
@endpush
@push('css')
    <style>
        ul li {
            text-decoration: none;
            list-style: none;
        }

        #accordion {
            padding: 0;

        }

        .accordion {
            width: 100%;
            margin: 30px auto 20px;
            background: #FFF;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        .accordion .link {
            cursor: pointer;
            display: block;
            padding: 15px 15px 15px 42px;
            color: #4D4D4D;
            font-size: 14px;
            font-weight: 700;
            border-bottom: 1px solid #CCC;
            position: relative;
            -webkit-transition: all 0.4s ease;
            -o-transition: all 0.4s ease;
            transition: all 0.4s ease;
        }

        .accordion li:last-child .link {
            border-bottom: 0;
        }

        .accordion li i {
            position: absolute;
            top: 16px;
            left: 12px;
            font-size: 18px;
            color: #595959;
            -webkit-transition: all 0.4s ease;
            -o-transition: all 0.4s ease;
            transition: all 0.4s ease;
        }

        .accordion li i.fa-chevron-down {
            right: 12px;
            left: auto;
            font-size: 16px;
        }

        .accordion li.open .link {
            color: white;
        }

        .accordion li.open {
            background: grey;
        }

        .accordion li.open i {
            color: white;
        }

        .accordion li.open i.fa-chevron-down {
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        /**
         * Submenu
         -----------------------------*/


        .submenu {
            display: none;
            font-size: 14px;
        }

        .submenu li {
            border-bottom: 1px solid #4b4a5e;
        }

        .submenu a {
            display: block;
            text-decoration: none;
            color: #d9d9d9;
            padding: 5px 10px;
            -webkit-transition: all 0.25s ease;
            -o-transition: all 0.25s ease;
            transition: all 0.25s ease;
        }

        .submenu a:hover {
            background: #b63b4d;
            color: #FFF;
        }
    </style>
@endpush
