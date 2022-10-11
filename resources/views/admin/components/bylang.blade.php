@php if(empty($id)) $id='bylang_'.random_int(1,9999); @endphp
@foreach($languages as $language)
    @php $active = $loop->first?' active':''; $prefix='_'.$language['iso'] @endphp
@section('bylang_tabs')
    <li class="nav-item">
        <a class="nav-link{!! $active !!}" data-toggle="tab" href="#{!! $id.$prefix !!}" role="tab">
            <span class="hidden-sm-up"></span>
            <span class="hidden-xs-down">{!! $language['title'] !!}</span>
        </a>
    </li>
    @if($loop->first) @overwrite @else @append @endif
@section('bylang_content')
    <div class="tab-pane{!! $active !!} @safe($tp_classes, 'normal-p')" id="{!! $id.$prefix !!}" role="tabpanel">
        {!! ${'bylang'.$prefix} !!}
    </div>
    @if($loop->first) @overwrite @else @append @endif
@endforeach
<div id="{{ $id }}" class="bylang">
    <div class="bylang-header @safe($class)">
        <div class="bylang-title {!! !empty($title)?'has-title':null; !!}">@safe($title) @if(!empty($required))<sup style="color:red">*</sup>@endif </div>
        <ul class="nav nav-tabs bylang-nav-tabs" role="tablist">
            @yield('bylang_tabs')
        </ul>
    </div>
    <div class="tab-content tabcontent-border">
        @yield('bylang_content')
    </div>
</div>
