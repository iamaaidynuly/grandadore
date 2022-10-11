<div class="form-group">
    @bylang(['tp_classes'=>'little-p', 'title'=>$label??null, 'id'=>$key.'_'.$count.'_'.$as])
    <textarea class="ckeditor"
              name="{{ $key }}[{!! $count !!}][{{ $as }}][{!! $iso !!}]">{!! $value[$iso]??null !!}</textarea>
    @endbylang
</div>
