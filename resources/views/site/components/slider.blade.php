

@push('js')
    <script src="{{ asset('js/swiper.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endpush

<div class="container1920">
    <div class="swiper-container home-swiper">
        <div class="swiper-wrapper">
            @foreach($slider as $i => $slide)
                @if($slide->image)
                    <div class="swiper-slide">
                        <a href="@if($slide->url) {{ $slide->url }} @else javascript:void(0) @endif">
                            <img class="img-fluid" src="{{ asset('u/main_slider/'.$slide->image) }}" alt="" title="">
                        </a>
                    </div>
                @endif
            @endforeach
        </div>

        @if(count($slider) > 1)
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- <div class="swiper-pagination"></div> -->
        @endif
    </div>
</div>
