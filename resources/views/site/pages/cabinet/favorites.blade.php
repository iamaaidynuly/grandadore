@extends('site.pages.cabinet.cabinet_layout')

@push('css')
    <link rel="stylesheet" href="{{asset('css/favorites.css')}}">
@endpush
@section('cabinetContent')
    @if(count($items))
        <div class="row">
            <div class="col-12" style="height: max-content"><h1 class="mb-3 title-default">Избранные товары</h1></div>
            @foreach($items as $item)
                <div class="col-md-3 col-lg-4 col-6 p-2">
                    @include('site.components.product-card', [
                        'item' => $item,
                        'removeAction' => true
                    ])
                </div>
            @endforeach
            <div class="col-12">
                {!! $items->links() !!}
            </div>
        </div>
    @else
        <h2 class="text-center">Товаров нет</h2>
    @endif
@endsection

@push('js')
    <script>
        favoritesBundle.removeCallback = function (itemId) {
            let element = $(`[data-id="${itemId}"].card-design`);

            element.parent().fadeOut(200);

            setTimeout(function () {
                element.parent().remove()
            }, 250);
        }
    </script>
@endpush
