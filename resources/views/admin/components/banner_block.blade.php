<div class="banner-block">
    @unless(empty($title))
        <div class="banner-block-title">{{ $title }}</div>
    @endunless
    {!! $slot !!}
</div>



