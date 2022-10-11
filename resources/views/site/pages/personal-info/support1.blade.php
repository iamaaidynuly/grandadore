@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'personal-info')

@section('content')
    <div class="container ">

            <div class="personal-info-bar-btn">
                <div class="btn-section">
                    <div class="personal-info-button">
                        <div class="toggle-wrap-personal-info">
                            <span class="toggle-bar"></span>
                        </div>
                    </div>

                </div>
            </div>
        <div class="d-flex mt-xl-5">
            @include('site.components.personal-infobar')
            <div class="personal-inforamtion w-100 ml-lg-5 mt-1">
            <form>
                <div class="personal-inforamtion w-100 ml-lg-5">
                    <h1 class="mb-5">Подержка</h1>
                        <div class="form-group row">
                            <div class="col-md-4 col-12 my-md-0 my-2">
                                <input type="text"  class="form-control" id="name" value="Мариям Ибраева">
                            </div>
                            <div class="col-md-4 col-12 my-md-0 my-2">
                                <input type="text"  class="form-control phonenumber"  id="phone">
                            </div>
                            <div class="col-md-4 col-12 my-md-0 my-2">
                                <input type="mail"  class="form-control "  id="mail">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="" id="" class="form-control" placeholder="Опишите проблему или вопрос." rows="10"></textarea>
                        </div>
                <div class="d-flex justify-content-end align-items-end flex-column p-0 mb-5">
                    <button class="btn btn-grey mt-2">Отправить</button>
                </div>
                            <div class="form-group">
                            <div class="accordion section-support" id="accordionExample">
                                <div class="card">
                                    <div class="card-header m-0 p-0" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link w-100 text-left py-3" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Возмещение средств через платежную систему mercado pago (mp)
                                                <i class="float-right fa fa-angle-up"></i>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            Из соображений безопасности, возврат средств производится только на оригинальную карту, с которой был списан платеж. Если срок действия оригинальной карты истек или она больше недействительна, вы можете обратиться за помощью в организацию, выдавшую карту (ваш банк или компания по выпуску платежных карт), чтобы она получила возврат средств от вашего имени.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header  m-0 p-0" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed w-100 text-left py-3" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Возврат средств при оплате смс
                                                <i class="float-right fa fa-angle-up"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            Из соображений безопасности, возврат средств производится только на оригинальную карту, с которой был списан платеж. Если срок действия оригинальной карты истек или она больше недействительна, вы можете обратиться за помощью в организацию, выдавшую карту (ваш банк или компания по выпуску платежных карт), чтобы она получила возврат средств от вашего имени.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


            </form>
            </div>

        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">
@endpush
@section('js')
    <script src="{{ asset('assets/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/personal-info.js') }}"></script>

    <script>
        $('.select2').select2();
        $('.select21').select2();
        $('.select211').select2();
    </script>
@endsection
