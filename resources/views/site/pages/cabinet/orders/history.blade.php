@extends('site.pages.cabinet.cabinet_layout')

@section('cabinetContent')

    <div class="orders-history-container">
        <div class="d-flex justify-content-center justify-content-lg-between flex-wrap">
            <h1 class="mb-xl-3 text-lg-left text-center p-lg-0 p-3">Архив заказов</h1>
            <div class="tabs-switchers-container">
                <a href="{{ route('cabinet.profile.orders.history', ['status' => 'done']) }}"
                   class="tab-switcher{{ $status == 'done' ? ' active' : '' }}">
                    <span>Завершенные</span>
                </a>
                <a href="{{ route('cabinet.profile.orders.history', ['status' => 'declined']) }}"
                   class="tab-switcher{{ $status == 'declined' ? ' active' : '' }}">
                    <span>Отказаные</span>
                </a>
            </div>
        </div>
        <div class="orders-list-wrapper">
            @if(count($orders))
                @foreach($orders as $order)
                    @include('site.pages.cabinet.orders.order-card', ['order' => $order])
                @endforeach
            @else
                <h3 class="text-center">Заказов нет</h3>
            @endif
        </div>

    </div>
@endsection
@push('js')
    <script>
        const flatpickrConfig = {
            dateFormat: "d/m/Y",
            "locale": flatpickrRussianLocalization
        };
        $('.date-inputs').flatpickr(flatpickrConfig);
    </script>
@endpush
