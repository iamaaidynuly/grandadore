@extends('admin.layouts.app')
@section('content')
    <form method="post" action="{{ route('admin.editThisAddress',['id'=>$pickupPoint->id]) }}">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="little-p">
                        <input type="text" name="address" class="form-control" placeholder="Адрес" maxlength="255"
                               value="{{ $pickupPoint->address }}"><br>
                        <input type="text" name="title" class="form-control" placeholder="Название" maxlength="255"
                               value="{{ $pickupPoint->title }}"><br>
                        <input type="number" name="phone" class="form-control" placeholder="phone" maxlength="255"
                               value="{{ $pickupPoint->phone }}">
                        <input type="text" id="lng" name="lng" hidden value="{{$pickupPoint->lng}}">
                        <input type="text" id="lat" name="lat" hidden value="{{$pickupPoint->lat}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>

    <div class="card col-12">
        <div
            class="c-title font-segeo font-13 font-bold mb-3">Выберите местоположение
        </div>
        <div class="c-body">
            <div class="little-p">
                <div id="map" style="width: 100%; height: 400px"></div>
                <div class="mt-2" style="display: none">
                    <input type="text" name="lat1" class="form-control map-inp lat"
                           placeholder="Широта" maxlength="20"
                           value="">
                    <input type="text" name="lng1" class="form-control map-inp lng mt-2"
                           placeholder="Долгота" maxlength="20"
                           value="">
                    <button type="button" class="btn btn-secondary mt-2 show-on-map">
                        Показать
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ce752946-5050-4a17-9d27-624b2dc71d8b&lang=ru_RU"></script>
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
                    latInput.val({{ $pickupPoint->lat }});
                    longInput.val({{ $pickupPoint->lng }});
                }
                tempPlacemark = new ymaps.Placemark(position)
                myMap.geoObjects.add(tempPlacemark);
                latInput.val(position[0]);
                longInput.val(position[1]);
                var myGeocoder = ymaps.geocode([position[0],position[1]]);
                //var myGeocoder = ymaps.geocode('Новый Арбат, 10');
                myGeocoder.then(
                    function (res) {
                        var coords = res.geoObjects.get(0).geometry.getCoordinates();
                        var myGeocoder = ymaps.geocode(coords, {kind: 'street'});
                        myGeocoder.then(
                            function (res) {
                                var street = res.geoObjects.get(0);
                                var name = street.properties.get('name');
                                //change(latInput.val(),longInput.val())
                                $('#lat').val(latInput.val())
                                $('#lng').val(longInput.val())
                                alert('ok');
                            }

                        );
                    });
            }

            @php isset($pickupPoint->lat) ? $x = $pickupPoint->lat:$x = 43.2386 ;
            isset($pickupPoint->lng) ?$y= $pickupPoint->lng:$y = 76.9671 ;
            $x = (float)$x ;
            $y = (float)$y ;

            @endphp


            function init() {
                myMap = new ymaps.Map("map", {
                    center:  [<?=$x ?>, <?=$y ?>],
                    zoom: 10,
                });
                 @if(isset($pickupPoint->lat))
                tempPlacemark = new ymaps.Placemark([{!! $x !!}, {!! $y !!}])
            myMap.geoObjects.add(tempPlacemark);
        @endif
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
            }
        })();
    </script>


@endsection
{{--@push('js')--}}
{{--@js(aApp('select2/select2.js'))--}}
{{--<script>--}}
{{--$('.select2').select2();--}}
{{--</script>--}}
{{--@endpush--}}
{{--@push('css')--}}
{{--@css(aApp('select2/select2.css'))--}}
{{--@endpush--}}
