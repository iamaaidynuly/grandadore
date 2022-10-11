@extends('admin.layouts.app')
@section('title', 'Импортирование товаров')
@section('content')

    <div class="card p-2">
        <form id="form" action="{!! url()->current() !!}" method="post" enctype="multipart/form-data">@csrf
            <div>Требуемые столбцы:
                @foreach($columns as $column)
                    '<b>{{ $column }}</b>',
                @endforeach
            </div>
            <div class="w-100 mt-2">
                <a href="{{ route('admin.items.import.downloadExample') }}" class="btn btn-sm btn-default">Скачать образец</a>
            </div>
            @if ($response)
                @if($response=='unvalidated')
                    <div class="alert alert-danger text-red text-2xl" role="alert"> Выберите Excel файл.</div>
                @elseif($response=='failed')
                    <div class="alert alert-danger" role="alert">Импортирование не произашло. Причина: неправильный формат файла.</div>
                @else
                    @php $multiple_sheets = count($response) > 1 @endphp
                    @foreach($response as $sheet)
                        @if($sheet['status'])
                            <div class="alert alert-info  my-2" role="alert">{{ $multiple_sheets?'Лист '.$loop->iteration.': ':null }} <span class="text-greens">Успешные элементы -</span> {{ $sheet['imported'] }}, <span class="text-red ">Неуспешные елементы -</span> {{ $sheet['failed'] }}.</div>
                            @if($sheet['failed']>0)
                                <div class="alert alert-danger text-red text-2xl" role="alert">
                                    <p>Ошыбки.</p>
                                    @foreach($sheet['errors'] as $error)
                                        <p class="text-red text-2xl">Линия {{ $error['row'] }}. {{ $error['reason'] }}</p>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="alert alert-danger" role="alert">Импортирование не произашло. Причина: неправильный формат листа.</div>
                        @endif
                        @break
                    @endforeach
                @endif
            @endif
            <div class="mt-4">
                @file(['name'=>'file', 'title'=>'Выберите Excel Файл...'])@endfile

            </div>
            <div class="mt-2">
                <button type="submit">Импортировать</button>
            </div>
        </form>

    </div>

    <p style="text-align: center; font-size: 30px; font-weight: bold;">Категории и фильтры</p>

<ul id="accordion" class="flex flex-col items-center  container mx-auto w-full accordion" >

@foreach($categories as $categ)
        <li style="width: 100%;"  class="border  border-cardBorder mt-3">
            <div class="link" style="display: flex;align-items: center; justify-content: space-around;">
                @if(!count($categ->childrens) && $categ->deep)
                (id:{{$categ->id}})
                @endif
                {{$categ->name}}
                @if(!empty($categ->filters) && count($categ->filters))
                            <a target="_blank" href="{{route('admin.items.filterAndCategory.view',['id'=>$categ->id])}}" class="text-headerBg first_a ">(Посмотреть фильтры связанные с этой категорией)</a>
                 @endif


                <i class="fa fa-chevron-down" style="{{(count($categ->filters)?'top:23px':null)}}"></i>
            </div>
            <ul class=" submenu bg-white " style="padding: 15px ; ">

                <ul id="accordion1" class="flex flex-col items-center  container mx-auto w-full accordion1" >
                    @foreach($categ->childrens as $cat)
                        <li style="width: 100%;"  class="border border-cardBorder mt-3">
                            <div class="link1" style="display: flex;align-items: center; justify-content: space-around;">
                                @if(!count($cat->childrens))
                                (id:{{$cat->id}})
                                @endif
                                {{$cat->name}}
                                @if(count($cat->filters))
                                    <a target="_blank" href="{{route('admin.items.filterAndCategory.view',['id'=>$cat->id])}}" class="text-headerBg ">(Посмотрет фильтры связанные с этой категорией)</a>
                                @endif
                                <i class="fa fa-chevron-down" style="{{(count($cat->filters)?'top:23px':null)}}"></i>
                            </div>
                            <ul class=" submenu1 bg-white " style="padding: 15px ; ">
                                <div class=" my-2">

                                        <div class=" my-2 flex flex-col">
                                            @foreach($cat->childrens as $c)
                                                <div class="border border-cardBorder mt-3" style="display: flex;align-items: center; justify-content: space-around;padding: 15px 15px 15px 42px;">
                                                    (id:{{$c->id}})
                                                    {{$c->name}}
                                                    @if(count($c->filters))
                                                        <a target="_blank" href="{{route('admin.items.filterAndCategory.view',['id'=>$c->id])}}" class="text-headerBg ">(Посмотрет фильтры связанные с этой категорией)</a>
                                                    @endif
                                                </div>

                                            @endforeach
                                        </div>

                                </div>
                            </ul>
                        </li>

                    @endforeach

                </ul>

            </ul>
        </li>
    @endforeach
