<div class="form-group">
    @bylang(['tp_classes'=>'little-p', 'title'=>$label??null, 'id'=>$key.'_'.$count.'_'.$as])
    <textarea name="{{ $key }}[{!! $count !!}][{{ $as }}][{!! $iso !!}]" class="form-textarea form-control"
              placeholder="{{ $label??null }}">{{ $value[$iso]??null }}</textarea>
    @endbylang
</div>
