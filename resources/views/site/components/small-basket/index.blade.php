
<div class="basket__mini">
    <p class="product__numbers">{{ count($basketItems = $basketService->getItems()) ? t('basket.basket') : t('basket.basket_empty') }}</p>
{{--    <p class="product__numbers">123</p>--}}

    <div class="small-basket-wrapper">

    </div>
</div>

{{--<div class="card-box-open">
    <div class="card-header">
        <div class="d-flex  justify-content-center align-items-center"><img
                src="{{asset('images/icons/shopping-basket.svg')}}" alt="">
            <p class="m-0 pl-2">Карзина</p></div>
        <button class="btn card-close-btn">&times;</button>
    </div>
    <div class="small-basket-wrapper">

    </div>

</div>--}}
