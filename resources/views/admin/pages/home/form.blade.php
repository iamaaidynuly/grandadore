@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.home.edit', ['id'=>$item->id]):route('admin.home.add') !!}" method="post"
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
                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                    <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Название"
                           value="{{ old('title.'.$iso, tr($item, 'title', $iso)) }}">
                    @endbylang
                </div>
                <div class="card p-2">
                    <label for="url">
                        Введите Ссылку
                    </label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="Ссылка"
                           value="{{ ($item)?$item->url:old('url') }}">
                </div>
                <div class="card px-3 pt-3">

                    @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('active', ($edit &&
                    empty($item->active))?false:true)])@endlabelauty
                </div>

            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Изображение (50 X 50)</div>
                    @if (!empty($item->image))
                        <div class="p-2 text-center">
                            <img src="{{ asset('u/home_info/'.$item->image) }}" alt="" class="img-responsive">
                        </div>
                    @endif
                    <div class="c-body">
                        @file(['name'=>'image'])@endfile
                    </div>
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
