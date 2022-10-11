@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.reviews.view', ['id'=>$item->id]):route('admin.news.add') !!}" method="post"
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
                <div class="card p-2">
                    <p>Отзыв</p>
                    <textarea readonly type="text" name="message" class="form-control" placeholder="Название"
                              value="">{{$item->message}}</textarea>
                </div>
                <div class="card p-2">
                    <p>Оценка</p>
                    <input readonly class="form-control" type="number" name="" value="{{$item->rating}}" id="">
                </div>


            </div>

        </div>
    </form>
@endsection
@push('js')
    @ckeditor
@endpush
