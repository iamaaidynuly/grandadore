@if(app()->get(\App\Services\ExchangeRateDetector\ExchangeRateDetector::class)->getCountry() == 'RU')
    <sub>₽</sub>
@else
    <sub>₸</sub>
@endif
