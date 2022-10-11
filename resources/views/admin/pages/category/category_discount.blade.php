@extends('admin.layouts.app')
@section('title', 'Редактирование скидки для пользователя')
@section('content')
    <div class="col-12">
        <div class="card p-3">
            <form method="post" action="{{route('admin.add.categories.discount',['id'=>$category_id])}}"
                  class="d-flex flex-column">
                @csrf
                <label for="discount">Связать скидку с категорией</label>

                <select class="js-example-basic-multiple" name="discount" id="discount">
                    @foreach($discounts as $discount)
                        <option value="">Без скидки</option>
                        @if(!empty($category_discount) && !empty($category_discount->individual_discount) && $loop->first)
                            <option value="{{$category_discount->individual_discount}}" selected="selected">
                                Индивидуальная скидка {{$category_discount->individual_discount.'%' }} </option>
                        @else
                            <option
                                value="{{ json_encode([ 'discount_id'=>$discount->id,'discount'=> (int) $discount->discount])}}" {{ (!empty($category_discount) && $discount->id==$category_discount->discount_id)?'selected':'null'}}> {{$discount->title}} </option>
                        @endif
                    @endforeach
                </select>
                <div class="card-actionbar mt-3">
                    <button type="submit" class="btn ink-reaction btn-raised btn-primary">Сохранить</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                tags: true
            });
        });
    </script>
@endpush

@css(aApp('select2/select2.css'))
