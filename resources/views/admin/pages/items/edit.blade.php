@extends('admin.layouts.app', ['contentWrapper' => 'w-75 m-auto'])
@section('title', 'Редактирование товаров')
@section('content')
    <form action="{{route('admin.items.edit.save',['id'=>$item->id])}}" method="post" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название', 'required'=>true])
                    <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Название"
                           value="{{ old('title.'.$iso, tr($item, 'title', $iso)) }}">
                    @endbylang
                </div>
                <div class="card p-2">
                    <label for="code">Артикул <sup style="color:red">*</sup> </label>
                    <input type="text" id="code" name="code" class="form-control" value="{{$item->code}}"
                           placeholder="Код">
                </div>
                <div class="card px-3">
                    <div class="row cstm-input">
                        <div class="col-12 p-b-5">
                            <input type="hidden" class="labelauty-reverse toggle-bottom-input on-unchecked"
                                   name="generate_url" value="1"
                                   data-labelauty="Вставить ссылку вручную">
                            <div class="bottom-input">
                                <input type="hidden" style="margin-top:3px;" name="url" class="form-control"
                                       id="form_url" placeholder="Ссылка" value="">
                            </div>
                        </div>
                    </div>
                    @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно','checked'=>
                    ($item->active)?true:false])@endlabelauty
                    @labelauty(['id'=>'new', 'label'=>'Не Новинки|Новинки','checked'=>
                    ($item->new)?true:false])@endlabelauty
                    @labelauty(['id'=>'top', 'label'=>'Не Топ|Топ','checked'=>
                    ($item->top)?true:false])@endlabelauty
                    @labelauty(['id'=>'sale', 'label'=>'Не Скидка|Скидка','checked'=>
                    ($item->sale)?true:false])@endlabelauty
{{--                    <div class="statuss d-flex flex-column">--}}
{{--                        <a class="status status-style {{ in_array(1,$arrayStatus) ? 'status-style-active' : "" }}" data-id="{{ $item->id }}"  data-attr="1">Новинки</a>--}}
{{--                        <a class="status status-style {{ in_array(2,$arrayStatus) ? 'status-style-active' : "" }}" data-id="{{ $item->id }}" data-attr="2">Топ</a>--}}
{{--                        <a class="status status-style {{ in_array(3,$arrayStatus) ? 'status-style-active' : "" }}" data-id="{{ $item->id }}" data-attr="3">Скидка</a>--}}
{{--                    </div>--}}
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="count">Количество на складе <sup style="color:red">*</sup> </label>
                                <input type="text" id="count" name="count" class="form-control" value="{{$item->count}}"
                                       placeholder="Количество">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="price">Цена</label>
                                <input type="number" min="0" class="form-control" id="price" name="price"
                                       value="{{$item->price}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="delivery_price">Скидка в процентах (1-100) %</label>
                                <input type="number" min="1" max="100" id="delivery_price" class="form-control"
                                       name="delivery_price" value="{{$item->delivery_price}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @if(!empty($categories))
                <div class="col-12 col-md-6">
                    <div class="card p-2">
                        <label for="types_ids">Связать с категорией <sup style="color:red">*</sup></label>
                        <select id="demo-3b" name="categories" id="types_ids">
                        @foreach($categories as $categ)
                                <optgroup label="{{$categ->name}}">
                                @foreach($categ->childrens as $cat)
                                    @if(count($cat->children)==0)
                                            <option
                                                value="{{$cat->id}}" {{ (in_array($cat->id,$items_category))?'selected':'null'}} > {{$cat->name}} </option>
                                        @else
                                            <option value="" disabled data-level="1"> {{$cat->name}}  </option>
                                        @foreach($cat->childrens as $c)
                                                <option value="{{$c->id}}"
                                                        data-level="2" {{ (in_array($c->id,$items_category))?'selected':'null'}}> {{$c->name}}  </option>
                                            @endforeach

                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        @if(!empty($brands))
                <div class="col-12 col-md-6">
                    <div class="card p-2">
                        <label for="brands">Связать с брендом</label>
                        <select class="js-example-basic-multiple" name="brands" id="brands">
                            @foreach($brands as $brand)
                                <option
                                    value="{{ $brand->id }}" {{ isset($item_brands) && $brand->id == $item_brands->brand_id ? 'selected' : '' }}>{{$brand->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    @bylang(['id'=>'form_content', 'tp_classes'=>'little-p', 'title'=>'Описание'])
                    <textarea class="ckeditor"
                              name="description[{!! $iso !!}]">{!! old('description.'.$iso, tr($item, 'description', $iso)) !!}</textarea>
                    @endbylang
                </div>
            </div>
            <div class="col-sm-12 mb-5">
                <div class="card p-2 card-underline" style="margin:0;">
                    <div class="char-add-row" style="font-size: 22px; float: left;">
                        <span>Размеры</span>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>
                                Название
                            </td>
                            <td>
                                Цена
                                <input type="checkbox" id="priceNotChange" value="">
                                <sup>(цена не меняется)</sup>
                            </td>
                            <td>
                                Удалить
                            </td>
                        </tr>
                        </thead>
                        <tbody class="sizes-container">
                        @if(!empty($sizes))
                            @foreach($sizes as $i => $infoSizes)
                                <tr class="added-criterion-row">
                                    <td>
                                        <input placeholder="Введите название"
                                               class="form-control characteristics-inputs"
                                               name="sizes[new][{{ $i }}][name]"
                                               type="text"
                                               value="{{ $infoSizes['name'] }}">
                                    </td>
                                    <td>
                                        <input placeholder="Введите Значение"
                                               class="form-control characteristics-inputs prices"
                                               name="sizes[new][{{ $i }}][price]"
                                               type="text"
                                               value="{{ $infoSizes['price'] }}">
                                        <input type="hidden" name="sizes[new][{{ $i }}][id]" value="{{ $infoSizes['id'] }}">
                                    </td>
                                    <td class="text-center">
                                        <a class="icon-btn delete delete-sizes-item"></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        @endif
                        </tbody>
                    </table>
                    <i class="icon-btn add char-add-row" onclick="addSizesRow()"></i>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card p-2 card-underline" style="margin:0;">
                    <div class="char-add-row" style="font-size: 22px; float: left;">
                        <span>Характеристики</span>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>
                                Название
                            </td>
                            <td>
                                Значение
                            </td>
                            <td>
                                Удалить
                            </td>
                        </tr>
                        </thead>
                        <tbody class="criteria-container">
                        @if(!empty($options))
                            @foreach($options as $i => $criterion)
                                <tr class="added-criterion-row">
                                    <td>
                                        <input placeholder="Введите название"
                                               class="form-control characteristics-inputs"
                                               name="criterion[new][{{ $i }}][name]"
                                               type="text"
                                               value="{{ $criterion['name'] }}">
                                    </td>
                                    <td>
                                        <input placeholder="Введите Значение"
                                               class="form-control characteristics-inputs"
                                               name="criterion[new][{{ $i }}][value]"
                                               type="text"
                                               value="{{ $criterion['value'] }}">
                                    </td>

                                    <td class="text-center">
                                        <a class="icon-btn delete delete-criterion-item"></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <input placeholder="Введите название"
                                           class="form-control characteristics-inputs"
                                           name="criterion[new][0][name]"
                                           type="text"
                                           value="">
                                </td>
                                <td>
                                    <input placeholder="Введите Значение"
                                           class="form-control characteristics-inputs"
                                           name="criterion[new][0][value]"
                                           type="text"
                                           value="">
                                </td>
                                <td class="text-center">
                                    <a class="icon-btn delete delete-criterion-item"></a>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <i class="icon-btn add char-add-row" onclick="addCriterionRow()"></i>
                </div><!--end .card -->
            </div>
            <div class="col-12 col-lg-6 mt-3">
                @seo(['item'=>$item])@endseo
            </div>
            <div class="col-12 col-lg-6 mt-3">
                <div class="card">
                    <div class="c-title">Изображение (800 x 800)</div>
                    @if(!empty($item->image))
                        <div class="p-2 text-center">
                            <img src="{{ asset('u/items/thumbs/'.$item->image) }}" alt="" class="img-responsive">
                        </div>
                    @endif
                    <div class="c-body">
                        @file(['name'=>'image'])@endfile
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>

        function change(id,status) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.items.edit.status') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id' : id ,
                    status:status,

                },
                success: function (data) {
                    return data ;
                }
            });
        }

        $('body').on('click','.status-style', function (){
            if($(this).hasClass('status-style-active')){
                $(this).removeClass('status-style-active')
            }
            else {
                $(this).addClass('status-style-active');
            }
        })


        $('body').on('click','.status', function (){
            var array=[];
            $('.status').each(function (index,val){
                if($(this).hasClass('status-style-active')){
                array.push($(val).attr('data-attr'))
            }})
            change($(this).attr('data-id'),array)
        })


    </script>


