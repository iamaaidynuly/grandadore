@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.main_slider.edit', ['id'=>$item->id]):route('admin.main_slider.add') !!}"
          method="post" enctype="multipart/form-data">
        @csrf @method($edit?'patch':'put')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-3">
                <div class="card px-3 pt-3">
                    @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('active', ($edit &&
                    empty($item->active))?false:true)])@endlabelauty
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="c-title">Изображение (1920 x 688)</div>
                    @if (!empty($item->image))
                        <div class="p-2 text-center">
                            <img src="{{ asset('u/main_slider/'.$item->image) }}" alt="" class="img-responsive">
                        </div>
                    @endif
                    <div class="c-body">
                        @file(['name'=>'image'])@endfile
                    </div>
                </div>
                <div class="card">
                    <label for="url"> Введите Ссылку</label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="Ссылка" value="   @if (!empty($item->image)){{ ($item)?$item->url:old('url') }}@endif">
                </div>
            </div>
            <input type="hidden" name="slider_type" value="1">
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </div>
    </form>


@endsection
@push('js')
    @include('ckfinder::setup')
@endpush
