@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.support.edit', ['id'=>$item->id]):route('admin.support.add') !!}" method="post"
          enctype="multipart/form-data">
        @csrf @method($edit?'patch':'put')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Вопрос'])
                    <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Вопрос"
                           value="{{ old('title.'.$iso, tr($item, 'title', $iso)) }}">
                    @endbylang
                </div>
                @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('active', ($edit &&
                empty($item->active))?false:true)])@endlabelauty

            </div>

            <div class="col-12">
                <div class="card">
                    @bylang(['id'=>'form_content', 'tp_classes'=>'little-p', 'title'=>'Ответ'])
                    <textarea class="ckeditor"
                              name="answer[{!! $iso !!}]">{!! old('answer.'.$iso, tr($item, 'answer', $iso)) !!}</textarea>
                    @endbylang
                </div>
            </div>

        </div>

        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
@endsection
@push('js')
    @ckeditor
@endpush
