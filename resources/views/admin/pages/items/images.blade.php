@extends('admin.layouts.app')
@section('title', 'Импортирование изображенией')
@section('content')
    <form id="form" action="{{route('admin.items.import.images')}}" method="post" enctype="multipart/form-data">@csrf
        @if(!empty($errors) )
            @foreach($errors->messages() as $message)
                <p class="text-red">{{$message[0]}}</p>
            @endforeach
        @endif
        <div class="row mt-3">
            <div class="col-12 col-lg-6">
                <div class="card">
                    {{--                <div class="c-title">Файл Excel</div>--}}
                    <div class="c-body">
                        @if(!empty($errors) && $errors->has('zip'))
                            <p class="text-red">Выберите файл <strong>(zip)</strong></p>
                        @endif
                        <div class="mt-4">
                            @file(['name'=>'file', 'title'=>'Выберите zip Файл...'])@endfile

                        </div>
                        <div class="pt-2 text-right">
                            <button type="submit" id="form-submit" class="btn btn-dark">
                                Импортировать
                            </button>
                        </div>
                    </div>
                </div>
                <div id="show-on-submit" class="font-weight-bold font-16" style="display: none">
                    <div class="text-danger">Подождите, идет импортирование...</div>
                    <div id="stopwatch">00:00</div>
                </div>
                @if(!empty(old('count')))
                    @if(old('changed_count') || old('changed_count')==0 )
                        <p class="text-greens">Успешно: <strong class="text-black">{{ old('changed_count')}}</strong>
                        </p>
                        <p class="text-red">Не Успешно: <strong
                                class="text-black"> {{old('count')- old('changed_count')}}</strong></p>
                    @endif
                @endif
            </div>
        </div>
    </form>








@endsection
@push('js')
    <script>
        $('#form').on('submit', function (e) {
            $('#show-on-submit').show();
            $('#form-submit').attr('disabled', 'disabled');
            var stopwatch = $('#stopwatch'),
                seconds = 0,
                minutes = 0;
            setInterval(function () {
                if (seconds >= 59) {
                    seconds = 0;
                    ++minutes;
                } else ++seconds;
                var thisSeconds = seconds > 9 ? seconds : '0' + seconds.toString(),
                    thisMinutes = minutes > 9 ? minutes : '0' + minutes.toString();
                stopwatch.html(thisMinutes + ':' + thisSeconds);
            }, 1000);
        });

    </script>
@endpush
@push('css')
    <style>
        .first_a {
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

        .accordion1 li:last-child .link1 {
            border-bottom: 0;
        }

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

        .accordion1 li.open .link1 {
            color: white;
        }

        .accordion1 li.open {
            background: red;
        }

        .accordion1 li.open i {
            color: white !important;
        }

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

        .submenu1 li {
            border-bottom: 1px solid #4b4a5e;
        }

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
            background: red;
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

