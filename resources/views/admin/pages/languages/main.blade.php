@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody class="table-sortable" data-action="{{ route('admin.languages.sort') }}">
                @foreach($languages as $index=>$language)
                    <tr class="lang-card" data-lang="{!! $language->id !!}" data-id="{!! $language->id !!}">
                        <td>{{ $language->title }}</td>
                        <td>{!! $settings['default_language']==$language->id?'основной':'вторичный' !!}</td>
                        <td>
                            <span data-toggle="modal" data-target="#languageEditModal"><a href="javascript:void(0)"
                                                                                          {!! tooltip('Редактировать') !!}  class="icon-btn edit"></a></span>
                            <a href="{{ route('admin.translations.main', ['locale'=>$language->iso]) }}"
                               {!! tooltip('Переводы') !!}  class="icon-btn translations"></a>
                        </td>
                    </tr>
                    @php
                        $langData[$language->id] = [
                            'iso'=>$language->iso,
                            'title'=>$language->title,
                            'default'=>$settings['default_language']==$language->id,
                            'active'=>$language->active,
                            'defaultInAdmin'=>$settings['admin_language']==$language->id,
                            'urlLanguage'=>$settings['url_language']==$language->id,
                        ];
                    @endphp
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @modal(['id'=>'languageEditModal', 'centered'=>true, 'form'=>['id'=>'lem_form', 'action'=>route('admin.languages.main')]])
    @slot('title')Редактирование языка  &Lt;<span id="lem-title"></span>&Gt;@endslot
    {{--region Content--}}
    @csrf
    @method('patch')
    <input type="hidden" name="language_id" id="lem-id">
    <p class="text-center font-14 p-b-5">Тип языка - <b id="lem-default">основной</b></p>
    @zselect(['name'=>'active', 'title'=>'Активность', 'id'=>'lem-active','options'=>[
    ['class'=>'z-unchecked', 'value'=>'-1', 'label'=>'Неактивно'],
    ['class'=>'z-indeterminate', 'value'=>'0', 'label'=>'Только на CMS'],
    ['class'=>'z-checked', 'value'=>'1', 'label'=>'Активно'],
    ]])@endzselect
    @labelauty(['title'=>'На CMS', 'label'=>'Неосновной|Основной', 'id'=>'lem-default-in-admin','name'=>'default_in_admin'])@endlabelauty
    @labelauty(['title'=>'URL Генератор', 'label'=>'Неосновной|Основной', 'id'=>'lem-url-language','name'=>'url_language'])@endlabelauty
    {{--endregion--}}
    @endmodal
@endsection
@push('css')
    {!! newCss(aApp('multicheck/multicheck.css')) !!}
@endpush
@push('js')
    <script>
        {!! printJson('var langData', $langData) !!}
        var languageEditModal = $('#languageEditModal'),
            lemTitle = $('#lem-title'),
            lemDefault = $('#lem-default'),
            lemActive = $('#lem-active'),
            lemId = $('#lem-id'),
            lemDefaultInAdmin = $('#lem-default-in-admin');
        lemUrlLanguage = $('#lem-url-language');
        languageEditModal.on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget),
                langCard = button.parents('tr.lang-card'),
                languageId = langCard.data('lang'),
                thisLangData = langData[languageId];
            lemTitle.html(thisLangData.title);
            lemDefault.html(thisLangData.default ? 'основной' : 'вторичный');
            lemId.val(languageId);
            lemActive.prop('disabled', thisLangData.default);
            lemActive.val(thisLangData.active).trigger('change');
            lemDefaultInAdmin.prop('disabled', thisLangData.defaultInAdmin);
            lemDefaultInAdmin.prop('checked', thisLangData.defaultInAdmin);
            lemUrlLanguage.prop('disabled', thisLangData.urlLanguage);
            lemUrlLanguage.prop('checked', thisLangData.urlLanguage);
        });
    </script>
@endpush
