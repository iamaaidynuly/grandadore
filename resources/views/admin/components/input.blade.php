<div class="form-group row cstm-input">

    @if(!empty($label))
        <label for="{{ safe($id) }}" class="col-12 control-label col-form-label">{!! $label !!} </label>
    @endif
    <div class="col-12">
        <input type="{{ safe($type, 'text') }}" name="{{ safe($name, safe($id)) }}" class="form-control"
               {!! exists('id="',$id,'"') !!} placeholder="{{ safe($placeholder, safe($label, 'Название')) }}"
               value="{{ safe($value) }}">
    </div>
</div>
