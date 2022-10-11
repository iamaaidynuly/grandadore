<div class="form-group {!! empty($class)?null:$class !!}">
    @if(!empty($title))
        <div class="col-form-label d-inline-block align-middle" style="font-weight:600">{!! $title !!}</div>
    @endif
    <div class="d-inline-block @if(!empty($title)) p-l-5 @endif align-middle">
        <input class="labelauty" {!! exists('id="',$id,'"') !!} type="checkbox"
               name="@safe($name, safe($id, 'labelauty'))" value="@safe($value, '1')"
               data-labelauty="@safe($label)"{!! empty($checked)?null:' checked' !!}>
    </div>
</div>
