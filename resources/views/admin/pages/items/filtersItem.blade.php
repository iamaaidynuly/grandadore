@extends('admin.layouts.app')
@section('title', 'Связать критерии к данному товару.')
@section('content')
    <form method="post" action="{{route('admin.items.criterion.submit',['item_id'=>$item_id])}}"
          class="d-flex flex-column">
        @csrf
        @if(!empty($item_criterions) && count($item_criterions))
            <div class="col-12">
                <div class="card">
                    <label for="item_criterions">Связать с критерией</label>
                    <select class="js-example-basic-multiple" name="item_criterions[]" id="item_criterions"
                            multiple="multiple">
                        @foreach($item_criterions as $filter)
                            <optgroup label="{{$filter->name}}">
                                @foreach($filter->criteria as $criterion)
                                    <option
                                        value="{{$criterion->id}}" {{ (in_array($criterion->id,$item_criterions_array))?'selected':'null'}}>{{$criterion->name}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="col-12">
            <div class="card">
                <label for="color_filters">Связать с цветовым фильтром</label>
                <select class="js-example-basic-multiple" name="color_filters[]" id="color_filters"
                        multiple="multiple">
                    @foreach($colorFilters as $colorFilter)
                        <option
                            value="{{$colorFilter->id}}" {{ (in_array($colorFilter->id, $selectedColorFilters)) ? 'selected' : 'null'}}>{{$colorFilter->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-actionbar mt-3">
            <button type="submit" class="btn ink-reaction btn-raised btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush

@css(aApp('select2/select2.css'))
