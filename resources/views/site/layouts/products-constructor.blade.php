@if(count($items))
    <div class="row">
        @foreach($items as $item)
            <div class="col-md-3 col-6 p-2 product-card_item">
                @include('site.components.product-card', [
                    'item' => $item
                ])
            </div>
        @endforeach

    </div>

    {!! $items->links() !!}

@else
    <div class="col-12">
        <h2 class="text-center tovarov-ne-naydeno my-2">Товаров не найдено</h2>
    </div>
@endif
