@extends('admin.layouts.app')
@section('content')
    <form method="post" action="{{ route('admin.editThisSearch',['id'=>$search->id]) }}">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                    <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Название"
                           value="{{ ($search)?$search->title:old('title') }}">
                    @endbylang
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ce752946-5050-4a17-9d27-624b2dc71d8b&lang=ru_RU"></script>
{{--    <script>--}}
{{--        (function () {--}}
{{--            ymaps.ready(init);--}}
{{--            var myMap, tempPlacemark = null,--}}
{{--                latInput = $('.map-inp.lat'), longInput = $('.map-inp.lng'),--}}
{{--                myPlacemark = {}, placemarkData = {};--}}
{{--            function showOnMap(position) {--}}
{{--                if (tempPlacemark !== null) {--}}
{{--                    myMap.geoObjects.remove(tempPlacemark);--}}
{{--                    tempPlacemark = null;--}}
{{--                    latInput.val({{ $pickupPoint->lat }});--}}
{{--                    longInput.val({{ $pickupPoint->lng }});--}}
{{--                }--}}
{{--                tempPlacemark = new ymaps.Placemark(position)--}}
{{--                myMap.geoObjects.add(tempPlacemark);--}}
{{--                latInput.val(position[0]);--}}
{{--                longInput.val(position[1]);--}}
{{--                var myGeocoder = ymaps.geocode([position[0],position[1]]);--}}
{{--                //var myGeocoder = ymaps.geocode('Новый Арбат, 10');--}}
{{--                myGeocoder.then(--}}
{{--                    function (res) {--}}
{{--                        var coords = res.geoObjects.get(0).geometry.getCoordinates();--}}
{{--                        var myGeocoder = ymaps.geocode(coords, {kind: 'street'});--}}
{{--                        myGeocoder.then(--}}
{{--                            function (res) {--}}
{{--                                var street = res.geoObjects.get(0);--}}
{{--                                var name = street.properties.get('name');--}}
{{--                                //change(latInput.val(),longInput.val())--}}
{{--                                $('#lat').val(latInput.val())--}}
{{--                                $('#lng').val(longInput.val())--}}
{{--                                alert('ok');--}}
{{--                            }--}}

{{--                        );--}}
{{--                    });--}}
{{--            }--}}

{{--            @php isset($pickupPoint->lat) ? $x = $pickupPoint->lat:$x = 43.2386 ;--}}
{{--            isset($pickupPoint->lng) ?$y= $pickupPoint->lng:$y = 76.9671 ;--}}
{{--            $x = (float)$x ;--}}
{{--            $y = (float)$y ;--}}

{{--            @endphp--}}


{{--            function init() {--}}
{{--                myMap = new ymaps.Map("map", {--}}
{{--                    center:  [<?=$x ?>, <?=$y ?>],--}}
{{--                    zoom: 10,--}}
{{--                });--}}
{{--                 @if(isset($pickupPoint->lat))--}}
{{--                tempPlacemark = new ymaps.Placemark([{!! $x !!}, {!! $y !!}])--}}
{{--            myMap.geoObjects.add(tempPlacemark);--}}
{{--        @endif--}}
{{--                myMap.events.add('click', function (e) {--}}
{{--                    showOnMap(e.get('coords'));--}}
{{--                });--}}
{{--                $('.show-on-map').on('click', function () {--}}
{{--                    var lat = $.trim(latInput.val()),--}}
{{--                        long = $.trim(longInput.val());--}}
{{--                    if (lat != '' && long != '' && !isNaN(lat) && !isNaN(long)) {--}}
{{--                        showOnMap([lat, long]);--}}
{{--                        myMap.setCenter([lat, long], myMap.getZoom(), {duration: 300});--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        })();--}}
{{--    </script>--}}


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
