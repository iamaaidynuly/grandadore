@php $has_sub = !empty($slot->toHtml()); @endphp
<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark sidebar-link{!! $has_sub?' has-arrow':null !!}"
       href="{{ !$has_sub?safe($url, 'javascript:void(0)'):'javascript:void(0)' }}" aria-expanded="false"><i
            class="{{ $icon??null }}"></i><span class="hide-menu">{{ $title??null }}</span>
        @if (!empty($counter) && $counter>0)
            @php if($counter>9) $counter='9+'; @endphp
            <sup class="link-counter">{{ $counter }}</sup>
        @endif
    </a>
    @if ($has_sub)
        <ul aria-expanded="false" class="collapse first-level">
            {!! $slot !!}
        </ul>
    @endif
</li>


