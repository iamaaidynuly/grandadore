@extends('admin.layouts.app')
@section('content')
    <form action="{!! $edit?route('admin.pickup_points.edit', ['id'=>$item->id]):route('admin.pickup_points.add') !!}"
          method="post">
        @csrf @method($edit?'patch':'put')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Адрес</div>
                    <div class="little-p">
                        <input type="text" name="address" class="form-control" placeholder="Адрес" maxlength="255"
                               value="{{ old('address', $item->address??null) }}">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Номер телефона</div>
                    <div class="little-p">
                        <input type="text" name="phone" class="form-control" placeholder="Номер телефона"
                               maxlength="255" value="{{ old('phone', $item->phone??null) }}">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Название</div>
                    <div class="little-p">
                        <input type="text" name="title" class="form-control" placeholder="Название" maxlength="255"
                               value="{{ old('title', $item->title??null) }}">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Статус</div>
                    <div class="little-p">
                        @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно', 'checked'=>oldCheck('active', ($edit
                        && empty($item->active))?false:true)])@endlabelauty
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="little-p">
                        <div id="map" style="width: 100%; height: 400px"></div>
                        <div class="mt-2" style="max-width: 300px">
                            <input type="text" name="lat" class="form-control map-inp lat" placeholder="Широта"
                                   maxlength="20" value="{{ old('lat', $item->lat??null) }}">
                            <input type="text" name="lng" class="form-control map-inp lng mt-2" placeholder="Долгота"
                                   maxlength="20" value="{{ old('lng', $item->lng??null) }}">
                            <button type="button" class="btn btn-secondary mt-2 show-on-map">Показать</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
@endsection
@push('js')
    <script src="https://api-maps.yandex.ru/2.1/?apikey=2e699e9e-5f6d-489c-ab71-0a755f489101&lang=ru_RU"></script>
    <script>
        (function () {
            ymaps.ready(init);
            var myMap, tempPlacemark = null,
                latInput = $('.map-inp.lat'), longInput = $('.map-inp.lng'),
                myPlacemark = {}, placemarkData = {};

            function showOnMap(position) {
                if (tempPlacemark !== null) {
                    myMap.geoObjects.remove(tempPlacemark);
                    tempPlacemark = null;
                    latInput.val('');
                    longInput.val('');
                }
                tempPlacemark = new ymaps.Placemark(position)
                myMap.geoObjects.add(tempPlacemark);
                latInput.val(position[0]);
                longInput.val(position[1]);
            }

            function init() {
                myMap = new ymaps.Map("map", {
                    center: [43.2, 76.9],
                    zoom: 10,
                });
                myMap.events.add('click', function (e) {
                    showOnMap(e.get('coords'));
                });
                $('.show-on-map').on('click', function () {
                    var lat = $.trim(latInput.val()),
                        long = $.trim(longInput.val());
                    if (lat != '' && long != '' && !isNaN(lat) && !isNaN(long)) {
                        showOnMap([lat, long]);
                        myMap.setCenter([lat, long], myMap.getZoom(), {duration: 300});
                    }
                });

                @php
                    $lat = old('lat', $item->lat??null);
                    $lng = old('lng', $item->lng??null);
                @endphp
                @if ($lat!==null && $lng!==null && is_numeric($lat) && is_numeric($lng))
                showOnMap([{{ $lat }}, {{ $lng }}]);
                myMap.setCenter([{{ $lat }}, {{ $lng }}]);
                @endif
            }
        })();
    </script>
@endpush
