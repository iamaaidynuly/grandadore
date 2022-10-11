<div class="form-group">
    <div class="bylang-header">
        <div class="bylang-title has-title">{{ $label??null }}</div>
    </div>
    <div class="little-p">
        @if (!empty($value))
            <div><a href="{{ asset('u/banners/'.$value) }}" download>Скачать</a></div>
        @endif
        <div class="c-body">
            @file(['name'=>$key.'['.$count.']['.$as.']', 'title'=>'Выбрать файл...'])@endfile
        </div>
    </div>
</div>
