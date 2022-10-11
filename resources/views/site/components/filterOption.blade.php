{{--<div class="dropdown bootstrap-select w-100 uniqal" >
<select required class="selectpicker w-100" name="nasselioni_punkt" id="nasselioni_punkt">
    @foreach($deliverCites as $value)
    <option value="{{$value->id}}">{{$value->title}}</option>
    @endforeach
</select>
</div>--}}


<select required class="w-100" name="nasselioni_punkt" id="nasselioni_punkt">
@foreach($deliverCites as $value)
    <option value="{{$value->id}}">{{$value->title}}</option>
@endforeach
</select>
