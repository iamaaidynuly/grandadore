@php $sid='cards_'.$banners; if(!empty($id)) $sid.='_'.$id; @endphp
@for($i=0; $i<$count; $i++)
    @php $active = $i==0?' active':''; $prefix='_'.$i @endphp
@section('card_header')
    <li class="nav-item">
        <a class="nav-link{!! $active !!}" data-toggle="tab" href="#{!! $sid.$prefix !!}" role="tab">
            <span class="hidden-sm-up"></span>
            <span class="hidden-xs-down">{!! $i+1 !!}</span>
        </a>
    </li>
    @if($i==0) @overwrite @else @append @endif
@section('card_content')
    <div class="tab-pane{!! $active !!}" id="{!! $sid.$prefix !!}" role="tabpanel">
        {!! ${'tab'.$prefix} !!}
    </div>
    @if($i==0) @overwrite @else @append @endif
@endfor
<div class="card">
    <div class="card-body">
        <div class="bylang-header">
            @unless(empty($title))
                <div class="card-title banner-card-title">{{ $title }}</div>
            @endunless
            <ul class="nav nav-tabs bylang-nav-tabs" role="tablist">
                @yield('card_header')
            </ul>
        </div>
        <div class="tab-content tabcontent-border pt-2">
            @yield('card_content')
        </div>
    </div>
</div>