</ul>
    <p style="text-align: center; font-size: 30px; font-weight: bold;">бренд</p>

    <ul id="accordion" class="flex flex-col items-center  container mx-auto w-full accordion" >
    @foreach($brands as $brand)
        <li style="width: 100%;"  class="border  border-cardBorder mt-3">
            <div class="link" style="display: flex;align-items: center; justify-content: space-around;">
                    (id:{{$brand->id}})
                {{$brand->title}}
            </div>
        </li>
    @endforeach
</ul>
    <p style="text-align: center; font-size: 30px; font-weight: bold;">Color filter </p>
    <ul id="accordion" class="flex flex-col items-center  container mx-auto w-full accordion" >
        @foreach($colors as $color)
            <li style="width: 100%;"  class="border  border-cardBorder mt-3">
                <div class="link" style="display: flex;align-items: center; justify-content: space-around;">
                    (id:{{$color->id}})
                    {{$color->name}}
                </div>
            </li>
        @endforeach
    </ul>



@endsection
@push('js')
    <script>
        $('#form').on('submit', function(e){
            $('#show-on-submit').show();
            $('#form-submit').attr('disabled', 'disabled');
            var stopwatch = $('#stopwatch'),
                seconds = 0,
                minutes = 0;
            setInterval(function(){
                if (seconds>=59) {
                    seconds = 0;
                    ++minutes;
                } else ++seconds;
                var thisSeconds = seconds>9?seconds:'0'+seconds.toString(),
                    thisMinutes = minutes>9?minutes:'0'+minutes.toString();
                stopwatch.html(thisMinutes+':'+thisSeconds);
            }, 1000);
        });
        $(function() {
            var Accordion = function(el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;

                // Variables privadas
                var links = this.el.find('.link');
                // Evento
                links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
            }

            Accordion.prototype.dropdown = function(e) {
                var $el = e.data.el;
                $this = $(this),
                    $next = $this.next();

                $next.slideToggle();
                $this.parent().toggleClass('open');

                if (!e.data.multiple) {
                    $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
                };
            }

            var accordion = new Accordion($('#accordion'), false);
        });
        $(function() {
            var Accordion1 = function(el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;

                // Variables privadas
                var links = this.el.find('.link1');
                // Evento
                links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
            }

            Accordion1.prototype.dropdown = function(e) {

                var $el = e.data.el;
                $this = $(this),
                    $next = $this.next();

                $next.slideToggle();
                $this.parent().toggleClass('open');

                if (!e.data.multiple) {
                    $el.find('.submenu1').not($next).slideUp().parent().removeClass('open');
                };
            }

            var Accordion1 = new Accordion1($('.accordion1'), false);
        });
    </script>
@endpush
@push('css')
    <style>
        #accordion{
            padding: 0;
        }
        ul li{
            list-style: none !important;
        }
.first_a{
    display: block;
    text-decoration: none;
    color: #d9d9d9;
    padding: 5px 10px;
    -webkit-transition: all 0.25s ease;
    -o-transition: all 0.25s ease;
    transition: all 0.25s ease;
}
.first_a:hover {
    background: #b63b4d;
    color: #FFF;
}

        .accordion1 {
            width: 100%;
            margin: 30px auto 20px;
            background: #FFF;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

         .link1 {
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

        .accordion1 li:last-child .link1 { border-bottom: 0; }

        .accordion1 li i {
            transform: rotate(0deg) !important;
            position: absolute;
            top: 16px;
            left: 12px;
            font-size: 18px;
            color: #595959 !important;
            -webkit-transition: all 0.4s ease;
            -o-transition: all 0.4s ease;
            transition: all 0.4s ease;
        }

        .accordion1 li i.fa-chevron-down {
            right: 12px;
            left: auto;
            font-size: 16px;
        }

        .accordion1 li.open .link1 { color: white; }
        .accordion1 li.open  { background: grey; }

        .accordion1 li.open i { color: white !important; }

        .accordion1 li.open i.fa-chevron-down {
            -webkit-transform: rotate(180deg) !important;
            -ms-transform: rotate(180deg) !important;
            -o-transform: rotate(180deg) !important;
            transform: rotate(180deg) !important;
        }

        /**
         * submenu1
         -----------------------------*/


        .submenu1 {
            display: none;
            font-size: 14px;
        }

        .submenu1 li { border-bottom: 1px solid #4b4a5e; }

        .submenu1 a {
            display: block;
            text-decoration: none;
            color: #d9d9d9;
            padding: 12px;
            padding-left: 42px;
            -webkit-transition: all 0.25s ease;
            -o-transition: all 0.25s ease;
            transition: all 0.25s ease;
        }

        .submenu1 a:hover {
            background: #b63b4d;
            color: #FFF;
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

        .accordion li:last-child .link { border-bottom: 0; }

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

        .accordion li.open .link { color: white; }
        .accordion li.open  { background: grey; }

        .accordion li.open i { color: white; }

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

        .submenu li { border-bottom: 1px solid #4b4a5e; }

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

