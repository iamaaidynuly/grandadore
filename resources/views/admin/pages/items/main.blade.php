@extends('admin.layouts.app')
@section('title', 'Фильтры связанные с категорией '.$category->name)
@section('content')
    <div class="text-xl">
        <p>Фильтры связанные с категорией - <strong>{{$category->name}}</strong></p>

    </div>
    <ul id="accordion" class="flex flex-col items-center  container mx-auto w-full accordion">
        @foreach($filters as $filter)
            <li style="width: 100%;" class="border  border-cardBorder mt-3">
                <div class="link" style="display: flex;align-items: center; justify-content: space-around;">
                    {{$filter->name}}
                    <i class="fa fa-chevron-down"></i>
                </div>
                <ul class=" submenu bg-white " style="padding: 15px ; ">
                    @foreach($filter->criteria as $crit)
                        <li style="width: 100%;" class="border border-cardBorder mt-3 p-2 flex justify-between">
                        <span>
                                {{$crit->name}}
                        </span>
                            <span>
                                  ({{$crit->id}})
                        </span>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach

    </ul>





@endsection
@push('js')
    <script>

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

            var accordion = new Accordion($('#accordion'), false);
        });

    </script>
@endpush
@push('css')
    <style>
        #accordion {
            padding: 0;
        }

        ul li {
            list-style: none !important;
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

