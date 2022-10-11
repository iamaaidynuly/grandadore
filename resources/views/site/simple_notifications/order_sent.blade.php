@extends('site.notifications.layout')
@section('content')
    @if ($item->payment_method=='bank')
        <p>Ваш заказ на сайте Dev.loc одобрен, вы можете оплатить заказ онлайн способом в личном кабинете.</p>
    @else
        <p>Ваш заказ на сайте Dev.loc одобрен. Доставка покупки будет организован согласно условиям и срокам торговой площадки.</p>
    @endif
@endsection
