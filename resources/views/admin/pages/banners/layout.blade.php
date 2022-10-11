@extends('admin.layouts.app')
@section('content')
    <div class="banner-page">
        <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
            @csrf
            @yield('body')
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </form>
    </div>
@endsection
@push('js')
    @ckeditor
    @js(aApp('minicolors/jquery.minicolors.js'))
    @js(aApp('datepicker/datepicker.js'))
@endpush
@push('css')
    @css(aApp('minicolors/jquery.minicolors.css'))
    @css(aApp('datepicker/datepicker.css'))
@endpush
