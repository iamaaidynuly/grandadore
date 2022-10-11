@extends('admin.layouts.app')
@section('title', 'Редактирование категории')
@section('content')
    <div class="col-12">
        <div class="card p-3">
            <form method="post" action="{{route('admin.filters.filterCategory' ,['id'=>$id])}}"
                  class="d-flex flex-column">
                @csrf
                <label for="filters_ids">Прикрепить фильтр к данному разделу.</label>
                <select class="js-example-basic-multiple" name="filters_ids[]" id="filters_ids" multiple="multiple">
                    @foreach($filters as $filter)
                        <option
                            value="{{$filter->id}}" {{ (in_array($filter->id,$filterCategory))?'selected':'null'}}> {{$filter->name}} </option>
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
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush

@css(aApp('select2/select2.css'))
