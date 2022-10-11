@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Dynamic')

@section('content')
    @if($item->image && $item->show_image == 1)
        <div class="container-fluid indent">
            <img class="img-full" src="{{ asset('u/pages/'.$item->image) }}" alt="{{ $item->title }}" title="{{ $item->title }}">
        </div>
    @endif
    <div class="container-fluid indent">
        <div class="d-flex flex-column">
            <h1 class="paragraph__name">{{ $item->title }}</h1>
            <div class="indent">
                <p class="text__information">{!! $item->content !!}</p>
            </div>
        </div>
    </div>


    @if(count($gallery))
        <div class="container-fluid">
            <div class="gallery d-flex flex-row">
                @foreach($gallery as $image)
                    <div class="gallery-item">
                        <a data-fancybox="gallery" href="{{ asset('u/gallery/'.$image->image) }}">
                            <img class="img-fluid" src="{{ asset('u/gallery/thumbs/'.$image->image) }}" alt="" title="">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dynamic.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/fancybox.js') }}"></script>
@endpush