@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    @js(aApp('smartSelect/smartSelect.min.js'))
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
            $("select#demo-3b").smartselect({
                toolbar: false,
                defaultView: 'root+selected',
                multiple: false
            });
        });
        var charContainer = $('.criteria-container');
        var charSizes = $('.sizes-container');
        function charRow(index) {
            return '<tr class="added-criterion-row">' +
                '<td>' +
                '<input placeholder="Введите название" class="form-control characteristics-inputs" name="criterion[new][' + index + '][name]" type="text" value="">' +
                '</td>' +
                '<td>' +
                '<input placeholder="Введите Значение" class="form-control characteristics-inputs" name="criterion[new][' + index + '][value]" type="text" value="">' +
                '</td>' +

                '<td class="text-center">' +
                '<a class="icon-btn delete delete-criterion-item"></a>' +
                '</td>' +
                '</tr>';
        }

        $(document).on('click', '.delete-criterion-item', function () {
            $(this).parents('tr').remove();
        });
        function charSizeRow(index) {
            return '<tr class="added-criterion-row">' +
                '<td>' +
                '<input placeholder="Введите название" class="form-control characteristics-inputs " name="sizes[new][' + index + '][name]" type="text" value="">' +
                '</td>' +
                '<td>' +
                '<input placeholder="Введите Значение" class="form-control characteristics-inputs prices" name="sizes[new][' + index + '][price]" type="text" value="">' +
                '</td>' +

                '<td class="text-center">' +
                '<a class="icon-btn delete delete-criterion-item"></a>' +
                '</td>' +
                '</tr>';
        }
        $(document).on('click', '.delete-sizes-item', function () {
            $(this).parents('tr').remove();
        });

        function addCriterionRow() {
            var index = charContainer.find('tr').length;
            charContainer.append(charRow(index));
        }
        function addSizesRow() {
            var index = charSizes.find('tr').length;
            charSizes.append(charSizeRow(index));
        }
        $('#priceNotChange').change(function(){
            if($('#priceNotChange').is(":checked"))
        {
            x=$('#price').val();
            $('.prices').val(x);
        }})
    </script>
    @ckeditor
@endpush


<style>
    .status-style {
        width: max-content;
        font-size: 16px;
        color: #000;
        transform: 0.4s all;
        -webkit-transform: 0.4s all;
        -moz-transform: 0.4s all;
        cursor: pointer;
    }

    .status-style:hover {
        font-weight: 700;
    }

    .status-style-active {
        font-weight: 700;
    }
</style>


@css(aApp('select2/select2.css'))
@css(aApp('smartSelect/smartSelect.min.css'))

