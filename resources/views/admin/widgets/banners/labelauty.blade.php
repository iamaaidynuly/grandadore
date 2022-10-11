<div class="little-p">
    <input class="labelauty" id="{{ $key.'_'.$count.'_'.$as }}" type="checkbox"
           name="{{ $key.'['.$count.']['.$as.']' }}" value="1"
           data-labelauty="{{ $label??null }}"{!! empty($value)?null:' checked' !!}>
</div>
