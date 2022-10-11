@extends('site.layouts.main', ['headerSidebar' => true])
@section('css')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row contact-page">
            <div class="col-lg-6 col-12">
                <div class="our-contact">
                    <h3>Наши контакты</h3>
                    @if(!empty($infos->address))
                        <div class="rounded-input">
                            <div class="icon-section">
                                <!-- <img src="{{asset('images/loaction.svg')}}" alt=""> -->
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p>{{ $infos->address->text }}</p>
                        </div>
                    @endif
                    @foreach($infos->contacts as $contact)
                        @if(!is_null($contact->phone))
                            <div class="rounded-input">
                                <div class="icon-section">
                                    <!-- <img src="{{asset('images/phone.svg')}}" alt=""> -->
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                            </div>
                        @endif
                    @endforeach
                    @foreach($infos->contacts as $contact)
                        @if(!is_null($contact->email))
                            <div class="rounded-input">
                                <div class="icon-section">
                                    <!-- <img src="{{asset('images/mail.svg')}}" alt=""> -->
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"> {{session('success')}} ։
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="col-lg-6 col-12 ">
                <div class="feedback">
                    <h3>Обратная связь</h3>
                    <form action="{{ route('newMessage') }}" method="post">
                        @csrf
                        <div class="input-field">
                            <label for="name">ФИО
                                <span class="first-letter">*</span>
                            </label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? (authUser() ? authUser()->name : null) ?? '' }}">
                            @if($errors->has('name'))
                                <span style="color: red">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="input-field">
                            <label for="phone">Контактный телефон
                                <span class="first-letter">* </span>
                            </label>
                            <input id="phone" type="number" class="form-control" name="phone" value="{{ old('phone') ?? (authUser() ? authUser()->phone : null) ?? '' }}">
                            @if($errors->has('phone'))
                                <span style="color: red">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <div class="input-field">
                            <label for="email">Email
                                <span class="first-letter">* </span>
                            </label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? (authUser() ? authUser()->email : null) ?? '' }}">
                            @if($errors->has('email'))
                                <span style="color: red">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="input-field">
                            <label for="message">Текст сообщения
                                <span class="first-letter">* </span>
                            </label>
                            <textarea name="message" class="form-control nice-input" id="message">{{ old('message') ?? '' }}</textarea>
                            @if($errors->has('message'))
                                <span style="color: red">{{ $errors->first('message') }}</span>
                            @endif
                        </div>

                        <button class="btn btn-send" type="submit">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="iframe my-5">
            <a class="dg-widget-link"
               href="http://2gis.kz/almaty/firm/70000001025749381/center/76.90163612365724,43.31641992801292/zoom/16?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=bigMap">Посмотреть
                на карте Алматы</a>
            <div class="dg-widget-link"><a
                    href="http://2gis.kz/almaty/firm/70000001025749381/photos/70000001025749381/center/76.90163612365724,43.31641992801292/zoom/17?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=photos">Фотографии
                    компании</a></div>
            <div class="dg-widget-link"><a
                    href="http://2gis.kz/almaty/center/76.901643,43.316116/zoom/16/routeTab/rsType/bus/to/76.901643,43.316116╎Арлан, торговый рынок?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=route">Найти
                    проезд до Арлан, торговый рынок</a></div>
            <script charset="utf-8" src="https://widgets.2gis.com/js/DGWidgetLoader.js"></script>
            <script charset="utf-8">new DGWidgetLoader({
                    "width": '100%',
                    "height": 300,
                    "borderColor": "#a3a3a3",
                    "pos": {"lat": 43.31641992801292, "lon": 76.90163612365724, "zoom": 16},
                    "opt": {"city": "almaty"},
                    "org": [{"id": "70000001025749381"}]
                });</script>
            <noscript style="color:#c00;font-size:16px;font-weight:bold;">Виджет карты использует JavaScript. Включите
                его в настройках вашего браузера.
            </noscript>
        </div>
    </div>
@endsection
