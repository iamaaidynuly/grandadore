@php /** map  */ @endphp

<div class="card col-12 p-0 yandexMapStyle">
    <div
        class="c-title font-segeo font-13 font-bold mb-3">Mестоположение
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

<script>
    (function () {
        ymaps.ready(init);
        var myMap, tempPlacemark = null,
            latInput = $('.map-inp.lat'), longInput = $('.map-inp.lng'),
            myPlacemark = {}, placemarkData = {};


        function init() {
            myMap = new ymaps.Map("map", {
                center:  [<?=$x?>,<?=$y?> ],
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
@php /** endMap */ @endphp
