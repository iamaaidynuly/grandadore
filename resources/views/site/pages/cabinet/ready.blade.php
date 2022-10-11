@extends('site.pages.cabinet.cabinet_layout')
@section('cabinetContent')

    <style>
        .text {
            font-size: 47px;
            color: #1F2535;
            font-family: 'Roboto-Condensed';
            font-weight: bold;
        }

        @media (max-width: 1439px) {
            .text {
                font-size: 40px;
            }
        }

        @media (max-width: 1199px) {
            .text {
                font-size: 30px;
            }
        }

        @media (max-width: 575px) {
            .text {
                font-size: 20px;
            }
        }
    </style>

    <div class="ready__content">
        <span class="text">Заказа<span class="orderNo">Nº {{request()->val}}</span></span>
        <span class="order-ready">Ваш заказ отправлен на подтверждение</span>
        <span class="opperator">Скоро наш оператор свяжется с вами</span>
    </div>

@endsection
