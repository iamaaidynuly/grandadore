@extends('site.pages.cabinet.cabinet_layout')

@section('cabinetContent')
    <div class="orders-history-container">
        <div class="d-flex justify-content-center justify-content-lg-between flex-wrap">
            <h1 class="mb-xl-3 text-lg-left text-center p-lg-0 p-3">Активные заказы</h1>
            <div class="tabs-switchers-container">
                <a href="{{ route('cabinet.profile.orders.active', ['status' => 'in-process']) }}" class="tab-switcher active">
                    <span>В процессе</span>
                </a>
                <a href="{{ route('cabinet.profile.orders.active', ['status' => 'pending']) }}" class="tab-switcher">
                    <span>В ожицании</span>
                </a>
            </div>
        </div>
    </div>
@endsection
