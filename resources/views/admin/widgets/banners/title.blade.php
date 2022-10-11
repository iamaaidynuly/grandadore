<div class="form-group">
    @bylang(['tp_classes'=>'little-p', 'title'=>$label??null, 'id'=>$key.'_'.$count.'_'.$as])
    <input type="text" name="{{ $key }}[{!! $count !!}][{{ $as }}][{!! $iso !!}]" class="form-control"
           placeholder="{{ $label??null }}" value="{{ $value[$iso]??null }}">
    @endbylang
</div>
