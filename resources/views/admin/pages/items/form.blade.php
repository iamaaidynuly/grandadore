@extends('admin.layouts.app', ['contentWrapper' => 'w-75 m-auto'])
@section('title', 'Добавление товаров')
@section('content')
    <form action="{{route('admin.items.add.save')}}" method="post" enctype="multipart/form-data">
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
                    <label for="code">Артикул <sup style="color:red">*</sup></label>
                    <input type="text" id="code" name="code" class="form-control" value="{{old('code')}}"
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
                    @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно','checked'=> old('active', false)])@endlabelauty
                    @labelauty(['id'=>'new', 'label'=>'Не Новинки|Новинки','checked'=> old('new', false)])@endlabelauty
                    @labelauty(['id'=>'top', 'label'=>'Не Топ|Топ','checked'=> old('top', false)])@endlabelauty
                    @labelauty(['id'=>'sale', 'label'=>'Не Скидка|Скидка','checked'=> old('sale', false)])@endlabelauty

                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="count">Количество на складе <sup style="color:red">*</sup></label>
                                <input type="text" id="count" name="count" class="form-control" value="{{ old('count') }}"
                                       placeholder="Количество">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="price">Цена</label>
                                <input type="number" min="0" id="price" class="form-control" name="price"
                                       value="{{old('price')}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="py-2">
                                <label for="delivery_price">Скидка в процентах (1-100) %</label>
                                <input type="number" min="1" class="form-control" max="100" id="delivery_price"
                                       name="delivery_price" value="{{old('delivery_price')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($categories))
                <div class="col-12 col-md-6">
                    <div class="card p-2">
                        <label for="types_ids">Связать с категорией  <sup style="color:red">*</sup></label>
                        <select id="demo-3b" name="categories" id="types_ids">
                            @foreach($categories as $categ)
                                <optgroup label="{{$categ->name}}">
                                    @foreach($categ->childrens as $cat)

                                        @if(count($cat->children)==0)
                                            <option value="{{$cat->id}}"> {{$cat->name}} </option>
                                        @else
                                            <option value="" disabled data-level="1"> {{$cat->name}}  </option>
                                            @foreach($cat->childrens as $c)
                                                <option value="{{$c->id}}" data-level="2"> {{$c->name}}  </option>
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
                        <label for="brands">Бренд товара</label>
                        <select class="js-example-basic-multiple" name="brands" id="brands">
                            <option value="">Не выбрано</option>
                            @foreach($brands as $brand)
                                <option
                                    value="{{$brand->id}}"{{ old('brands') == $brand->id ? ' selected' : '' }}> {{$brand->title}}</option>
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
                        @if(old('sizes') && count(old('sizes')['new']) > 0)
                            @foreach(old('sizes')['new'] as $i => $sizes)
                                <tr class="added-criterion-row">
                                    <td>
                                        <input placeholder="Введите название"
                                               class="form-control characteristics-inputs"
                                               name="sizes[new][{{ $i }}][name]"
                                               type="text"
                                               value="{{ $sizes['name'] }}">
                                    </td>
                                    <td>
                                        <input placeholder="Введите Значение"
                                               class="form-control characteristics-inputs prices"
                                               name="sizes[new][{{ $i }}][price]"
                                               type="text"
                                               value="{{ $sizes['price'] }}">
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
                        @if(old('criterion') && count(old('criterion')['new']) > 0)
                            @foreach(old('criterion')['new'] as $i => $criterion)
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

                        @endif
                        </tbody>
                    </table>
                    <i class="icon-btn add char-add-row" onclick="addCriterionRow()"></i>
                </div>
            </div>
            <div class="col-12 col-lg-6 mt-3">
                @seo(['item'=>$item])@endseo
            </div>
            <div class="col-12 col-lg-6 mt-3">
                <div class="card">
                    <div class="c-title">Изображение (453 x 674)</div>
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
@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    @js(aApp('smartSelect/smartSelect.min.js'))


    <script>
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
        $(document).on('click', '.delete-criterion-item', function () {
            $(this).parents('tr').remove();
        });
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
@css(aApp('select2/select2.css'))
@css(aApp('smartSelect/smartSelect.min.css'))
