@extends('admin.layouts.app')
@section('content')
    <form
        action="{{ $edit?route('admin.video_gallery.edit', ['id'=>$item->id]):route('admin.video_gallery.add', ['gallery'=>$gallery, 'id'=>$key]) }}"
        method="post">
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
                @card(['title'=>'Видео'])
                <input type="text" name="video" class="form-control" placeholder="Ссылка/ID видео Youtube"
                       value="{{ old('video', $item->video??null) }}">
                @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'class'=>'mt-2 mb-0',
                'checked'=>oldCheck('active', ($edit && empty($item->active))?false:true)])@endlabelauty
                @endcard
            </div>
            @if($edit && $item->video)
                <div class="col-12 col-lg-6">
                    <iframe style="width:100%; height:300px;"
                            src="{{ url('https://www.youtube.com/embed/'.$item->video) }}" frameborder="0"
                            allow="autoplay;" allowfullscreen></iframe>
                </div>
            @endif
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </div>
    </form>
@endsection
