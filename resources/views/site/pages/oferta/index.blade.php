@extends('site.layouts.main')

@section('content')
    <div class="container">
        <h1>{{$item->content->title}}</h1>
    </div>

    <div class="container">
        {!!$item->content->content!!}
    </div>
@endsection


@push('css')@endpush
@push('js')@endpush
