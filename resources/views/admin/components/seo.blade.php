<div class="card">
    @bylang(['id'=>'seo_component', 'title'=>'SEO'])
    <div class="form-group">
        <div class="bylang-header">
            <div class="bylang-title has-title">Seo Title</div>
        </div>
        <div class="little-p">
            <input type="text" name="seo_title[{!! $iso !!}]" class="form-control" placeholder="Название"
                   value="{{ old('seo_title.'.$iso, tr($item, 'seo_title', $iso)) }}">
        </div>
    </div>
    {{--                <div class="form-group">--}}
    {{--                    <div class="bylang-header">--}}
    {{--                        <div class="bylang-title has-title">Ключевые слова</div>--}}
    {{--                    </div>--}}
    {{--                    <div class="little-p">--}}
    {{--                        <input type="text" name="seo_keywords[{!! $iso !!}]" class="form-control" placeholder="Ключевые слова" value="{{ old('seo_keywords.'.$iso, tr($item, 'seo_keywords', $iso)) }}">--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    <div class="form-group">
        <div class="bylang-header">
            <div class="bylang-title has-title">Seo Description</div>
        </div>
        <div class="little-p">
                        <textarea name="seo_description[{!! $iso !!}]" class="form-control form-textarea"
                                  placeholder="Описание">{{ old('seo_description.'.$iso, tr($item, 'seo_description', $iso)) }}</textarea>
        </div>
    </div>
    @endbylang
</div>
