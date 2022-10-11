<div class="form-group">
    @if($label)
        <div class="bylang-header">
            <div class="bylang-title has-title">{{ $label }}</div>
        </div>
    @endif
    <div class="little-p">
        <textarea class="ckeditor" name="{{ $key }}[{!! $count !!}][{{ $as }}]">{!! $value !!}</textarea>
    </div>
</div>
