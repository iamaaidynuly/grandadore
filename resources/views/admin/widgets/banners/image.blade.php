<div class="form-group">
    <div class="bylang-header">
        <div class="bylang-title has-title">{{ $label??null }}</div>
    </div>
    <div class="little-p">
        @if (!empty($value))
            <div class="py-2 text-{{ $settings['preview_position']??'center' }}">
                <img src="{{ asset('u/banners/'.$value) }}" alt="" class="{{ $settings['preview_classes']??null }}"
                     style="max-width:80%;width:auto;height:auto;">
            </div>
        @endif
        <div class="c-body">
            @file(['name'=>$key.'['.$count.']['.$as.']'])@endfile
        </div>
    </div>
</div>
