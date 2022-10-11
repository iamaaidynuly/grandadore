@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            @if($order->payment_method == 'bank')
                <span>Ваш заказ на сайте Dev.loc одобрен, вы можете оплатить заказ безналичным способом в личном кабинете.</span>
            @else
                <span>Ваш заказ на сайте Dev.loc одобрен. Доставка покупки будет организован согласно условиям и срокам торговой площадки.</span>
            @endif
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', [
                'text' => 'Перейти в личный кабинет',
                'link' => $url ?? '#'
            ])
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')
@stop
