@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Products')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endpush
@push('js')
    <script src="{{ asset('js/product-list.js') }}"></script>
    <script src="{{ asset('js/viewModel-bundle.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <h1 class="text-center my-3" style="font-size: 2rem">{{ request()->query('searchQuery') . ' - ' . __('app.search results') }}</h1>
        <div class="product-list row">
            @if(count($items))
                @foreach($items as $item)
                    <div class="col-lg-2 col-md-3 col-6 mt-2">
                        @include('site.components.product-card', [
                            'item' => $item
                        ])
                    </div>
                @endforeach
                <div class="col-12">
                    <div class="pagination-wrapper">
                        {!! $items->links() !!}
                    </div>
                </div>
            @else
                <div class="col-12">
                    <h2 class="text-center my-2">Товаров не найдено</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
