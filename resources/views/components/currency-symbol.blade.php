@if(app()->get(\App\Services\ExchangeRateDetector\ExchangeRateDetector::class)->getCountry() == 'RU')
    <span class="tenge">₽</span>
@else
    <span class="tenge">₸</span>
@endif
