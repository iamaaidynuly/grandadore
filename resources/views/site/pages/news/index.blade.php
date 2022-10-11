@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'News')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-center justify-content-sm-start indent mt-5 mb-5">
            @foreach($articles as $article)
                @include('site.components.news-card', ['item' => $article])
            @endforeach
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-12">
            {!! $articles->links() !!}
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">
@endpush
