<div class="form-group">
    <div class="bylang-header">
        <div class="bylang-title has-title">{{ $label??null }}</div>
    </div>
    <div class="little-p">
        <input type="text" name="{{ $key }}[{!! $count !!}][{{ $as }}]" class="form-control"
               placeholder="{{ $label??null }}" value="{{ $value }}">
    </div>
</div>
