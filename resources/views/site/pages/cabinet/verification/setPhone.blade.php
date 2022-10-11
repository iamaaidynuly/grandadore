@extends('site.pages.cabinet.cabinet_layout')

@section('cabinetContent')
        <div class="align-items-center d-flex flex-column mt-4 w-100">
            <div class="login-section">
                <form method="POST" action="{{ route('cabinet.phoneVerification.setPhone') }}" class="w-100 d-contents" autocomplete="off">
                    @csrf
                    <p class="sub-text mb-0">Для доступа ко всем функциям сайта, пожалуйста предоставьте ваш номер телефона</p>
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <svg id="_001-phone-call" data-name="001-phone-call"
                                     xmlns="http://www.w3.org/2000/svg" width="15.811" height="15.811"
                                     viewBox="0 0 15.811 15.811">
                                    <g id="Group_5701" data-name="Group 5701">
                                        <path id="Path_11355" data-name="Path 11355"
                                              d="M14.562,10.41a9.025,9.025,0,0,1-2.837-.452,1.216,1.216,0,0,0-1.234.261L8.7,11.575A10.026,10.026,0,0,1,4.235,7.114L5.554,5.361a1.26,1.26,0,0,0,.309-1.273,9.049,9.049,0,0,1-.454-2.84A1.25,1.25,0,0,0,4.161,0H1.248A1.25,1.25,0,0,0,0,1.248,14.579,14.579,0,0,0,14.562,15.811a1.25,1.25,0,0,0,1.248-1.248v-2.9A1.25,1.25,0,0,0,14.562,10.41Zm.416,4.152a.417.417,0,0,1-.416.416A13.746,13.746,0,0,1,.832,1.248.417.417,0,0,1,1.248.832H4.161a.417.417,0,0,1,.416.416,9.879,9.879,0,0,0,.493,3.1.461.461,0,0,1-.143.474L3.412,6.823a.416.416,0,0,0-.038.439,10.918,10.918,0,0,0,5.174,5.174.414.414,0,0,0,.44-.038l2.049-1.553a.417.417,0,0,1,.424-.1,9.872,9.872,0,0,0,3.1.5.417.417,0,0,1,.416.416v2.9Z"
                                              fill="#858585"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <input type="text" class="form-control masked-phone-inputs" placeholder="Телефон в международном формате" name="phone"
                               value="{{ old('phone') }}">
                        @if($errors->has('phone'))
                            <div class="w-100">
                                <span class="input-alert">{{ $errors->first('phone') }}</span>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class=" btn login-btn">Подтвердить</button>
                </form>
            </div>
        </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
@endsection
@section('js')
    <script src="{{ asset('js/personal-info.js') }}"></script>
@endsection
