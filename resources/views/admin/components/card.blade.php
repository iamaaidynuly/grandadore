<div class="card">
    <div class="card-body">
        @unless(empty($title))
            <div class="card-title banner-card-title">{{ $title }}</div>
        @endunless
        {!! $slot !!}
    </div>
</div>