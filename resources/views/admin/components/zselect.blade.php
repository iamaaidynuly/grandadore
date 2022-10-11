<div class="form-group">
    @if(!empty($title))
        <div class="col-form-label d-inline-block align-middle" style="font-weight:600">{!! $title !!}</div>
    @endif
    <div class="d-inline-block @if(!empty($title)) p-l-5 @endif align-middle">
        <select name="@safe($name)" class="z-select" {!! exists('id="',$id,'"') !!}>
            @foreach ($options as $option)
                <option value="{!! $option['value']??null !!}"
                        data-class="@safe($option['class'])"{!! empty($option['checked'])?null:' checked' !!}>
                    @safe($option['label'])
                </option>
            @endforeach
        </select>
    </div>
</div>
