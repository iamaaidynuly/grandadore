@extends('admin.layouts.app')
@section('content')
    @if(count($items))
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Слова</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="item-row">
                            <td class="item-title">{{ Str::title($item['filename']) }}</td>
                            <td class="item-title">{{ $item['words_count'] }}</td>
                            <td>
                                <a href="{{ route('admin.translations.edit', ['locale'=>$locale, 'filename'=>$item['filename']]) }}"
                                   {!! tooltip('Редактировать') !!} class="icon-btn edit"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h4 class="text-danger">@lang('admin/all.empty')</h4>
    @endif
@endsection
