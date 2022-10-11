@extends('site.pages.cabinet.cabinet_layout')


@section('cabinetContent')
    <div class="orders-history-container">
        <div class="d-flex justify-content-center justify-content-lg-between flex-wrap">
            <h1 class="mb-xl-3 text-lg-left text-center p-lg-0 p-3">Активные заказы</h1>
            <div class="tabs-switchers-container">
               {{-- <a href="{{ route('cabinet.profile.orders.active', ['status' => 'in-process']) }}"
                   class="tab-switcher{{ $status == 'in-process' ? ' active' : '' }}">
                    <span>В процессе</span>
                </a>--}}
                {{--<a href="{{ route('cabinet.profile.orders.active', ['status' => 'pending']) }}" class="tab-switcher{{ $status == 'pending' ? ' active' : '' }}">
                    <span>В ожидании</span>
                </a>--}}
            </div>
        </div>
        <div class="orders-list-wrapper">
            @if(count($orders))
                @foreach($orders as $order)
                    @include('site.pages.cabinet.orders.order-card', ['order' => $order])
                @endforeach
                <!-- Modal -->
                    @if (session('order'))
                    <div class="modal fade" id="basket-modal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p class="text-center h5">Спасибо, Ваш заказ оформлен</p>
                                </div>
                                <div class="modal-footer m-0 p-0">
                                    <a href="/" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
            @else
                <h3 class="text-center">Заказов нет</h3>
            @endif
        </div>
    </div>
@endsection
@push('js')
<script>
    $(window).on('load', function() {
        $('#basket-modal1').modal('show');
    });
</script>
@endpush
